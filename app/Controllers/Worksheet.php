<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\WorkSheetImportModel;
use App\Models\WorkSheetExportModel;
use App\Models\WorksheetContainerModel;
use App\Models\WorksheetTruckingModel;
use App\Models\WorksheetDoModel;
use App\Models\WorksheetLartasModel;
use App\Models\WorksheetInformasiTambahanModel;

class Worksheet extends BaseController
{
    protected $importModel;
    protected $exportModel;
    protected $containerModel;
    protected $truckingModel;
    protected $doModel;
    protected $lartasModel;
    protected $informasiTambahanModel;

    public function __construct()
    {
        $this->importModel              = new WorkSheetImportModel();
        $this->exportModel              = new WorkSheetExportModel();
        $this->containerModel           = new WorksheetContainerModel();
        $this->truckingModel            = new WorksheetTruckingModel();
        $this->doModel                  = new WorksheetDoModel();
        $this->lartasModel              = new WorksheetLartasModel();
        $this->informasiTambahanModel   = new WorksheetInformasiTambahanModel();
    }

    /**
     * ==========================
     * INDEX WORKSHEET
     * ==========================
     */
    public function index()
    {
        // satu view saja untuk import/export
        return view('worksheet/index');
    }

    /**
     * ==========================
     * TABLE WORKSHEET
     * ==========================
     */
    public function list()
    {
        $type = $this->request->getGet('type') ?? 'import';

        if ($type === 'export') {
            $rows = $this->exportModel->orderBy('no_ws', 'ASC')->findAll();
            $data = [];

            foreach ($rows as $r) {
                $data[] = [
                    'id'            => $r['id'],
                    'no_ws'         => $r['no_ws'],
                    'no_aju'        => $r['no_aju'],
                    'shipper'       => $r['shipper'],
                    'party'         => $r['party'],
                    'etd'           => $r['etd'],
                    'pod'           => $r['pod'],
                    'bl'            => $r['bl'],
                    'master_bl'     => $r['master_bl'],
                    'shipping_line' => $r['shipping_line'],
                    'status'        => $r['status'] ?? 'not completed',
                ];
            }

            return $this->response->setJSON(['data' => $data]);
        }

        // default import
        $rows = $this->importModel->orderBy('no_ws', 'ASC')->findAll();
        $data = [];

        foreach ($rows as $r) {
            $data[] = [
                'id'            => $r['id'],
                'no_ws'         => $r['no_ws'],
                'no_aju'        => $r['no_aju'],
                'consignee'     => $r['consignee'],
                'party'         => $r['party'],
                'eta'           => $r['eta'],
                'pol'           => $r['pol'],
                'bl'            => $r['bl'],
                'master_bl'     => $r['master_bl'],
                'shipping_line' => $r['shipping_line'],
                'status'        => $r['status'] ?? 'not completed',
            ];
        }

        return $this->response->setJSON(['data' => $data]);
    }

    /**
     * ==========================
     * REDIRECT WORKSHEET
     * ==========================
     */
    public function redirectToBooking()
    {
        return redirect()->to('/booking-job')->with('autoAdd', true);
    }

    /**
     * ==========================
     * EDIT WORKSHEET IMPORT
     * ==========================
     */
    public function editImport($id)
    {
        $worksheet = $this->importModel->find($id);

        if (!$worksheet) {
            return redirect()->back()->with('error', 'Data worksheet import tidak ditemukan.');
        }

        // Ambil data kontainer & trucking (jika ada)
        $containers         = $this->containerModel->where('id_ws', $id)->findAll();
        $truckings          = $this->truckingModel->where('id_ws', $id)->findAll();
        $dos                = $this->doModel ->where('id_ws', $id)->findAll();
        $lartass            = $this->lartasModel ->where('id_ws', $id)->findAll();
        $informasitambahans = $this->informasiTambahanModel ->where('id_ws', $id)->findAll();
        

        return view('worksheet/edit_import', [
            'worksheet'  => $worksheet,
            'containers' => $containers,
            'truckings'  => $truckings,
            'dos'        => $dos,
            'lartass'    => $lartass,
            'informasitambahans'  => $informasitambahans
        ]);
    }

