<?php

namespace App\Controllers;

use App\Controllers\BaseController;

// ======================
// IMPORT MODELS
// ======================
use App\Models\WorksheetImport\WorkSheetImportModel;
use App\Models\WorksheetImport\WorksheetContainerModel;
use App\Models\WorksheetImport\WorksheetTruckingModel;
use App\Models\WorksheetImport\WorksheetDoModel;
use App\Models\WorksheetImport\WorksheetLartasModel;
use App\Models\WorksheetImport\WorksheetInformasiTambahanModel;
use App\Models\WorksheetImport\WorksheetFasilitasModel;

// ======================
// EXPORT MODELS
// ======================
use App\Models\WorksheetExport\WorkSheetExportModel;
use App\Models\WorksheetExport\WorksheetContainerExportModel;
use App\Models\WorksheetExport\WorksheetTruckingExportModel;
use App\Models\WorksheetExport\WorksheetDoExportModel;
use App\Models\WorksheetExport\WorksheetLartasExportModel;
use App\Models\WorksheetExport\WorksheetInformasiTambahanExportModel;
use App\Models\WorksheetExport\WorksheetFasilitasExportModel;

class Worksheet extends BaseController
{
    // ======================
    // IMPORT PROPERTIES
    // ======================
    protected $importModel;
    protected $containerModel;
    protected $truckingModel;
    protected $doModel;
    protected $lartasModel;
    protected $informasiTambahanModel;
    protected $fasilitasModel;

    // ======================
    // EXPORT PROPERTIES
    // ======================
    protected $exportModel;
    protected $containerExportModel;
    protected $truckingExportModel;
    protected $doExportModel;
    protected $lartasExportModel;
    protected $informasiTambahanExportModel;
    protected $fasilitasExportModel;

