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
    // TAMPILKAN HALAMAN TAMBAH BOOKING JOB
    // ============================================================
    public function addPage()
    {
        return view('booking_job/add');
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



    // GET ALL CONSIGNEE FOR SELECT2
    public function searchConsignee()
    {
        $q = $this->request->getGet('term');

        $model = new MasterConsigneeModel();

        // Jika tidak mengetik -> tampilkan semua
        if ($q == "" || $q == null) {
            $data = $model
                ->select('id, nama_consignee')
                ->orderBy('nama_consignee', 'ASC')
                ->findAll();
        } else {
            // Jika mengetik -> filter
            $data = $model
                ->select('id, nama_consignee')
                ->like('nama_consignee', $q)
                ->orderBy('nama_consignee', 'ASC')
                ->findAll();
        }

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

        $model = new MasterPortModel();

        // Jika tidak mengetik → tampilkan semua
        if ($q == "" || $q == null) {
            $data = $model
                ->select('id, kode, nama_port')
                ->orderBy('nama_port', 'ASC')
                ->findAll();
        } else {
            // Jika mengetik → filter pencarian
            $data = $model
                ->select('id, kode, nama_port')
                ->groupStart()
                ->like('kode', $q)
                ->orLike('nama_port', $q)
                ->groupEnd()
                ->orderBy('nama_port', 'ASC')
                ->findAll();
        }

        $results = [];

        foreach ($data as $row) {
            $results[] = [
                'id'   => $row['id'],
                'text' => $row['nama_port'] // tampil nama saja
            ];
        }

        return $this->response->setJSON($results);
    }


    // SEARCH PELAYARAN (SELECT2)
    public function searchPelayaran()
    {
        $q = $this->request->getGet('term');

        $model = new MasterPelayaranModel();

        // Jika user tidak mengetik → tampilkan semua
        if ($q == "" || $q == null) {
            $data = $model
                ->select('id, kode, nama_pelayaran')
                ->orderBy('nama_pelayaran', 'ASC')
                ->findAll();
        } else {
            // Jika user mengetik → filter pencarian
            $data = $model
                ->select('id, kode, nama_pelayaran')
                ->groupStart()
                ->like('kode', $q)
                ->orLike('nama_pelayaran', $q)
                ->groupEnd()
                ->orderBy('nama_pelayaran', 'ASC')
                ->findAll();
        }

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

        // Jika validasi gagal → kembali ke add.php
        if (!$validation->withRequest($this->request)->run()) {

            return redirect()
                ->back()
                ->withInput()
                ->with('errors', $validation->getErrors());
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
            // Simpan
            $this->bookingModel->insert($data);
            addLog('Menambahkan Booking Job Baru No:"' . $data['no_job'] . '"');

            // Flashdata sukses → untuk SweetAlert di index
            session()->setFlashdata('success', 'Booking Job berhasil disimpan!');

            return redirect()->to('/booking-job');
        } catch (\Exception $e) {

            return redirect()
                ->back()
                ->withInput()
                ->with('errors', ['db' => 'Gagal menyimpan data ke database.']);
        }
    }


    // ============================================================
    // TAMPILKAN HALAMAN EDIT BOOKING JOB
    // ============================================================
    public function editPage($id)
    {
        $row = $this->bookingModel->find($id);

        if (!$row) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Booking Job tidak ditemukan!");
        }

        return view('booking_job/edit', [
            'booking' => $row
        ]);
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
            addLog("Menghapus Booking Job No: {$row['no_job']}");

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
    // UPDATE DATA BOOKING JOB
    // ============================================================
    public function update($id)
    {
        $validation = \Config\Services::validation();

        // Aturan validasi
        $rules = [
            'no_job'        => "required|is_unique[booking_job.no_job,id,{$id}]",
            'type'          => 'required',
            'consignee'     => 'required',
            'no_pib_po'     => 'required',
            'party'         => 'required',
            'eta'           => 'required',
            'pol'           => 'required',
            'shipping_line' => 'required',
            'bl'            => "required|is_unique[booking_job.bl,id,{$id}]",
            'master_bl'     => 'required',
        ];

        $messages = [
            'no_job' => [
                'required'  => 'Nomor job wajib diisi.',
                'is_unique' => 'Nomor job sudah digunakan oleh data lain.'
            ],
            'bl' => [
                'required'  => 'Nomor BL wajib diisi.',
                'is_unique' => 'Nomor BL sudah digunakan oleh data lain.'
            ],
            'type'          => ['required' => 'Jenis job wajib diisi.'],
            'consignee'     => ['required' => 'Consignee wajib diisi.'],
            'no_pib_po'     => ['required' => 'Nomor PIB/PO wajib diisi.'],
            'party'         => ['required' => 'Party wajib diisi.'],
            'eta'           => ['required' => 'ETA/ETD wajib diisi.'],
            'pol'           => ['required' => 'POL/POD wajib diisi.'],
            'shipping_line' => ['required' => 'Shipping line wajib diisi.'],
            'master_bl'     => ['required' => 'Master BL wajib diisi.'],
        ];

        $validation->setRules($rules, $messages);

        // Validasi gagal → redirect kembali ke halaman edit
        if (!$validation->withRequest($this->request)->run()) {

            return redirect()
                ->back()
                ->withInput()
                ->with('errors', $validation->getErrors());
        }

        // Data update
        $data = [
            'no_job'        => $this->request->getPost('no_job'),
            'type'          => $this->request->getPost('type'),
            'consignee'     => $this->request->getPost('consignee'), // nama, bukan ID
            'party'         => $this->request->getPost('party'),
            'eta'           => $this->request->getPost('eta'),
            'pol'           => $this->request->getPost('pol'),       // nama, bukan ID
            'no_pib_po'     => $this->request->getPost('no_pib_po'),
            'shipping_line' => $this->request->getPost('shipping_line'),
            'bl'            => $this->request->getPost('bl'),
            'master_bl'     => $this->request->getPost('master_bl'),
            'updated_at'    => date('Y-m-d H:i:s')
        ];

        try {

            $this->bookingModel->update($id, $data);
            addLog('Mengupdate Booking Job No:"' . $data['no_job'] . '"');

            // Gunakan flashdata untuk SweetAlert
            session()->setFlashdata('success', 'Booking Job berhasil diperbarui!');

            return redirect()->to('/booking-job');
        } catch (\Exception $e) {

            log_message('error', 'BookingJob::update error: ' . $e->getMessage());

            return redirect()
                ->back()
                ->withInput()
                ->with('errors', ['db' => 'Terjadi kesalahan saat update data.']);
        }
    }


    /**
     * ==============================================================
     * EXPORT DATA BOOKING JOB KE EXCEL (PER TANGGAL)
     * ==============================================================
     * Fungsi ini mengekspor data booking job ke format Excel (.xlsx)
     * dengan filter tanggal mulai dan tanggal selesai, serta 
     * pengelompokan jenis job. Struktur dan style sama persis versi
     * tahun/bulan—hanya logika filter yang berubah.
     * ==============================================================
     */
    public function exportExcelRange($type = 'all')
    {
        $bookingModel = new BookingJobModel();

        // Ambil filter tanggal dari parameter GET
        $start = $this->request->getGet('start_date');
        $end   = $this->request->getGet('end_date');

        // Validasi
        if (!$start || !$end) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Tanggal mulai dan tanggal selesai wajib diisi.'
            ]);
        }

        // Definisi label untuk tiap kategori
        $filters = [
            'export'                => 'Export',
            'import_lcl'            => 'Import LCL',
            'import_fcl_jaminan'    => 'FCL Jaminan',
            'import_fcl_nonjaminan' => 'FCL Non-Jmn',
            'lain'                  => 'Lain-lain',
            'all'                   => 'Semua Job'
        ];

        // Definisi warna header
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

        // Export semua atau salah satu
        $exportTypes = ($type == 'all') ? array_keys($filters) : [$type];

        foreach ($exportTypes as $t) {

            $query = $bookingModel->builder();

            if ($t != 'all') {
                $query->where('type', $t);
            }

            // Filter tanggal (periodik)
            $query->where("DATE(created_at) >=", $start);
            $query->where("DATE(created_at) <=", $end);

            $data = $query->get()->getResultArray();

            $title = substr($filters[$t], 0, 31);

            // Buat Sheet
            $this->createBookingSheet($spreadsheet, $sheetIndex, $t, $title, $data, $headerColors);
            $sheetIndex++;
        }

        // Set sheet pertama aktif
        $spreadsheet->setActiveSheetIndex(0);

        // Penamaan file
        $filename = 'Laporan_Booking_' . $type . '_' . $start . '_sd_' . $end . '.xlsx';

        // Header download
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');

        addLog("Export Booking Job Excel (Periode {$start} s/d {$end}): {$filename}");
        exit;
    }

    private function sanitizeSheetTitle($title)
    {
        // Karakter ilegal: : \ / ? * [ ]
        $illegal = ['\\', '/', '?', '*', ':', '[', ']'];

        // Hapus karakter ilegal
        $title = str_replace($illegal, '-', $title);

        // Maksimal 31 karakter
        if (strlen($title) > 31) {
            $title = substr($title, 0, 31);
        }

        return $title;
    }

    /**
     * ==============================================================
     * FUNGSI BANTU MEMBUAT SHEET BOOKING JOB
     * ==============================================================
     */
    private function createBookingSheet($spreadsheet, $sheetIndex, $type, $title, $data, $headerColors)
    {
        // Ambil filter tanggal dari parameter GET
        $start = $this->request->getGet('start_date');
        $end   = $this->request->getGet('end_date');
        $period = " {$start} s/d {$end}";
        $sheet = $spreadsheet->createSheet($sheetIndex);

        $cleanTitle = $this->sanitizeSheetTitle($title);
        $sheet->setTitle($cleanTitle);

        // ==============================================================
        // JUDUL UTAMA
        // ==============================================================
        $sheet->mergeCells('A1:J1');
        $sheet->setCellValue('A1', strtoupper('LAPORAN BOOKING JOB ' . $title));

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
        $sheet->getRowDimension(1)->setRowHeight(25);

        // ==============================================================
        // PERIODE LAPORAN (Baris 2)
        // ==============================================================
        $sheet->mergeCells('A2:J2');
        $sheet->setCellValue('A2', strtoupper('PERIODE DATA ' . $period));

        $sheet->getStyle('A2')->applyFromArray([
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
        $sheet->getRowDimension(1)->setRowHeight(25);

        // ==============================================================
        // TANGGAL CETAK (Baris 3)
        // ==============================================================
        $sheet->mergeCells('A3:J3');
        $sheet->setCellValue('A3', 'Dicetak: ' . date('d/m/Y H:i:s'));

        $sheet->getStyle('A3')->applyFromArray([
            'font' => [
                'italic' => true,
                'size' => 9,
                'color' => ['argb' => 'FF666666']
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER
            ]
        ]);
        $sheet->getRowDimension(3)->setRowHeight(18);

        // ==============================================================
        // HEADER TABLE (Mulai baris 4)
        // ==============================================================
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

        $headerRow = 4;
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

        // ==============================================================
        // ISI DATA (Mulai baris 5)
        // ==============================================================
        $rowNumber = 5;
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

        // ==============================================================
        // Jika tidak ada data
        // ==============================================================
        if (empty($data)) {
            $sheet->mergeCells('A5:J5');
            $sheet->setCellValue('A5', 'Tidak ada data untuk periode ini');

            $sheet->getStyle('A5')->applyFromArray([
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                'font' => [
                    'italic' => true,
                    'color' => ['argb' => 'FF888888']
                ]
            ]);
        }

        // Auto width
        foreach (range('A', 'J') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
    }



    /**
     * ==============================================================
     * EXPORT DATA BOOKING JOB KE PDF (PER TANGGAL)
     * ==============================================================
     */
    public function exportPdfRange($type = 'all')
    {
        $bookingModel = new BookingJobModel();

        // Ambil tanggal dari GET
        $start = $this->request->getGet('start_date');
        $end   = $this->request->getGet('end_date');

        if (!$start || !$end) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Tanggal mulai dan tanggal selesai diperlukan.'
            ]);
        }

        $filters = [
            'export'                 => 'EXPORT',
            'import_lcl'             => 'IMPORT LCL',
            'import_fcl_jaminan'     => 'IMPORT FCL JAMINAN',
            'import_fcl_nonjaminan'  => 'IMPORT FCL NON-JAMINAN',
            'lain'                   => 'IMPORT LAIN-LAIN',
            'all'                    => 'SEMUA JOB'
        ];

        $headerColors = [
            'export'                 => '#4F81BD',
            'import_lcl'             => '#9BBB59',
            'import_fcl_jaminan'     => '#F79646',
            'import_fcl_nonjaminan'  => '#C0504D',
            'lain'                   => '#8064A2',
            'all'                    => '#31859B'
        ];

        $exportTypes = ($type === 'all') ? array_keys($filters) : [$type];

        $html = "
        <style>
            table { border-collapse: collapse; width: 100%; margin-bottom: 20px; }
            th, td { border: 1px solid black; padding: 5px; text-align: center; font-size: 10pt; }
            thead { display: table-header-group; }
            tr { page-break-inside: avoid; }
        </style>
    ";

        foreach ($exportTypes as $t) {

            $query = $bookingModel->builder();
            if ($t !== 'all') $query->where('type', $t);

            $query->where("DATE(created_at) >=", $start);
            $query->where("DATE(created_at) <=", $end);

            $data = $query->get()->getResultArray();

            $title =  "LAPORAN BOOKING JOB";
            $title2 = $filters[$t];
            $title3 = "PERIODE ({$start} s/d {$end})";
            $color = $headerColors[$t] ?? '#4F81BD';

            // Judul (lebih rapat dan lebih kecil)
            $html .= "<h2 style='text-align:center; margin:0; padding:0; font-size:25px;'>{$title}</h2>";
            $html .= "<h3 style='text-align:center; margin:0; padding:0; font-size:20px;'>{$title2}</h3>";
            $html .= "<h4 style='text-align:center; margin:0 0 20px 0; padding:0; font-size:16px;'>{$title3}</h4>";

            // Header
            $html .= "<table><thead><tr style='background-color:{$color}; color:white; font-weight:bold;'>";
            $headers = ['No', 'No Job', 'No PIB/PEB/PO', 'Importir/Exportir', 'Party', 'ETA/ETD', 'POL/POD', 'Pelayaran', 'BL', 'Master BL'];

            foreach ($headers as $h) $html .= "<th>{$h}</th>";
            $html .= "</tr></thead><tbody>";

            // Data
            if (empty($data)) {
                $html .= "<tr><td colspan='10'>Tidak ada data</td></tr>";
            } else {
                $no = 1;
                foreach ($data as $row) {
                    $html .= "<tr>
                    <td>{$no}</td>
                    <td>{$row['no_job']}</td>
                    <td>{$row['no_pib_po']}</td>
                    <td>{$row['consignee']}</td>
                    <td>{$row['party']}</td>
                    <td>{$row['eta']}</td>
                    <td>{$row['pol']}</td>
                    <td>{$row['shipping_line']}</td>
                    <td>{$row['bl']}</td>
                    <td>{$row['master_bl']}</td>
                </tr>";
                    $no++;
                }
            }

            $html .= "</tbody></table>";

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

        $filename = 'Laporan_Booking_' . $type . "_{$start}_sd_{$end}" . '.pdf';
        $dompdf->stream($filename, ['Attachment' => true]);

        addLog("Export Booking Job PDF (Periode {$start} s/d {$end}): {$filename}");
        exit;
    }



    public function printNote($encoded_id)
    {
        // --- Perbaikan padding base64 ---
        $padding = strlen($encoded_id) % 4;
        if ($padding) {
            $encoded_id .= str_repeat('=', 4 - $padding);
        }

        // --- Decode ---
        $decoded = base64_decode($encoded_id);
        $parts   = explode('-', $decoded);
        $id      = $parts[0] ?? null;

        if (!$id || !is_numeric($id)) {
            return redirect()->back()->with('error', 'Invalid ID.');
        }

        $booking = $this->bookingModel->find($id);

        if (!$booking) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Data tidak ditemukan");
        }

        // --- User & Tanggal Cetak ---
        $user      = user()->username ?? 'Unknown';
        date_default_timezone_set('Asia/Jakarta');
        $printDate = date('d-m-Y H:i');


        // Format tanggal ETA
        $eta    = date('d-m-Y', strtotime($booking['eta']));
        $status = strtoupper($booking['status']);

        // --- HTML Sticky Note ---
        $html = "
        <style>
            @page { margin: 0cm; }

            body {
                margin: 0;
                padding: 8px;
                font-family: Arial, sans-serif;
                font-size: 11px;
                color: #000;
            }

            table {
                width: 100%;
                border-collapse: collapse;
            }

            td {
                padding: 2px 4px;
                font-size: 11px;
                vertical-align: top;
            }

            .title {
                text-align: center;
                font-size: 14px;
                font-weight: bold;
                padding: 4px 0;
                margin-bottom: 5px;
            }

            .sub-address {
                text-align: center;
                font-size: 9px;
                border-bottom: 1px dashed #000;
                margin-top: -4px;
                margin-bottom: 6px;
            }

            .status-box {
                text-align: center;
                font-size: 12px;
                font-weight: bold;
                background: #f1f1f1;
                padding: 3px 0;
                border: 1px solid #000;
                margin-bottom: 4px;
            }

            .label { width: 38%; font-weight: bold; }
            .value { width: 62%; }

            .line {
                border-bottom: 1px solid #000;
                margin: 3px 0;
            }

            .footer {
                font-size: 9px;
                text-align: left;
                margin-top: 25px;
                border-top: 1px dashed #000;
                padding-top: 3px;
            }
        </style>

        <title>Print Note : {$booking['no_job']}</title>

        <div class='title'>PT TRUSTWAY TRANSINDO</div>
        <div class='sub-address'>
            The Central 88, Jl. Trembesi Blok F No. 808,<br>
            Pademangan Timur, Kemayoran, Jakarta Utara 14410
        </div>
        <br>

        <div class='title'>BOOKING JOB NOTE</div>
        <div class='status-box'>STATUS: {$status}</div>

        <table>
            <tr>
                <td class='label'>No Job</td>
                <td class='value'>: {$booking['no_job']}</td>
            </tr>
            <tr>
                <td class='label'>Consignee</td>
                <td class='value'>: {$booking['consignee']}</td>
            </tr>

            <tr><td colspan='2' class='line'></td></tr>

            <tr>
                <td class='label'>No PIB/PEB/PO</td>
                <td class='value'>: {$booking['no_pib_po']}</td>
            </tr>
            <tr>
                <td class='label'>Party</td>
                <td class='value'>: {$booking['party']}</td>
            </tr>

            <tr><td colspan='2' class='line'></td></tr>

            <tr>
                <td class='label'>ETA / ETD</td>
                <td class='value'>: {$eta}</td>
            </tr>
            <tr>
                <td class='label'>POL / POD</td>
                <td class='value'>: {$booking['pol']}</td>
            </tr>
            <tr>
                <td class='label'>Shipping Line</td>
                <td class='value'>: {$booking['shipping_line']}</td>
            </tr>

            <tr><td colspan='2' class='line'></td></tr>

            <tr>
                <td class='label'>BL</td>
                <td class='value'>: {$booking['bl']}</td>
            </tr>
            <tr>
                <td class='label'>Master BL</td>
                <td class='value'>: {$booking['master_bl']}</td>
            </tr>
        </table>

        <div class='footer'>
            Dicetak oleh: <b>{$user}</b><br>
            Tgl Cetak: {$printDate}
        </div>
        ";

        // --- DOMPDF ---
        $options = new \Dompdf\Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);

        $dompdf = new \Dompdf\Dompdf($options);
        $dompdf->loadHtml($html);

        // Sticky note ukuran square (lebih presisi)
        $dompdf->setPaper([0, 0, 250, 290], 'portrait');

        $dompdf->render();

        $filename = 'Note_' . $booking['no_job'] . '.pdf';
        addLog("Mencetak Booking Job Note No: {$booking['no_job']}");
        return $dompdf->stream($filename, ["Attachment" => false]);
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

            addLog("Mengirim Booking Job No: {$booking['no_job']} ke Worksheet");
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