    /**
     * ==========================
     * UPDATE WORKSHEET IMPORT (FINAL)
     * ==========================
     */
    public function updateImport($id)
    {
        $data = [
            'no_ws'             => $this->request->getPost('no_ws'),
            'pengurusan_pib'    => $this->request->getPost('pengurusan_pib'),
            'no_aju'            => $this->request->getPost('no_aju'),
            'tgl_aju'           => $this->request->getPost('tgl_aju'),
            'no_po'             => $this->request->getPost('no_po'),
            'io_number'         => $this->request->getPost('io_number'),
            'pib_nopen'         => $this->request->getPost('pib_nopen'),
            'tgl_nopen'         => $this->request->getPost('tgl_nopen'),
            'tgl_sppb'          => $this->request->getPost('tgl_sppb'),
            'shipper'           => $this->request->getPost('shipper'),
            'consignee'         => $this->request->getPost('consignee'),
            'notify_party'      => $this->request->getPost('notify_party'),
            'vessel'            => $this->request->getPost('vessel'),
            'no_voyage'         => $this->request->getPost('no_voyage'),
            'pol'               => $this->request->getPost('pol'),
            'terminal'          => $this->request->getPost('terminal'),
            'shipping_line'     => $this->request->getPost('shipping_line'),
            'commodity'         => $this->request->getPost('commodity'),
            'party'             => $this->request->getPost('party'),
            'jenis_con'         => $this->request->getPost('jenis_con'),
            'jenis_trucking'    => $this->request->getPost('jenis_trucking'),
            'qty'               => $this->request->getPost('qty'),
            'kemasan'           => $this->request->getPost('kemasan'),
            'net'               => $this->request->getPost('net'),
            'gross'             => $this->request->getPost('gross'),
            'bl'                => $this->request->getPost('bl'),
            'tgl_bl'            => $this->request->getPost('tgl_bl'),
            'master_bl'         => $this->request->getPost('master_bl'),
            'tgl_master'        => $this->request->getPost('tgl_master'),
            'no_invoice'        => $this->request->getPost('no_invoice'),
            'tgl_invoice'       => $this->request->getPost('tgl_invoice'),
            'eta'               => $this->request->getPost('eta'),
            'pengurusan_do'     => $this->request->getPost('pengurusan_do'),
            'pengurusan_lartas' => $this->request->getPost('pengurusan_lartas'),
            'jenis_tambahan'    => $this->request->getPost('jenis_tambahan'),
            'asuransi'          => $this->request->getPost('asuransi'),
            'top'               => $this->request->getPost('top'),
            'berita_acara'      => $this->request->getPost('berita_acara'),
            'updated_at'        => date('Y-m-d H:i:s')
        ];

        // Hilangkan key yang nilainya null
        $data = array_filter($data, fn($v) => $v !== null);

        // Ambil data lama dan merge untuk pengecekan kelengkapan
        $existing = $this->importModel->find($id);
        $merged   = array_merge($existing ?? [], $data);

        // Field wajib untuk status "completed"
        $requiredFields = [
            'no_ws','pengurusan_pib','no_aju','tgl_aju','no_po','pib_nopen','tgl_nopen',
            'tgl_sppb','shipper','consignee','notify_party','vessel',
            'no_voyage','pol','terminal','shipping_line','commodity',
            'party','qty','kemasan','net','gross','bl','tgl_bl',
            'master_bl','tgl_master','no_invoice','tgl_invoice','eta',
            'pengurusan_do','asuransi','top','berita_acara', 'pengurusan_lartas',
            'penjaluran','jenis_tambahan'
        ];

        $allFilled = true;
        foreach ($requiredFields as $field) {
            if (empty($merged[$field])) {
                $allFilled = false;
                break;
            }
        }

        // ==========================
        // SIMPAN DATA PENJALURAN PIB
        // ==========================
        $penjaluran = $this->request->getPost('penjaluran');
        $tglSpjm    = $this->request->getPost('tgl_spjm');

        // Jika SPPB → kosongkan tanggal SPJM
        if ($penjaluran === 'SPPB') {
            $tglSpjm = null;
        }

        // Update ke worksheet_import
        $this->importModel->update($id, [
            'penjaluran' => $penjaluran,
            'tgl_spjm'   => $tglSpjm,
        ]);

        /**
         * ==========================
         * SIMPAN DATA CONTAINER
         * ==========================
         */
        $jenisCon    = $this->request->getPost('jenis_con');
        $noContainer = $this->request->getPost('no_container');
        $ukuran      = $this->request->getPost('ukuran');
        $tipe        = $this->request->getPost('tipe');

        // Hapus data container lama agar tidak duplikat
        $this->containerModel->where('id_ws', $id)->delete();

        // Jika FCL → wajib simpan container
        if ($jenisCon === 'FCL' && !empty($noContainer)) {
            $hasContainer = false;

            foreach ($noContainer as $i => $no) {
                if (!empty(trim($no))) {
                    $this->containerModel->insert([
                        'id_ws'        => $id,
                        'no_container' => trim($no),
                        'ukuran'       => isset($ukuran[$i]) ? trim($ukuran[$i]) : null,
                        'tipe'         => isset($tipe[$i]) ? trim($tipe[$i]) : null,
                        'created_at'   => date('Y-m-d H:i:s')
                    ]);
                    $hasContainer = true;
                }
            }

            if (!$hasContainer) {
                $allFilled = false; // Tidak ada container valid diinput
            }
        } elseif ($jenisCon === 'LCL') {
            // Jika LCL hapus data container lama (jika ada)
            $this->containerModel->where('id_ws', $id)->delete();
        }

        /**
         * ==========================
         * SIMPAN DATA TRUCKING
         * ==========================
         */
        $jenisTrucking = $this->request->getPost('jenis_trucking');
        $noMobil       = $this->request->getPost('no_mobil');
        $tipeMobil     = $this->request->getPost('tipe_mobil');
        $namaSupir     = $this->request->getPost('nama_supir');
        $alamat        = $this->request->getPost('alamat');
        $telpSupir     = $this->request->getPost('telp_supir');

        // Hapus data trucking lama
        $this->truckingModel->where('id_ws', $id)->delete();

        if ($jenisTrucking === 'Pengurusan Trucking' && !empty($noMobil)) {
            $hasTrucking = false;
            foreach ($noMobil as $i => $nopol) {
                if (!empty(trim($nopol))) {
                    $this->truckingModel->insert([
                        'id_ws'      => $id,
                        'no_mobil'   => trim($nopol),
                        'tipe_mobil' => $tipeMobil[$i] ?? null,
                        'nama_supir' => $namaSupir[$i] ?? null,
                        'alamat'     => $alamat[$i] ?? null,
                        'telp_supir' => $telpSupir[$i] ?? null,
                        'created_at' => date('Y-m-d H:i:s')
                    ]);
                    $hasTrucking = true;
                }
            }

            if (!$hasTrucking) {
                $allFilled = false;
            }
        } elseif ($jenisTrucking === 'Trucking Sendiri') {
            // jika trucking sendiri, pastikan tidak ada record trucking
            $this->truckingModel->where('id_ws', $id)->delete();
        }

        /**
         * ==========================
         * SIMPAN DATA DO
         * ==========================
         */
        $pengurusanDo = $this->request->getPost('pengurusan_do');
        $tipeDo        = $this->request->getPost('tipe_do');
        $pengambilDo   = $this->request->getPost('pengambil_do');
        $tglMatiDo     = $this->request->getPost('tgl_mati_do');

        // Hapus data DO lama agar tidak duplikat
        $this->doModel->where('id_ws', $id)->delete();

        // Jika "Pengurusan DO" → wajib simpan data DO
        if ($pengurusanDo === 'Pengambilan Delivery Order' && !empty($tipeDo)) {
            $hasDo = false;

            foreach ($tipeDo as $i => $tipe) {
                if (!empty(trim($tipe)) || !empty(trim($pengambilDo[$i])) || !empty(trim($tglMatiDo[$i]))) {
                    $this->doModel->insert([
                        'id_ws'        => $id,
                        'tipe_do'      => isset($tipeDo[$i]) ? trim($tipeDo[$i]) : null,
                        'pengambil_do' => isset($pengambilDo[$i]) ? trim($pengambilDo[$i]) : null,
                        'tgl_mati_do'  => isset($tglMatiDo[$i]) ? trim($tglMatiDo[$i]) : null,
                        'created_at'   => date('Y-m-d H:i:s')
                    ]);
                    $hasDo = true;
                }
            }

            if (!$hasDo) {
                $allFilled = false; // Tidak ada data DO valid diinput
            }
        } elseif ($pengurusanDo === 'Delivery Order Sendiri') {
            // Jika DO Sendiri → hapus semua data DO lama
            $this->doModel->where('id_ws', $id)->delete();
        }

        /**
         * ==========================
         * SIMPAN DATA LARTAS
         * ==========================
         */
        $pengurusanLartas = $this->request->getPost('pengurusan_lartas');
        $namaLartas       = $this->request->getPost('nama_lartas');
        $noLartas         = $this->request->getPost('no_lartas');
        $tglLartas        = $this->request->getPost('tgl_lartas');

        // Hapus data lama agar tidak duplikat
        $this->lartasModel->where('id_ws', $id)->delete();

        if ($pengurusanLartas === 'Pembuatan Lartas' && !empty($namaLartas)) {
            $hasLartas = false;

            foreach ($namaLartas as $i => $nama) {
                if (
                    !empty(trim($nama)) ||
                    !empty(trim($noLartas[$i] ?? '')) ||
                    !empty(trim($tglLartas[$i] ?? ''))
                ) {
                    $this->lartasModel->insert([
                        'id_ws'        => $id,
                        'nama_lartas'  => isset($namaLartas[$i]) ? trim($namaLartas[$i]) : null,
                        'no_lartas'    => isset($noLartas[$i]) ? trim($noLartas[$i]) : null,
                        'tgl_lartas'   => isset($tglLartas[$i]) ? trim($tglLartas[$i]) : null,
                        'created_at'   => date('Y-m-d H:i:s')
                    ]);
                    $hasLartas = true;
                }
            }

            if (!$hasLartas) {
                $allFilled = false; // Tidak ada data Lartas valid yang diinput
            }

        } elseif ($pengurusanLartas === 'Lartas Sendiri') {
            // Jika Lartas sendiri → hapus semua data lama
            $this->lartasModel->where('id_ws', $id)->delete();
        }

        /**
         * ==========================
         * SIMPAN DATA INFORMASI TAMBAHAN
         * ==========================
         */
        $jenisTambahan   = $this->request->getPost('jenis_tambahan');
        $namaPengurusan  = $this->request->getPost('nama_pengurusan');
        $tglPengurusan   = $this->request->getPost('tgl_pengurusan');

        // Hapus data lama agar tidak duplikat
        $this->informasiTambahanModel->where('id_ws', $id)->delete();

        if ($jenisTambahan === 'Pengurusan Tambahan' && !empty($namaPengurusan)) {
            $hasTambahan = false;

            foreach ($namaPengurusan as $i => $nama) {
                if (
                    !empty(trim($nama)) ||
                    !empty(trim($tglPengurusan[$i] ?? ''))
                ) {
                    $this->informasiTambahanModel->insert([
                        'id_ws'           => $id,
                        'nama_pengurusan' => isset($namaPengurusan[$i]) ? trim($namaPengurusan[$i]) : null,
                        'tgl_pengurusan'  => isset($tglPengurusan[$i]) ? trim($tglPengurusan[$i]) : null,
                        'created_at'      => date('Y-m-d H:i:s')
                    ]);
                    $hasTambahan = true;
                }
            }

            if (!$hasTambahan) {
                $allFilled = false; // Tidak ada data Informasi Tambahan valid diinput
            }

        } elseif ($jenisTambahan === 'Tidak Ada Tambahan') {
            // Jika Tidak Ada Tambahan → hapus semua data lama (jika ada)
            $this->informasiTambahanModel->where('id_ws', $id)->delete();
        }



        // Set status akhir setelah semua cek
        $data['status'] = $allFilled ? 'completed' : 'not completed';

        // Update data worksheet utama
        $this->importModel->update($id, $data);

        session()->setFlashdata('success', 'Data worksheet import berhasil diperbarui.');
        return redirect()->to('/worksheet?type=import');
    }