    public function __construct()
    {
        // ======================
        // IMPORT MODELS
        // ======================
        $this->importModel              = new WorkSheetImportModel();
        $this->containerModel           = new WorksheetContainerModel();
        $this->truckingModel            = new WorksheetTruckingModel();
        $this->doModel                  = new WorksheetDoModel();
        $this->lartasModel              = new WorksheetLartasModel();
        $this->informasiTambahanModel   = new WorksheetInformasiTambahanModel();
        $this->fasilitasModel           = new WorksheetFasilitasModel();

        // ======================
        // EXPORT MODELS
        // ======================
        $this->exportModel              = new WorkSheetExportModel();
        $this->containerExportModel     = new WorksheetContainerExportModel();
        $this->truckingExportModel      = new WorksheetTruckingExportModel();
        $this->doExportModel            = new WorksheetDoExportModel();
        $this->lartasExportModel        = new WorksheetLartasExportModel();
        $this->informasiTambahanExportModel = new WorksheetInformasiTambahanExportModel();
        $this->fasilitasExportModel     = new WorksheetFasilitasExportModel();
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
        $fasilitass         = $this->fasilitasModel ->where('id_ws', $id)->findAll();
        

        return view('worksheet/edit_import', [
            'worksheet'  => $worksheet,
            'containers' => $containers,
            'truckings'  => $truckings,
            'dos'        => $dos,
            'lartass'    => $lartass,
            'informasitambahans'  => $informasitambahans,
            'fasilitass' => $fasilitass
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
            'jenis_fasilitas'   => $this->request->getPost('jenis_fasilitas'),
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
            'no_ws','pengurusan_pib','no_aju','tgl_aju','pib_nopen','tgl_nopen',
            'tgl_sppb','shipper','consignee','vessel',
            'no_voyage','pol','terminal','shipping_line','commodity',
            'party','qty','kemasan','net','gross','bl','tgl_bl','no_invoice',
            'tgl_invoice','eta','pengurusan_do','asuransi','top','pengurusan_lartas',
            'penjaluran','jenis_tambahan','jenis_fasilitas'
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

        // ==========================
        // SIMPAN DATA DOK ORIGINAL
        // ==========================
        $dokOri = $this->request->getPost('dok_ori');
        $tglOri    = $this->request->getPost('tgl_ori');

        // Jika Belum Ada → kosongkan tanggal dok ori
        if ($dokOri === 'Belum Ada') {
            $tglOri = null;
        }

        // Update ke worksheet_import
        $this->importModel->update($id, [
            'dok_ori'   => $dokOri,
            'tgl_ori'  => $tglOri,
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
         * SIMPAN DATA FASILITAS
         * ==========================
         */
        $jenisFasilitas = $this->request->getPost('jenis_fasilitas');
        $tipeFasilitas  = $this->request->getPost('tipe_fasilitas');
        $namaFasilitas  = $this->request->getPost('nama_fasilitas');
        $tglFasilitas   = $this->request->getPost('tgl_fasilitas');
        $noFasilitas    = $this->request->getPost('no_fasilitas');

        // Hapus data lama agar tidak duplikat
        $this->fasilitasModel->where('id_ws', $id)->delete();

        if ($jenisFasilitas === 'Pengurusan Fasilitas' || $jenisFasilitas === 'Fasilitas Sendiri') {
            $hasFasilitas = false;

            if (!empty($namaFasilitas)) {
                foreach ($namaFasilitas as $i => $nama) {
                    if (
                        !empty(trim($nama)) ||
                        !empty(trim($tglFasilitas[$i] ?? '')) ||
                        !empty(trim($noFasilitas[$i] ?? ''))
                    ) {
                        $this->fasilitasModel->insert([
                            'id_ws'          => $id,
                            'tipe_fasilitas' => isset($tipeFasilitas[$i]) ? trim($tipeFasilitas[$i]) : null,
                            'nama_fasilitas' => isset($namaFasilitas[$i]) ? trim($namaFasilitas[$i]) : null,
                            'tgl_fasilitas'  => isset($tglFasilitas[$i]) ? trim($tglFasilitas[$i]) : null,
                            'no_fasilitas'   => isset($noFasilitas[$i]) ? trim($noFasilitas[$i]) : null,
                            'created_at'     => date('Y-m-d H:i:s')
                        ]);
                        $hasFasilitas = true;
                    }
                }
            }

            if (!$hasFasilitas) {
                $allFilled = false; // Tidak ada data valid diinput
            }

        } elseif ($jenisFasilitas === 'Tidak Ada Fasilitas') {
            // Jika tidak ada fasilitas → hapus semua data
            $this->fasilitasModel->where('id_ws', $id)->delete();
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
            'pib_nopen'       => 'Nomor PIB / Nopen',
            'tgl_nopen'       => 'Tanggal Nopen',
            'tgl_sppb'        => 'Tanggal SPPB',
            'penjaluran'      => 'Penjaluran',
            'shipper'         => 'Shipper',
            'consignee'       => 'Consignee',
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
            'no_invoice'      => 'Nomor Invoice',
            'tgl_invoice'     => 'Tanggal Invoice',
            'eta'             => 'ETA',
            'dok_ori'         => 'Dokumen Original',
            'pengurusan_do'   => 'Pengurusan Delivery Order',
            'jenis_con'       => 'Jenis Container',
            'jenis_trucking'  => 'Pengurusan Trucking',
            'pengurusan_lartas' => 'Pengurusan Lartas',
            'jenis_fasilitas' => 'Jenis Fasilitas',
            'jenis_tambahan'  => 'Jenis Tambahan',
            'asuransi'        => 'Asuransi',
            'top'             => 'TOP'
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
        // Cek data dok original
        // ==============================
        if (($data['dok_ori'] ?? '') === 'Sudah Ada') {
            if (empty($data['tgl_ori']) || $data['tgl_ori'] === '0000-00-00') {
                $incomplete[] = [
                    'name'  => 'tgl_ori',
                    'label' => 'Tanggal Terima Dokumen Original wajib diisi karena Sudah Ada'
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
        // Cek data Fasilitas jika diperlukan
        // ==============================
        if (
            ($data['jenis_fasilitas'] ?? '') === 'Pengurusan Fasilitas' ||
            ($data['jenis_fasilitas'] ?? '') === 'Fasilitas Sendiri'
        ) {
            $fasilitas = $this->fasilitasModel->where('id_ws', $id)->findAll();

            if (empty($fasilitas)) {
                $incomplete[] = [
                    'name'  => 'fasilitas',
                    'label' => 'Data Fasilitas wajib diisi untuk Pengurusan/Fasilitas Sendiri'
                ];
            } else {
                foreach ($fasilitas as $f) {
                    if (
                        empty(trim($f['tipe_fasilitas'] ?? '')) ||
                        empty(trim($f['nama_fasilitas'] ?? '')) ||
                        empty(trim($f['tgl_fasilitas'] ?? '')) ||
                        empty(trim($f['no_fasilitas'] ?? '')) ||
                        $f['tgl_fasilitas'] === '0000-00-00'
                    ) {
                        $incomplete[] = [
                            'name'  => 'fasilitas_detail',
                            'label' => 'Beberapa kolom Fasilitas (Tipe, Nama, Tanggal, atau Nomor Fasilitas) belum lengkap'
                        ];
                        break; // cukup satu kali error
                    }
                }
            }
        } else {
            // Jika Tidak Ada Fasilitas → hapus data lama
            $this->fasilitasModel->where('id_ws', $id)->delete();
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

    // ======================================================================================================================================
    // ======================================================================================================================================
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

        // Ambil semua data relasi berdasarkan id_ws
        $containers          = $this->containerExportModel->getByWorksheet($id);
        $trucking            = $this->truckingExportModel->getByWorksheet($id);
        $do                  = $this->doExportModel->where('id_ws', $id)->findAll();
        $lartas              = $this->lartasExportModel->getByWorksheet($id);
        $informasiTambahan   = $this->informasiTambahanExportModel->getByWorksheet($id);
        $fasilitas           = $this->fasilitasExportModel->getByWorksheet($id);

        return view('worksheet/export/edit_export', [
            'worksheet'           => $worksheet,
            'containers'          => $containers,
            'trucking'            => $trucking,
            'do'                  => $do,
            'lartas'              => $lartas,
            'informasi_tambahan'  => $informasiTambahan,
            'fasilitas'           => $fasilitas,
        ]);
    }


    /**
     * ==========================
     * UPDATE WORKSHEET EXPORT
     * ==========================
     */
    public function updateExport($id)
    {
        // ======================
        // 1. Update data utama worksheet_export
        // ======================
        $data = [
            'no_ws'             => $this->request->getPost('no_ws'),
            'no_aju'            => $this->request->getPost('no_aju'),
            'pengurusan_peb'    => $this->request->getPost('pengurusan_peb'),
            'peb_nopen'         => $this->request->getPost('peb_nopen'),
            'tgl_aju'           => $this->request->getPost('tgl_aju'),
            'tgl_nopen'         => $this->request->getPost('tgl_nopen'),
            'no_po'             => $this->request->getPost('no_po'),
            'io_number'         => $this->request->getPost('io_number'),
            'penjaluran'        => $this->request->getPost('penjaluran'),
            'tgl_npe'           => $this->request->getPost('tgl_npe'),
            'tgl_spjm'          => $this->request->getPost('tgl_spjm'),
            'shipper'           => $this->request->getPost('shipper'),
            'consignee'         => $this->request->getPost('consignee'),
            'notify_party'      => $this->request->getPost('notify_party'),
            'vessel'            => $this->request->getPost('vessel'),
            'no_voyage'         => $this->request->getPost('no_voyage'),
            'pol'               => $this->request->getPost('pol'),
            'pod'               => $this->request->getPost('pod'),
            'shipping_line'     => $this->request->getPost('shipping_line'),
            'commodity'         => $this->request->getPost('commodity'),
            'party'             => $this->request->getPost('party'),
            'jenis_con'         => $this->request->getPost('jenis_con'),
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
            'etd'               => $this->request->getPost('etd'),
            'closing'           => $this->request->getPost('closing'),
            'stuffing'          => $this->request->getPost('stuffing'),
            'depo'              => $this->request->getPost('depo'),
            'terminal'          => $this->request->getPost('terminal'),
            'dok_ori'           => $this->request->getPost('dok_ori'),
            'tgl_ori'           => $this->request->getPost('tgl_ori'),
            'pengurusan_do'     => $this->request->getPost('pengurusan_do'),
            'asuransi'          => $this->request->getPost('asuransi'),
            'jenis_trucking'    => $this->request->getPost('jenis_trucking'),
            'jenis_fasilitas'   => $this->request->getPost('jenis_fasilitas'),
            'jenis_tambahan'    => $this->request->getPost('jenis_tambahan'),
            'pengurusan_lartas' => $this->request->getPost('pengurusan_lartas'),
            'top'               => $this->request->getPost('top'),
            'berita_acara'      => $this->request->getPost('berita_acara'),
            'updated_at'        => date('Y-m-d H:i:s')
        ];

        // Hapus field kosong agar tidak overwrite nilai lama
        $data = array_filter($data, fn($v) => $v !== null && $v !== '');

        $this->exportModel->update($id, $data);

        // ======================
        // 2. Update data CONTAINER
        // ======================
        $this->containerExportModel->where('id_ws', $id)->delete();
        $containers = $this->request->getPost('container');
        if (!empty($containers) && is_array($containers)) {
            $insertData = [];
            foreach ($containers as $c) {
                if (!empty($c['no_container'])) {
                    $insertData[] = [
                        'id_ws'        => $id,
                        'no_container' => $c['no_container'],
                        'seal'         => $c['seal'] ?? null,
                        'size'         => $c['size'] ?? null,
                        'created_at'   => date('Y-m-d H:i:s')
                    ];
                }
            }
            if ($insertData) {
                $this->containerExportModel->insertBatch($insertData);
            }
        }

        // ======================
        // 3. Update data TRUCKING
        // ======================
        $this->truckingExportModel->where('id_ws', $id)->delete();
        $trucking = $this->request->getPost('trucking');
        if (!empty($trucking) && is_array($trucking)) {
            $insertData = [];
            foreach ($trucking as $t) {
                if (!empty($t['no_mobil'])) {
                    $insertData[] = [
                        'id_ws'      => $id,
                        'no_mobil'   => $t['no_mobil'],
                        'tipe_mobil' => $t['tipe_mobil'] ?? null,
                        'nama_supir' => $t['nama_supir'] ?? null,
                        'alamat'     => $t['alamat'] ?? null,
                        'telp_supir' => $t['telp_supir'] ?? null,
                        'created_at' => date('Y-m-d H:i:s')
                    ];
                }
            }
            if ($insertData) {
                $this->truckingExportModel->insertBatch($insertData);
            }
        }

        // ======================
        // 4. Update data DO
        // ======================
        $this->doExportModel->where('id_ws', $id)->delete();
        $doData = $this->request->getPost('do');
        if (!empty($doData) && is_array($doData)) {
            $insertData = [];
            foreach ($doData as $d) {
                if (!empty($d['tipe_do'])) {
                    $insertData[] = [
                        'id_ws'        => $id,
                        'tipe_do'      => $d['tipe_do'],
                        'pengambil_do' => $d['pengambil_do'] ?? null,
                        'tgl_mati_do'  => $d['tgl_mati_do'] ?? null,
                        'created_at'   => date('Y-m-d H:i:s')
                    ];
                }
            }
            if ($insertData) {
                $this->doExportModel->insertBatch($insertData);
            }
        }

        // ======================
        // 5. Update data LARTAS
        // ======================
        $this->lartasExportModel->where('id_ws', $id)->delete();
        $lartasData = $this->request->getPost('lartas');
        if (!empty($lartasData) && is_array($lartasData)) {
            $insertData = [];
            foreach ($lartasData as $l) {
                if (!empty($l['nama_lartas'])) {
                    $insertData[] = [
                        'id_ws'       => $id,
                        'nama_lartas' => $l['nama_lartas'],
                        'no_lartas'   => $l['no_lartas'] ?? null,
                        'tgl_lartas'  => $l['tgl_lartas'] ?? null,
                        'created_at'  => date('Y-m-d H:i:s')
                    ];
                }
            }
            if ($insertData) {
                $this->lartasExportModel->insertBatch($insertData);
            }
        }

        // ======================
        // 6. Update data INFORMASI TAMBAHAN
        // ======================
        $this->informasiTambahanExportModel->where('id_ws', $id)->delete();
        $infoTambahan = $this->request->getPost('informasi_tambahan');
        if (!empty($infoTambahan) && is_array($infoTambahan)) {
            $insertData = [];
            foreach ($infoTambahan as $info) {
                if (!empty($info['nama_pengurusan'])) {
                    $insertData[] = [
                        'id_ws'           => $id,
                        'nama_pengurusan' => $info['nama_pengurusan'],
                        'tgl_pengurusan'  => $info['tgl_pengurusan'] ?? null,
                        'created_at'      => date('Y-m-d H:i:s')
                    ];
                }
            }
            if ($insertData) {
                $this->informasiTambahanExportModel->insertBatch($insertData);
            }
        }

        // ======================
        // 7. Update data FASILITAS
        // ======================
        $this->fasilitasExportModel->where('id_ws', $id)->delete();
        $fasilitasData = $this->request->getPost('fasilitas');
        if (!empty($fasilitasData) && is_array($fasilitasData)) {
            $insertData = [];
            foreach ($fasilitasData as $f) {
                if (!empty($f['tipe_fasilitas'])) {
                    $insertData[] = [
                        'id_ws'          => $id,
                        'tipe_fasilitas' => $f['tipe_fasilitas'],
                        'nama_fasilitas' => $f['nama_fasilitas'] ?? null,
                        'tgl_fasilitas'  => $f['tgl_fasilitas'] ?? null,
                        'no_fasilitas'   => $f['no_fasilitas'] ?? null,
                        'created_at'     => date('Y-m-d H:i:s')
                    ];
                }
            }
            if ($insertData) {
                $this->fasilitasExportModel->insertBatch($insertData);
            }
        }

        // ======================
        // 8. Redirect & Pesan Sukses
        // ======================
        return redirect()
            ->to('/worksheet?type=export')
            ->with('success', 'Worksheet export dan semua data relasi berhasil diperbarui.');
    }


     /**
 * ==========================
 * CEK KELENGKAPAN WORKSHEET EXPORT
 * ==========================
 */
public function checkExport($id)
{
    $data = $this->exportModel->find($id);

    if (!$data) {
        return $this->response->setJSON([
            'status'  => 'error',
            'message' => 'Data worksheet export tidak ditemukan.'
        ]);
    }

    // ======================
    // 1. Cek field utama di worksheet_export
    // ======================
    $requiredFields = [
        'no_ws'         => 'Nomor Worksheet',
        'no_aju'        => 'Nomor AJU',
        'no_peb'        => 'Nomor PEB',
        'tgl_peb'       => 'Tanggal PEB',
        'shipper'       => 'Shipper',
        'consignee'     => 'Consignee',
        'vessel'        => 'Vessel',
        'no_voyage'     => 'Nomor Voyage',
        'pol'           => 'Port of Loading (POL)',
        'pod'           => 'Port of Discharge (POD)',
        'shipping_line' => 'Shipping Line',
        'commodity'     => 'Commodity',
        'party'         => 'Party',
        'jenis_con'     => 'Jenis Container',
        'qty'           => 'Quantity',
        'net'           => 'Net Weight',
        'gross'         => 'Gross Weight',
        'bl'            => 'Bill of Lading (BL)',
        'tgl_bl'        => 'Tanggal BL',
        'no_invoice'    => 'Nomor Invoice',
        'tgl_invoice'   => 'Tanggal Invoice',
        'etd'           => 'ETD',
        'eta'           => 'ETA',
        'asuransi'      => 'Asuransi',
        'top'           => 'TOP',
        'berita_acara'  => 'Berita Acara'
    ];

    $incomplete = [];
    foreach ($requiredFields as $field => $label) {
        $value = $data[$field] ?? null;
        if (empty($value) || $value === '0000-00-00' || $value === '1970-01-01') {
            $incomplete[] = [
                'name'  => $field,
                'label' => $label
            ];
        }
    }

    // ==============================
    // 2. Cek data Container jika FCL
    // ==============================
    if (($data['jenis_con'] ?? '') === 'FCL') {
        $containers = $this->containerExportModel->where('id_ws', $id)->findAll();

        if (empty($containers)) {
            $incomplete[] = [
                'name'  => 'container',
                'label' => 'Data Container wajib diisi untuk FCL'
            ];
        } else {
            foreach ($containers as $c) {
                if (empty($c['no_container']) || empty($c['seal']) || empty($c['size'])) {
                    $incomplete[] = [
                        'name'  => 'container_detail',
                        'label' => 'Beberapa kolom Container (No Container, Seal, atau Size) belum lengkap'
                    ];
                    break;
                }
            }
        }
    }

    // ==============================
    // 3. Cek data Trucking (jika ada)
    // ==============================
    $truckings = $this->truckingExportModel->where('id_ws', $id)->findAll();
    if (!empty($truckings)) {
        foreach ($truckings as $t) {
            if (empty($t['no_mobil']) || empty($t['tipe_mobil']) || empty($t['nama_supir'])) {
                $incomplete[] = [
                    'name'  => 'trucking_detail',
                    'label' => 'Beberapa kolom Trucking (No Mobil, Tipe Mobil, atau Nama Supir) belum lengkap'
                ];
                break;
            }
        }
    }

    // ==============================
    // 4. Cek data Lartas (jika ada)
    // ==============================
    $lartas = $this->lartasExportModel->where('id_ws', $id)->findAll();
    if (!empty($lartas)) {
        foreach ($lartas as $l) {
            if (empty($l['nama_lartas']) || empty($l['no_lartas']) || empty($l['tgl_lartas']) || $l['tgl_lartas'] === '0000-00-00') {
                $incomplete[] = [
                    'name'  => 'lartas_detail',
                    'label' => 'Beberapa kolom Lartas (Nama, Nomor, atau Tanggal Lartas) belum lengkap'
                ];
                break;
            }
        }
    }

    // ==============================
    // 5. Cek data DO (jika ada)
    // ==============================
    $dos = $this->doExportModel->where('id_ws', $id)->findAll();
    if (!empty($dos)) {
        foreach ($dos as $d) {
            if (empty($d['tipe_do']) || empty($d['pengambil_do']) || empty($d['tgl_mati_do']) || $d['tgl_mati_do'] === '0000-00-00') {
                $incomplete[] = [
                    'name'  => 'do_detail',
                    'label' => 'Beberapa kolom DO (Tipe DO, Pengambil DO, atau Tanggal Mati DO) belum lengkap'
                ];
                break;
            }
        }
    }

    // ==============================
    // 6. Cek data Fasilitas (jika ada)
    // ==============================
    $fasilitas = $this->fasilitasExportModel->where('id_ws', $id)->findAll();
    if (!empty($fasilitas)) {
        foreach ($fasilitas as $f) {
            if (empty($f['tipe_fasilitas']) || empty($f['nama_fasilitas']) || empty($f['tgl_fasilitas']) || empty($f['no_fasilitas']) || $f['tgl_fasilitas'] === '0000-00-00') {
                $incomplete[] = [
                    'name'  => 'fasilitas_detail',
                    'label' => 'Beberapa kolom Fasilitas (Tipe, Nama, Tanggal, atau Nomor) belum lengkap'
                ];
                break;
            }
        }
    }

    // ==============================
    // 7. Cek data Informasi Tambahan (jika ada)
    // ==============================
    $tambahans = $this->informasiTambahanExportModel->where('id_ws', $id)->findAll();
    if (!empty($tambahans)) {
        foreach ($tambahans as $t) {
            if (empty($t['nama_pengurusan']) || empty($t['tgl_pengurusan']) || $t['tgl_pengurusan'] === '0000-00-00') {
                $incomplete[] = [
                    'name'  => 'informasi_tambahan_detail',
                    'label' => 'Beberapa kolom Informasi Tambahan (Nama Pengurusan atau Tanggal) belum lengkap'
                ];
                break;
            }
        }
    }

    // ==============================
    // 8. Hasil akhir
    // ==============================
    if (empty($incomplete)) {
        return $this->response->setJSON([
            'status'  => 'complete',
            'message' => 'Semua data worksheet export sudah lengkap.'
        ]);
    }

    return $this->response->setJSON([
        'status'         => 'incomplete',
        'message'        => 'Beberapa data worksheet export belum diisi.',
        'missing_fields' => $incomplete
    ]);
}

    

}
