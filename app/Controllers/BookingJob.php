<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Bookingjob\BookingJobModel;
use App\Models\Bookingjob\BookingJobTrashModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use App\Models\Master\MasterConsigneeModel;
use App\Models\Master\MasterPortModel;
use App\Models\Master\MasterPelayaranModel;

class BookingJob extends BaseController
{
    protected $bookingModel;
    protected $db;

    public function __construct()
    {
        helper(['form', 'url', 'auth']);
        $this->bookingModel = new BookingJobModel();
        $this->db = \Config\Database::connect();
    }

    // ============================================================
    // TAMPILKAN HALAMAN BOOKING JOB
    // ============================================================
    public function index()
    {
        // Cek apakah view booking_job tersedia
        if (is_file(APPPATH . 'Views/booking_job/index.php')) {
            return view('booking_job/index');
        }

        // Jika tidak, fallback ke view booking/index
        return view('booking/index');
    }

    // ============================================================
    // GENERATE NOMOR JOB BERIKUTNYA BERDASARKAN TYPE DAN TAHUN
    // ============================================================
    public function nextNo()
    {
        $type = $this->request->getGet('type');
        $year = date('y');

        $map = [
            'export'                => ['prefix' => 'EX', 'first' => '3'],
            'import_lcl'            => ['prefix' => 'IM', 'first' => '1'],
            'import_fcl_jaminan'    => ['prefix' => 'IM', 'first' => '4'],
            'import_fcl_nonjaminan' => ['prefix' => 'IM', 'first' => '6'],
            'lain'                  => ['prefix' => 'IM', 'first' => '5'],
        ];

        // Validasi tipe job
        if (!isset($map[$type])) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Tipe job tidak valid.'
            ])->setStatusCode(400);
        }

        $prefix     = $map[$type]['prefix'];
        $firstDigit = $map[$type]['first'];
        $like       = $prefix . '/' . $year . '/%';

        try {
            // Mulai transaksi untuk mencegah race condition
            $this->db->transStart();

            // Kunci tabel agar nomor tidak dobel
            $this->db->query("LOCK TABLES booking_job WRITE");

            // Ambil nomor terbesar berdasarkan type dan tahun
            $sql = "SELECT MAX(CAST(RIGHT(no_job,6) AS UNSIGNED)) as max_no
                    FROM booking_job
                    WHERE no_job LIKE ? AND type = ?";
            $row = $this->db->query($sql, [$like, $type])->getRow();

            $maxNo = (int) ($row ? $row->max_no : 0);

            // Tentukan nomor berikutnya
            if ($maxNo === 0) {
                // Tahun baru atau belum ada data → reset
                $nextNumeric = intval($firstDigit . str_repeat('0', 5)) + 1;
            } else {
                $nextNumeric = $maxNo + 1;

                // Pastikan digit pertama sesuai mapping
                $firstChar = substr((string)$nextNumeric, 0, 1);
                if ($firstChar !== $firstDigit) {
                    $nextNumeric = intval($firstDigit . str_repeat('0', 5)) + 1;
                }
            }

            // Lepas kunci tabel dan akhiri transaksi
            $this->db->query("UNLOCK TABLES");
            $this->db->transComplete();

            // Format hasil akhir nomor job
            $noJobNumeric = str_pad((string)$nextNumeric, 6, '0', STR_PAD_LEFT);
            $noJob = sprintf('%s/%s/%s', $prefix, $year, $noJobNumeric);

            return $this->response->setJSON([
                'status' => 'ok',
                'no_job' => $noJob
            ]);
        } catch (\Throwable $e) {
            // Pastikan tabel dilepas jika error
            $this->db->query("UNLOCK TABLES");
            $this->db->transComplete();

            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ])->setStatusCode(500);
        }
    }


    public function generateNoPIB()
    {
        $model = new BookingJobModel();

        // 1. Prefix tetap
        $prefix = "000020030484";

        // 2. Tanggal sekarang YYYYMMDD
        $tanggal = date("Ymd");

        // 3. Ambil data terakhir (berdasarkan id)
        $lastData = $model->orderBy('id', 'DESC')->first();

        if ($lastData && !empty($lastData['no_pib_po'])) {
            // Ambil 6 digit terakhir sebagai urutan
            $lastNumber = substr($lastData['no_pib_po'], -6);
            $nextNumber = intval($lastNumber) + 1;
        } else {
            // Kalau belum ada → mulai 1
            $nextNumber = 1;
        }

        // 4. Format urutan 6 digit
        $urut = str_pad($nextNumber, 6, "0", STR_PAD_LEFT);

        // 5. Gabung semua
        $noPIB = $prefix . $tanggal . $urut;

        // 6. Kembalikan JSON (PERHATIKAN: key = no_pib_po)
        return $this->response->setJSON([
            'no_pib_po' => $noPIB
        ]);
    }



    // SEARCH CONSIGNEE (SELECT2)
    public function searchConsignee()
    {
        $q = $this->request->getGet('term');

        // Validasi input
        if (empty($q)) {
            return $this->response->setJSON([]);
        }

        $model = new MasterConsigneeModel();
        $data = $model
            ->select('id, kode, nama_consignee')
            ->groupStart()
            ->like('kode', $q)
            ->orLike('nama_consignee', $q)
            ->groupEnd()
            ->limit(20)
            ->findAll();

        $results = [];

        foreach ($data as $row) {
            $results[] = [
                'id'   => $row['id'],
                'text' => $row['nama_consignee']
            ];
        }

        return $this->response->setJSON($results);
    }

    // SEARCH PORT (SELECT2)
    public function searchPort()
    {
        $q = $this->request->getGet('term');

        // Validasi input
        if (empty($q)) {
            return $this->response->setJSON([]);
        }

        $model = new MasterPortModel();
        $data = $model
            ->select('id, kode, nama_port')
            ->groupStart()
            ->like('kode', $q)
            ->orLike('nama_port', $q)
            ->groupEnd()
            ->limit(20)
            ->findAll();

        $results = [];

        foreach ($data as $row) {
            $results[] = [
                'id'   => $row['id'],
                'text' => $row['nama_port']
            ];
        }

        return $this->response->setJSON($results);
    }

    // SEARCH PELAYARAN (SELECT2)
    public function searchPelayaran()
    {
        $q = $this->request->getGet('term');

        // Validasi input
        if (empty($q)) {
            return $this->response->setJSON([]);
        }

        $model = new MasterPelayaranModel();
        $data = $model
            ->select('id, kode, nama_pelayaran')
            ->groupStart()
            ->like('kode', $q)
            ->orLike('nama_pelayaran', $q)
            ->groupEnd()
            ->limit(20)
            ->findAll();

        $results = [];

        foreach ($data as $row) {
            $results[] = [
                'id'   => $row['id'],
                'text' => $row['nama_pelayaran']
            ];
        }

        return $this->response->setJSON($results);
    }


    // ============================================================
    // SIMPAN DATA BOOKING JOB BARU
    // ============================================================
    public function store()
    {
        $validation = \Config\Services::validation();

        // Aturan validasi input
        $rules = [
            'no_job'        => 'required|is_unique[booking_job.no_job]',
            'type'          => 'required',
            'consignee'     => 'required',
            'no_pib_po'     => 'required',
            'party'         => 'required',
            'eta'           => 'required',
            'pol'           => 'required',
            'shipping_line' => 'required',
            'bl'            => 'required|is_unique[booking_job.bl]',
            'master_bl'     => 'required',
        ];

        $messages = [
            'no_job' => [
                'required'  => 'Nomor job wajib diisi.',
                'is_unique' => 'Nomor job sudah ada, silakan generate ulang.'
            ],
            'bl' => [
                'required'  => 'Nomor BL wajib diisi.',
                'is_unique' => 'Nomor BL sudah ada.'
            ]
        ];

        $validation->setRules($rules, $messages);

        // Jika validasi gagal
        if (!$validation->withRequest($this->request)->run()) {
            $errors = $validation->getErrors();

            // Jika BL sudah ada, tampilkan info no_job terkait
            if (isset($errors['bl']) && str_contains($errors['bl'], 'Nomor BL sudah ada')) {
                $bl = $this->request->getPost('bl');

                $existing = $this->bookingModel
                    ->select('no_job')
                    ->where('bl', $bl)
                    ->first();

                if ($existing) {
                    $errors['bl'] .= " (sudah digunakan di No Job: {$existing['no_job']})";
                }
            }

            return $this->response->setStatusCode(422)->setJSON([
                'status'  => 'error',
                'message' => implode("\n", $errors)
            ]);
        }

        // Data yang akan disimpan
        $data = [
            'no_job'        => $this->request->getPost('no_job'),
            'type'          => $this->request->getPost('type'),
            'consignee'     => $this->request->getPost('consignee'),
            'party'         => $this->request->getPost('party'),
            'eta'           => $this->request->getPost('eta'),
            'pol'           => $this->request->getPost('pol'),
            'no_pib_po'     => $this->request->getPost('no_pib_po'),
            'shipping_line' => $this->request->getPost('shipping_line'),
            'bl'            => $this->request->getPost('bl'),
            'master_bl'     => $this->request->getPost('master_bl'),
            'status'        => 'open job'
        ];

        try {
            // Simpan ke database
            $this->bookingModel->insert($data);

            return $this->response->setJSON([
                'status'  => 'ok',
                'message' => 'Booking berhasil disimpan'
            ]);
        } catch (\Exception $e) {
            // Jika gagal simpan
            return $this->response->setStatusCode(500)->setJSON([
                'status'  => 'error',
                'message' => 'Terjadi kesalahan saat menyimpan booking'
            ]);
        }
    }

    // ============================================================
    // TAMPILKAN DAFTAR BOOKING JOB
    // ============================================================
    public function list()
    {
        $type = $this->request->getGet('type') ?? '';

        $builder = $this->bookingModel;
        if ($type !== '') {
            $builder = $builder->where('type', $type);
        }

        $rows = $builder->orderBy('no_job', 'ASC')->findAll();

        $data = [];
        foreach ($rows as $r) {
            // Normalisasi setiap row agar jadi array
            $data[] = [
                'id'           => $r['id'],
                'no_job'       => $r['no_job'],
                'type'         => $r['type'],
                'consignee'    => $r['consignee'],
                'party'        => $r['party'],
                'eta'          => $r['eta'],
                'pol'          => $r['pol'],
                'no_pib_po'    => $r['no_pib_po'],
                'shipping_line' => $r['shipping_line'],
                'bl'           => $r['bl'],
                'status'       => $r['status'],
                'master_bl'    => $r['master_bl'],
                'created_at'   => $r['created_at'],
            ];
        }

        return $this->response->setJSON(['data' => $data]);
    }

    // ============================================================
    // HAPUS DATA BOOKING DAN PINDAHKAN KE TRASH
    // ============================================================
    public function delete($id)
    {
        try {
            $trashModel = new BookingJobTrashModel();

            // Cek apakah data ada
            $row = $this->bookingModel->find($id);
            if (!$row) {
                return $this->response->setJSON([
                    'status'  => 'error',
                    'message' => 'Data tidak ditemukan'
                ])->setStatusCode(404);
            }

            // Tambahkan info penghapusan
            $row['deleted_at'] = date('Y-m-d H:i:s');
            $row['deleted_by'] = (function () {
                try {
                    return user() ? user()->username : 'system';
                } catch (\Throwable $t) {
                    return 'system';
                }
            })();

            // Hapus id lama sebelum insert ke trash
            if (isset($row['id'])) {
                unset($row['id']);
            }

            // Pastikan type ada
            if (!isset($row['type'])) {
                $row['type'] = null;
            }

            // Masukkan ke tabel trash
            $inserted = $trashModel->insert($row);
            if ($inserted === false) {
                $error = $this->db->error();
                log_message('error', 'Gagal insert ke booking_job_trash: ' . json_encode($error));
                return $this->response->setJSON([
                    'status'  => 'error',
                    'message' => 'Gagal memindahkan data ke sampah (DB error).'
                ])->setStatusCode(500);
            }

            // Hapus dari tabel utama
            $this->db->table('booking_job')->where('id', $id)->delete();

            return $this->response->setJSON([
                'status'  => 'ok',
                'message' => 'Data berhasil dipindahkan ke sampah'
            ]);
        } catch (\Exception $e) {
            log_message('error', 'BookingJob::delete error: ' . $e->getMessage());
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Gagal menghapus data'
            ])->setStatusCode(500);
        }
    }

    // ============================================================
    // RESTORE DATA DARI TABEL SAMPAH
    // ============================================================
    public function restore($id)
    {
        $db = \Config\Database::connect();

        // Ambil data dari tabel sampah
        $trash = $db->table('booking_job_trash')->where('id', $id)->get()->getRowArray();

        if (!$trash) {
            return $this->response->setStatusCode(404)->setJSON([
                'status'  => 'error',
                'message' => 'Data tidak ditemukan di sampah.'
            ]);
        }

        // Cek apakah no_job atau bl sudah digunakan
        $exists = $db->table('booking_job')
            ->where('no_job', $trash['no_job'])
            ->orWhere('bl', $trash['bl'])
            ->countAllResults();

        if ($exists > 0) {
            return $this->response->setStatusCode(409)->setJSON([
                'status'  => 'error',
                'message' => "Tidak bisa restore. No Job ({$trash['no_job']}) atau BL ({$trash['bl']}) sudah dipakai."
            ]);
        }

        // Siapkan data untuk insert ke booking_job
        unset($trash['id'], $trash['deleted_at'], $trash['deleted_by']);

        // Set ulang timestamp
        $now = date('Y-m-d H:i:s');
        $trash['created_at'] = $now;
        $trash['updated_at'] = $now;

        // Insert ke tabel utama
        $this->bookingModel->protect(false);
        $inserted = $this->bookingModel->insert($trash);

        if ($inserted === false) {
            $error = $this->db->error();
            log_message('error', 'Gagal insert restore booking_job: ' . json_encode($error));
            return $this->response->setStatusCode(500)->setJSON([
                'status'  => 'error',
                'message' => 'Gagal merestore data (DB error).'
            ]);
        }

        // Hapus dari tabel trash
        $db->table('booking_job_trash')->where('id', $id)->delete();

        return $this->response->setJSON([
            'status'  => 'ok',
            'message' => 'Data berhasil direstore.'
        ]);
    }

    // ============================================================
    // AMBIL DATA UNTUK EDIT
    // ============================================================
    public function edit($id)
    {
        $row = $this->bookingModel->find($id);
        if (!$row) {
            return $this->response->setStatusCode(404)->setJSON([
                'status'  => 'error',
                'message' => 'Data tidak ditemukan.'
            ]);
        }

        return $this->response->setJSON([
            'status' => 'ok',
            'data'   => $row
        ]);
    }

    // ============================================================
    // UPDATE DATA BOOKING JOB
    // ============================================================
    public function update($id)
    {
        $validation = \Config\Services::validation();

        // Aturan validasi
        $rules = [
            'type'          => 'required',
            'consignee'     => 'required',
            'no_pib_po'     => 'required',
            'party'         => 'required',
            'eta'           => 'required',
            'pol'           => 'required',
            'shipping_line' => 'required',
            'master_bl'     => 'required',
        ];

        $messages = [
            'type'      => ['required' => 'Tipe wajib diisi.'],
            'consignee' => ['required' => 'Consignee wajib diisi.'],
            'bl'        => ['required'  => 'Nomor BL wajib diisi.']
        ];

        // Validasi unique tapi abaikan ID sendiri
        $rules['no_job'] = "required|is_unique[booking_job.no_job,id,{$id}]";
        $rules['bl']     = "required|is_unique[booking_job.bl,id,{$id}]";

        $validation->setRules($rules, $messages);

        // Jika gagal validasi
        if (!$validation->withRequest($this->request)->run()) {
            $errors = $validation->getErrors();
            return $this->response->setStatusCode(422)->setJSON([
                'status'  => 'error',
                'message' => implode("\n", $errors)
            ]);
        }

        // Data yang akan diupdate
        $data = [
            'no_job'        => $this->request->getPost('no_job'),
            'type'          => $this->request->getPost('type'),
            'consignee'     => $this->request->getPost('consignee'),
            'party'         => $this->request->getPost('party'),
            'eta'           => $this->request->getPost('eta'),
            'pol'           => $this->request->getPost('pol'),
            'no_pib_po'     => $this->request->getPost('no_pib_po'),
            'shipping_line' => $this->request->getPost('shipping_line'),
            'bl'            => $this->request->getPost('bl'),
            'master_bl'     => $this->request->getPost('master_bl'),
            'updated_at'    => date('Y-m-d H:i:s')
        ];

        try {
            // Lakukan update
            $this->bookingModel->update($id, $data);

            return $this->response->setJSON([
                'status'  => 'ok',
                'message' => 'Data berhasil diperbarui.'
            ]);
        } catch (\Exception $e) {
            log_message('error', 'BookingJob::update error: ' . $e->getMessage());
            return $this->response->setStatusCode(500)->setJSON([
                'status'  => 'error',
                'message' => 'Terjadi kesalahan saat update data.'
            ]);
        }
    }

    /**
     * ==============================================================
     * EXPORT DATA BOOKING JOB KE EXCEL
     * ==============================================================
     * Fungsi ini mengekspor data booking job ke format Excel (.xlsx)
     * dengan filter tahun dan bulan, serta pengelompokan jenis job.
     * ==============================================================
     */
    public function exportExcel($type = 'all')
    {
        $bookingModel = new BookingJobModel();

        // Ambil filter tahun dan bulan dari parameter GET
        $year  = $this->request->getGet('year');
        $month = $this->request->getGet('month');

        // Jika $month dikirim sebagai array (misalnya multiple select)
        if (is_array($month)) {
            $month = reset($month);
        }

        // Validasi sederhana (pastikan angka)
        $year  = is_numeric($year) ? (int)$year : null;
        $month = is_numeric($month) ? (int)$month : null;

        // Definisi label dan warna untuk tiap kategori
        $filters = [
            'export'                => 'Export',
            'import_lcl'            => 'Import LCL',
            'import_fcl_jaminan'    => 'FCL Jaminan',
            'import_fcl_nonjaminan' => 'FCL Non-Jmn',
            'lain'                  => 'Lain-lain',
            'all'                   => 'Semua Job'
        ];

        $headerColors = [
            'export'                => '4F81BD',
            'import_lcl'            => '9BBB59',
            'import_fcl_jaminan'    => 'F79646',
            'import_fcl_nonjaminan' => 'C0504D',
            'lain'                  => '8064A2',
            'all'                   => '31859B'
        ];

        // Siapkan spreadsheet
        $spreadsheet = new Spreadsheet();
        $spreadsheet->removeSheetByIndex(0);
        $sheetIndex = 0;

        // Tentukan jenis export: semua atau hanya satu
        $exportTypes = ($type == 'all') ? array_keys($filters) : [$type];

        foreach ($exportTypes as $t) {
            $query = $bookingModel->builder();

            if ($t != 'all') {
                $query->where('type', $t);
            }
            if ($year) {
                $query->where('YEAR(created_at)', $year, false);
            }
            if ($month) {
                $query->where('MONTH(created_at)', $month, false);
            }

            $data = $query->get()->getResultArray();

            // Informasi periode (bulan/tahun)
            $periodInfo = '';
            if ($year && $month) {
                $periodInfo = " (" . str_pad($month, 2, '0', STR_PAD_LEFT) . "-$year)";
            } elseif ($year) {
                $periodInfo = " (Tahun $year)";
            }

            $title = substr($filters[$t] . $periodInfo, 0, 31);

            // Gunakan fungsi bantu untuk membuat sheet
            $this->createBookingSheet($spreadsheet, $sheetIndex, $t, $title, $data, $headerColors);
            $sheetIndex++;
        }

        // Set active sheet pertama
        $spreadsheet->setActiveSheetIndex(0);

        // Penamaan file dinamis
        $period = '';
        if ($year && $month) {
            $period = "_{$year}-" . str_pad($month, 2, '0', STR_PAD_LEFT);
        } elseif ($year) {
            $period = "_{$year}";
        }

        $filename = 'Booking_' . $type . '_' . $period . '_' . date('Y-m-d_His') . '.xlsx';

        // Header download Excel
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header('Cache-Control: max-age=0');

        // Simpan output ke browser
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    /**
     * ==============================================================
     * FUNGSI BANTU MEMBUAT SHEET BOOKING JOB
     * ==============================================================
     */
    private function createBookingSheet($spreadsheet, $sheetIndex, $type, $title, $data, $headerColors)
    {
        $sheet = $spreadsheet->createSheet($sheetIndex);
        $sheet->setTitle(substr($title, 0, 30));

        // ========== TAMBAHKAN JUDUL DI ATAS TABEL ==========

        // Merge cell untuk judul (A1 sampai J1)
        $sheet->mergeCells('A1:J1');

        // Set nilai judul
        $sheet->setCellValue('A1', strtoupper('List Booking Job ' . $title));

        // Style untuk judul
        $sheet->getStyle('A1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 14,
                'color' => ['argb' => 'FFFFFFFF']
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FF' . ($headerColors[$type] ?? '4F81BD')]
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ]
        ]);

        // Set tinggi baris judul
        $sheet->getRowDimension(1)->setRowHeight(25);

        // Tambahkan baris kosong atau info tambahan (opsional)
        $sheet->mergeCells('A2:J2');
        $sheet->setCellValue('A2', 'Laporan Data Booking Job - Dicetak: ' . date('d/m/Y H:i:s'));
        $sheet->getStyle('A2')->applyFromArray([
            'font' => [
                'italic' => true,
                'size' => 9,
                'color' => ['argb' => 'FF666666']
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER
            ]
        ]);
        $sheet->getRowDimension(2)->setRowHeight(18);

        // ========== HEADER KOLOM TABEL (MULAI DARI BARIS 3) ==========

        $headers = [
            'No',
            'No Job',
            'No PIB/PEB/PO',
            'Importir/Exportir',
            'Party',
            'ETA/ETD',
            'POL/POD',
            'Pelayaran',
            'BL',
            'Master BL'
        ];

        $color = $headerColors[$type] ?? '4F81BD';

        // Buat header baris ke-3
        $headerRow = 3;  // ← UBAH dari baris 1 ke baris 3
        $col = 'A';
        foreach ($headers as $h) {
            $sheet->setCellValue($col . $headerRow, $h);
            $sheet->getStyle($col . $headerRow)->applyFromArray([
                'font' => [
                    'bold' => true,
                    'color' => ['argb' => 'FFFFFFFF']
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FF' . $color]
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['argb' => 'FF000000']
                    ]
                ]
            ]);
            $col++;
        }

        // ========== ISI DATA (MULAI DARI BARIS 4) ==========

        $rowNumber = 4;  // ← UBAH dari 2 ke 4
        $no = 1;
        foreach ($data as $row) {
            $sheet->setCellValue("A$rowNumber", $no++);
            $sheet->setCellValue("B$rowNumber", $row['no_job']);
            $sheet->setCellValue("C$rowNumber", $row['no_pib_po']);
            $sheet->setCellValue("D$rowNumber", $row['consignee']);
            $sheet->setCellValue("E$rowNumber", $row['party']);
            $sheet->setCellValue("F$rowNumber", $row['eta']);
            $sheet->setCellValue("G$rowNumber", $row['pol']);
            $sheet->setCellValue("H$rowNumber", $row['shipping_line']);
            $sheet->setCellValue("I$rowNumber", $row['bl']);
            $sheet->setCellValue("J$rowNumber", $row['master_bl']);

            $sheet->getStyle("A{$rowNumber}:J{$rowNumber}")->applyFromArray([
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['argb' => 'FF000000']
                    ]
                ]
            ]);

            $rowNumber++;
        }

        // Jika data kosong
        if (empty($data)) {
            $sheet->mergeCells('A4:J4');  // ← UBAH dari A2:J2 ke A4:J4
            $sheet->setCellValue('A4', 'Tidak ada data untuk jenis ini');

            $sheet->getStyle('A4')->applyFromArray([
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER
                ],
                'font' => [
                    'italic' => true,
                    'color' => ['argb' => 'FF888888']
                ]
            ]);
        }

        // Auto width semua kolom
        foreach (range('A', 'J') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
    }

    /**
     * ==============================================================
     * AMBIL DAFTAR TAHUN DARI DATA BOOKING JOB
     * ==============================================================
     */
    public function getYears()
    {
        $db = \Config\Database::connect();
        $query = $db->query("SELECT DISTINCT YEAR(created_at) AS year FROM booking_job ORDER BY year DESC");
        $years = array_column($query->getResultArray(), 'year');

        return $this->response->setJSON(['years' => $years]);
    }

    /**
     * ==============================================================
     * AMBIL DAFTAR BULAN BERDASARKAN TAHUN
     * ==============================================================
     */
    public function getMonths($year)
    {
        $db = \Config\Database::connect();
        $query = $db->query("SELECT DISTINCT MONTH(created_at) AS month FROM booking_job WHERE YEAR(created_at) = ? ORDER BY month ASC", [$year]);
        $months = $query->getResultArray();

        $bulanNames = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember'
        ];

        $result = [];
        foreach ($months as $m) {
            $num = (int)$m['month'];
            if ($num >= 1 && $num <= 12) {
                $result[] = ['month' => $num, 'name' => $bulanNames[$num]];
            }
        }

        return $this->response->setJSON(['months' => $result]);
    }

    /**
     * ==============================================================
     * EXPORT DATA BOOKING JOB KE PDF
     * ==============================================================
     */
    public function exportPdf($type = 'all')
    {
        $bookingModel = new BookingJobModel();

        // Ambil filter tahun dan bulan dari GET
        $year  = $this->request->getGet('year');
        $month = $this->request->getGet('month');

        // Jika $month dikirim sebagai array (misal multiple select)
        if (is_array($month)) $month = reset($month);

        $year  = is_numeric($year) ? (int)$year : null;
        $month = is_numeric($month) ? (int)$month : null;

        $filters = [
            'export'                 => 'List Job Export',
            'import_lcl'             => 'List Job Import LCL',
            'import_fcl_jaminan'     => 'List Job Import FCL Jaminan',
            'import_fcl_nonjaminan'  => 'List Job Import FCL Non-Jaminan',
            'lain'                   => 'List Job Import Lain-lain',
        ];

        $headerColors = [
            'export'                 => '#4F81BD',
            'import_lcl'             => '#9BBB59',
            'import_fcl_jaminan'     => '#F79646',
            'import_fcl_nonjaminan'  => '#C0504D',
            'lain'                   => '#8064A2',
        ];

        $exportTypes = ($type === 'all') ? array_keys($filters) : [$type];

        $html = "
            <style>
                table { border-collapse: collapse; width: 100%; margin-bottom: 20px; }
                th, td { border: 1px solid black; padding: 5px; text-align: center; font-size: 10pt; }
                thead { display: table-header-group; }
                thead th { padding: 10px; line-height: 1.6; font-size: 11pt; }
                tr { page-break-inside: avoid; }
            </style>
        ";

        foreach ($exportTypes as $t) {
            $query = $bookingModel->builder();
            if ($t !== 'all') $query->where('type', $t);
            if ($year) $query->where('YEAR(created_at)', $year, false);
            if ($month) $query->where('MONTH(created_at)', $month, false);
            $data = $query->get()->getResultArray();

            // Tentukan info periode untuk judul
            $periodInfo = '';
            if ($year && $month) {
                $periodInfo = " (" . str_pad($month, 2, '0', STR_PAD_LEFT) . "-$year)";
            } elseif ($year) {
                $periodInfo = " (Tahun $year)";
            }

            $title = $filters[$t] . $periodInfo;
            $color = $headerColors[$t] ?? '#4F81BD';

            $html .= "<h2 style='text-align:center; margin-bottom:10px;'>{$title}</h2>";
            $html .= "<table><thead><tr style='background-color:{$color}; color:white; font-weight:bold;'>";

            $headers = ['No', 'No Job', 'No PIB/PEB/PO', 'Importir/Exportir', 'Party', 'ETA/ETD', 'POL/POD', 'Pelayaran', 'BL', 'Master BL'];
            foreach ($headers as $h) {
                $html .= "<th>{$h}</th>";
            }
            $html .= "</tr></thead><tbody>";

            if (empty($data)) {
                $html .= "<tr><td colspan='10'>Tidak ada data</td></tr>";
            } else {
                $no = 1;
                foreach ($data as $row) {
                    $html .= "<tr>
                        <td>{$no}</td>
                        <td>" . htmlspecialchars($row['no_job']) . "</td>
                        <td>" . htmlspecialchars($row['no_pib_po']) . "</td>
                        <td>" . htmlspecialchars($row['consignee']) . "</td>
                        <td>" . htmlspecialchars($row['party']) . "</td>
                        <td>" . htmlspecialchars($row['eta']) . "</td>
                        <td>" . htmlspecialchars($row['pol']) . "</td>
                        <td>" . htmlspecialchars($row['shipping_line']) . "</td>
                        <td>" . htmlspecialchars($row['bl']) . "</td>
                        <td>" . htmlspecialchars($row['master_bl']) . "</td>
                    </tr>";
                    $no++;
                }
            }

            $html .= "</tbody></table>";

            // Page break antar tipe
            if ($t !== end($exportTypes)) {
                $html .= "<div style='page-break-after: always;'></div>";
            }
        }

        $options = new \Dompdf\Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);

        $dompdf = new \Dompdf\Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        $period = ($year ? "_{$year}" : '') . ($month ? '-' . str_pad($month, 2, '0', STR_PAD_LEFT) : '');
        $filename = 'Booking_' . $type . $period . '_' . date('Y-m-d_His') . '.pdf';
        $dompdf->stream($filename, ['Attachment' => true]);
        exit;
    }


    /**
     * ==============================================================
     * CETAK STICKY NOTE BOOKING JOB
     * ==============================================================
     * Fungsi untuk mencetak booking job dalam format note kecil
     * menggunakan Dompdf. Ukuran paper disesuaikan untuk sticky note.
     */
    public function printNote($encoded_id)
    {
        // Tambahkan padding = jika diperlukan untuk base64 decode
        $padding = strlen($encoded_id) % 4;
        if ($padding) {
            $encoded_id .= str_repeat('=', 4 - $padding);
        }

        // Decode dan ambil ID (bagian sebelum -)
        $decoded = base64_decode($encoded_id);
        $parts = explode('-', $decoded);
        $id = $parts[0] ?? null;

        // Validasi ID
        if (!$id || !is_numeric($id)) {
            return redirect()->back()->with('error', 'Invalid ID.');
        }

        $bookingModel = new BookingJobModel();
        $data = $bookingModel->find($id);

        if (!$data) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Data tidak ditemukan");
        }

        // HTML sticky note
        $html = "
        <style>
            @page { margin: 0cm; }
            body { margin: 0; padding: 6px; font-family: Arial, sans-serif; font-size: 11px; }
            table { width: 100%; font-size: 11px; color: #000; line-height: 1.2; border: 1px solid #000; border-collapse: collapse; }
            td { padding: 2px 4px; vertical-align: top; border: 1px solid #000; }
            .title { text-align: center; font-size: 13px; font-weight: bold; background-color: #ffff; }
            .label { font-weight: bold; width: 35%; }
        </style>
        <title>Print Note : {$data['no_job']}</title>
        <table>
            <tr><td colspan='2' class='title'>Booking Job</td></tr>
            <tr><td class='label'>No Job</td><td>: {$data['no_job']}</td></tr>
            <tr><td class='label'>No PIB/PEB/PO</td><td>: {$data['no_pib_po']}</td></tr>
            <tr><td class='label'>Importir/Exportir</td><td>: {$data['consignee']}</td></tr>
            <tr><td class='label'>ETA/ETD</td><td>: {$data['eta']}</td></tr>
        </table>
        ";

        // Setup Dompdf
        $options = new \Dompdf\Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);

        $dompdf = new \Dompdf\Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper([0, 0, 200, 85], 'portrait'); // ukuran sticky note
        $dompdf->render();

        $filename = 'Note_' . $data['no_job'] . '.pdf';
        $dompdf->stream($filename, ['Attachment' => false]);
        exit;
    }

    /**
     * ==============================================================
     * KIRIM DATA BOOKING JOB KE WORKSHEET
     * ==============================================================
     * Fungsi ini memindahkan data booking job ke worksheet
     * sesuai tipe (export/import) dan update status booking.
     */
    public function sendToWorksheet($id)
    {
        $booking = $this->bookingModel->find($id);

        if (!$booking) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Data Booking Job tidak ditemukan.'
            ])->setStatusCode(404);
        }

        try {
            // Tentukan model worksheet berdasarkan type booking
            if (isset($booking['type']) && strtolower($booking['type']) === 'export') {
                $wsModel = new \App\Models\WorksheetExport\WorkSheetExportModel();
            } else {
                $wsModel = new \App\Models\WorksheetImport\WorkSheetImportModel();
            }

            // Mapping data booking ke worksheet
            $worksheetData = $wsModel->mapFromBooking($booking);

            // Insert ke worksheet
            $insertId = $wsModel->insert($worksheetData, true);

            // Update status di booking_job
            $this->bookingModel->update($id, [
                'status'     => 'worksheet',
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            return $this->response->setJSON([
                'status'  => 'ok',
                'message' => 'Data berhasil dikirim ke Worksheet & status booking job diperbarui.'
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Gagal: ' . $e->getMessage()
            ])->setStatusCode(500);
        }
    }
}