    /**
     * ==========================
     * CEK KELENGKAPAN IMPORT
     * ==========================
     */
    public function checkImport($id)
    {
        $data = $this->importModel->find($id);

        if (!$data) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Data worksheet tidak ditemukan.'
            ]);
        }

        $requiredFields = [
            'no_ws'           => 'Nomor Worksheet',
            'pengurusan_pib'  => 'Pengurusan PIB',
            'no_aju'          => 'Nomor AJU',
            'tgl_aju'         => 'Tanggal AJU',
            'no_po'           => 'Nomor PO',
            'pib_nopen'       => 'Nomor PIB / Nopen',
            'tgl_nopen'       => 'Tanggal Nopen',
            'tgl_sppb'        => 'Tanggal SPPB',
            'penjaluran'      => 'Penjaluran',
            'shipper'         => 'Shipper',
            'consignee'       => 'Consignee',
            'notify_party'    => 'Notify Party',
            'vessel'          => 'Vessel',
            'no_voyage'       => 'Nomor Voyage',
            'pol'             => 'Port of Loading (POL)',
            'terminal'        => 'Terminal',
            'shipping_line'   => 'Shipping Line',
            'commodity'       => 'Commodity',
            'party'           => 'Party',
            'qty'             => 'Quantity',
            'kemasan'         => 'Kemasan',
            'net'             => 'Net Weight',
            'gross'           => 'Gross Weight',
            'bl'              => 'Bill of Lading (BL)',
            'tgl_bl'          => 'Tanggal BL',
            'master_bl'       => 'Master BL',
            'tgl_master'      => 'Tanggal Master BL',
            'no_invoice'      => 'Nomor Invoice',
            'tgl_invoice'     => 'Tanggal Invoice',
            'eta'             => 'ETA',
            'pengurusan_do'   => 'Pengurusan Delivery Order',
            'jenis_con'       => 'Jenis Container',
            'jenis_trucking'  => 'Pengurusan Trucking',
            'pengurusan_lartas' => 'Pengurusan Lartas',
            'jenis_tambahan'    => 'Jenis Tambahan',
            'asuransi'        => 'Asuransi',
            'top'             => 'TOP',
            'berita_acara'    => 'Berita Acara'
        ];

        $incomplete = [];
        foreach ($requiredFields as $field => $label) {
            $value = $data[$field] ?? null;
        
            if (
                empty($value) ||
                $value === '0000-00-00' ||
                $value === '1970-01-01'
            ) {
                $incomplete[] = [
                    'name'  => $field,
                    'label' => $label
                ];
            }
        }

        // ==============================
        // Cek data SPJM jika penjaluran SPJM
        // ==============================
        if (($data['penjaluran'] ?? '') === 'SPJM') {
            if (empty($data['tgl_spjm']) || $data['tgl_spjm'] === '0000-00-00') {
                $incomplete[] = [
                    'name'  => 'tgl_spjm',
                    'label' => 'Tanggal SPJM wajib diisi karena penjaluran = SPJM'
                ];
            }
        }


        // ==============================
        // Cek container jika FCL
        // ==============================
        if (($data['jenis_con'] ?? '') === 'FCL') {
            $containers = $this->containerModel->where('id_ws', $id)->findAll();

            if (empty($containers)) {
                $incomplete[] = [
                    'name'  => 'container',
                    'label' => 'Data Kontainer wajib diisi untuk FCL'
                ];
            } else {
                // Cek apakah ada kolom penting yang kosong
                foreach ($containers as $c) {
                    if (
                        empty($c['no_container']) ||
                        empty($c['ukuran']) ||
                        empty($c['tipe']) 
                    ) {
                        $incomplete[] = [
                            'name'  => 'container_detail',
                            'label' => 'Beberapa kolom Container (No Container, Ukuran, Tipe) belum lengkap'
                        ];
                        break; // cukup satu kali error
                    }
                }
            }
        }

        // ==============================
        // Cek trucking jika Pengurusan Trucking
        // ==============================
        if (($data['jenis_trucking'] ?? '') === 'Pengurusan Trucking') {
            $truckings = $this->truckingModel->where('id_ws', $id)->findAll();

            if (empty($truckings)) {
                $incomplete[] = [
                    'name'  => 'trucking',
                    'label' => 'Data Trucking wajib diisi untuk Pengurusan Trucking'
                ];
            } else {
                foreach ($truckings as $t) {
                    if (
                        empty($t['no_mobil']) ||
                        empty($t['tipe_mobil']) ||
                        empty($t['nama_supir']) ||
                        empty($t['alamat']) ||
                        empty($t['telp_supir'])
                    ) {
                        $incomplete[] = [
                            'name'  => 'trucking_detail',
                            'label' => 'Beberapa kolom Trucking (No Mobil, Tipe Mobil, Nama Supir, Alamat Pengiriman, atau Telp Supir) belum lengkap'
                        ];
                        break;
                    }
                }
            }
        }

        // ==============================
        // Cek data DO jika Pengurusan DO
        // ==============================
        if (($data['pengurusan_do'] ?? '') === 'Pengambilan DO') {
            $dos = $this->doModel->where('id_ws', $id)->findAll();

            if (empty($dos)) {
                $incomplete[] = [
                    'name'  => 'do',
                    'label' => 'Data DO wajib diisi untuk Pengambilan DO'
                ];
            } else {
                foreach ($dos as $d) {
                    // Cek semua kolom DO wajib diisi
                    if (
                        empty(trim($d['tipe_do'] ?? '')) ||
                        empty(trim($d['pengambil_do'] ?? '')) ||
                        empty(trim($d['tgl_mati_do'] ?? '')) ||
                        $d['tgl_mati_do'] === '0000-00-00'
                    ) {
                        $incomplete[] = [
                            'name'  => 'do_detail',
                            'label' => 'Beberapa kolom DO (Tipe DO, Pengambil DO, atau Tanggal Mati DO) belum lengkap'
                        ];
                        break;
                    }
                }
            }
        } else {
            // Jika DO Sendiri → hapus data DO lama (jika ada)
            $this->doModel->where('id_ws', $id)->delete();
        }

        // ==============================
        // Cek data Lartas jika Pembuatan Lartas
        // ==============================
        if (($data['pengurusan_lartas'] ?? '') === 'Pembuatan Lartas') {
            $lartas = $this->lartasModel->where('id_ws', $id)->findAll();

            if (empty($lartas)) {
                $incomplete[] = [
                    'name'  => 'lartas',
                    'label' => 'Data Lartas wajib diisi untuk Pembuatan Lartas'
                ];
            } else {
                foreach ($lartas as $l) {
                    // Cek semua kolom Lartas wajib diisi
                    if (
                        empty(trim($l['nama_lartas'] ?? '')) ||
                        empty(trim($l['no_lartas'] ?? '')) ||
                        empty(trim($l['tgl_lartas'] ?? '')) ||
                        $l['tgl_lartas'] === '0000-00-00'
                    ) {
                        $incomplete[] = [
                            'name'  => 'lartas_detail',
                            'label' => 'Beberapa kolom Lartas (Nama Lartas, No Lartas, atau Tanggal Lartas) belum lengkap'
                        ];
                        break;
                    }
                }
            }
        } else {
            // Jika Lartas Sendiri → hapus data lama (jika ada)
            $this->lartasModel->where('id_ws', $id)->delete();
        }

        // ==============================
        // Cek data Informasi Tambahan jika Pengurusan Tambahan
        // ==============================
        if (($data['jenis_tambahan'] ?? '') === 'Pengurusan Tambahan') {
            $tambahans = $this->informasiTambahanModel->where('id_ws', $id)->findAll();

            if (empty($tambahans)) {
                $incomplete[] = [
                    'name'  => 'informasi_tambahan',
                    'label' => 'Data Informasi Tambahan wajib diisi untuk Pengurusan Tambahan'
                ];
            } else {
                foreach ($tambahans as $t) {
                    if (
                        empty(trim($t['nama_pengurusan'] ?? '')) ||
                        empty(trim($t['tgl_pengurusan'] ?? '')) ||
                        $t['tgl_pengurusan'] === '0000-00-00'
                    ) {
                        $incomplete[] = [
                            'name'  => 'informasi_tambahan_detail',
                            'label' => 'Beberapa kolom Informasi Tambahan (Nama Pengurusan atau Tanggal Pengurusan) belum lengkap'
                        ];
                        break; // cukup satu kali error
                    }
                }
            }
        } else {
            // Jika Tidak Ada Tambahan → hapus data tambahan lama (jika ada)
            $this->informasiTambahanModel->where('id_ws', $id)->delete();
        }




        // ==============================
        // Hasil akhir
        // ==============================
        if (empty($incomplete)) {
            return $this->response->setJSON([
                'status'  => 'complete',
                'message' => 'Semua data wajib sudah terisi.'
            ]);
        }

        return $this->response->setJSON([
            'status'         => 'incomplete',
            'message'        => 'Beberapa data belum diisi.',
            'missing_fields' => $incomplete
        ]);
    }

    /**
     * ==========================
     * EDIT WORKSHEET EXPORT
     * ==========================
     */
    public function editExport($id)
    {
        $worksheet = $this->exportModel->find($id);

        if (!$worksheet) {
            return redirect()->back()->with('error', 'Data worksheet export tidak ditemukan.');
        }

        return view('worksheet/edit_export', [
            'worksheet' => $worksheet
        ]);
    }

    /**
     * ==========================
     * UPDATE WORKSHEET EXPORT
     * ==========================
     */
    public function updateExport($id)
    {
        $data = [
            'no_ws'         => $this->request->getPost('no_ws'),
            'no_aju'        => $this->request->getPost('no_aju'),
            'no_peb'        => $this->request->getPost('no_peb'),
            'tgl_peb'       => $this->request->getPost('tgl_peb'),
            'shipper'       => $this->request->getPost('shipper'),
            'consignee'     => $this->request->getPost('consignee'),
            'vessel'        => $this->request->getPost('vessel'),
            'no_voyage'     => $this->request->getPost('no_voyage'),
            'pol'           => $this->request->getPost('pol'),
            'pod'           => $this->request->getPost('pod'),
            'shipping_line' => $this->request->getPost('shipping_line'),
            'commodity'     => $this->request->getPost('commodity'),
            'party'         => $this->request->getPost('party'),
            'jenis_con'     => $this->request->getPost('jenis_con'),
            'qty'           => $this->request->getPost('qty'),
            'net'           => $this->request->getPost('net'),
            'gross'         => $this->request->getPost('gross'),
            'bl'            => $this->request->getPost('bl'),
            'tgl_bl'        => $this->request->getPost('tgl_bl'),
            'master_bl'     => $this->request->getPost('master_bl'),
            'tgl_master'    => $this->request->getPost('tgl_master'),
            'no_invoice'    => $this->request->getPost('no_invoice'),
            'tgl_invoice'   => $this->request->getPost('tgl_invoice'),
            'etd'           => $this->request->getPost('etd'),
            'eta'           => $this->request->getPost('eta'),
            'asuransi'      => $this->request->getPost('asuransi'),
            'top'           => $this->request->getPost('top'),
            'berita_acara'  => $this->request->getPost('berita_acara'),
            'updated_at'    => date('Y-m-d H:i:s')
        ];

        $data = array_filter($data, fn($v) => $v !== null && $v !== '');

        $this->exportModel->update($id, $data);
        return redirect()->to('/worksheet?type=export')->with('success', 'Worksheet export berhasil diperbarui.');
    }
}
