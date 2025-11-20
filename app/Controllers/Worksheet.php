<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use Dompdf\Dompdf;
use Dompdf\Options;

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
        $dos                = $this->doModel->where('id_ws', $id)->findAll();
        $lartass            = $this->lartasModel->where('id_ws', $id)->findAll();
        $informasitambahans = $this->informasiTambahanModel->where('id_ws', $id)->findAll();
        $fasilitass         = $this->fasilitasModel->where('id_ws', $id)->findAll();


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
     * ========================
     * UPDATE WORKSHEET IMPORT 
     * ========================
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
            'no_ws',
            'pengurusan_pib',
            'no_aju',
            'tgl_aju',
            'pib_nopen',
            'tgl_nopen',
            'tgl_sppb',
            'shipper',
            'consignee',
            'vessel',
            'no_voyage',
            'pol',
            'terminal',
            'shipping_line',
            'commodity',
            'party',
            'qty',
            'kemasan',
            'net',
            'gross',
            'bl',
            'tgl_bl',
            'no_invoice',
            'tgl_invoice',
            'eta',
            'pengurusan_do',
            'asuransi',
            'top',
            'pengurusan_lartas',
            'penjaluran',
            'jenis_tambahan',
            'jenis_fasilitas'
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
         * ================
         * SIMPAN DATA DO
         * ================
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
         * ===================
         * SIMPAN DATA LARTAS
         * ===================
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
         * ==============================
         * SIMPAN DATA INFORMASI TAMBAHAN
         * ==============================
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

        // Jangan ubah status di updateImport
        unset($data['status']);

        // Update worksheet_import TANPA menyentuh kolom status
        $this->importModel->update($id, $data);


        session()->setFlashdata('success', 'Data worksheet import berhasil diperbarui.');
        return redirect()->to(base_url('worksheet') . '?type=import');
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
        if (($data['pengurusan_do'] ?? '') === 'Pengambilan Delivery Order') {
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
        // HASIL AKHIR
        // ==============================
        if (empty($incomplete)) {

            $this->importModel->update($id, [
                'status' => 'completed'
            ]);

            return $this->response->setJSON([
                'status'  => 'complete',
                'message' => 'Semua data worksheet import sudah lengkap.'
            ]);
        }

        $this->importModel->update($id, [
            'status' => 'not completed'
        ]);

        return $this->response->setJSON([
            'status'         => 'incomplete',
            'message'        => 'Beberapa data worksheet import belum diisi.',
            'missing_fields' => $incomplete
        ]);
    }


    /**
     * PRINT WORKSHEET IMPORT
     */
    public function printImport($encoded_id)
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

        // ==============
        // GET DATA
        // ==============
        $ws = $this->importModel->find($id);

        if (!$ws) {
            return redirect()->back()->with('error', 'Worksheet tidak ditemukan.');
        }

        // ==============
        // RELATIONS
        // ==============
        $container  = $this->containerModel->getByWorksheet($id);
        $trucking   = $this->truckingModel->getByWorksheet($id);
        $do         = $this->doModel->where('id_ws', $id)->findAll();
        $lartas     = $this->lartasModel->getByWorksheet($id);
        $tambahan   = $this->informasiTambahanModel->getByWorksheet($id);
        $fasilitas  = $this->fasilitasModel->getByWorksheet($id);

        // ==============
        // VIEW
        // ==============
        $html = view('worksheet/print_import', [
            'ws'        => $ws,
            'container' => $container,
            'trucking'  => $trucking,
            'do'        => $do,
            'lartas'    => $lartas,
            'fasilitas' => $fasilitas,
            'tambahan'  => $tambahan,
        ]);

        // ==============
        // PDF SETUP
        // ==============
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);

        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $filename = 'Worksheet_Import_' . $ws['no_ws'] . '.pdf';

        return $dompdf->stream($filename, ["Attachment" => false]);
    }

    // ============================================================
    // HAPUS WORKSHEET IMPORT + PINDAHKAN SEMUA RELASI KE TRASH
    // ============================================================
    public function deleteImport($id)
    {
        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // IMPORT trash models
            $trashMain      = new \App\Models\WorksheetImportTrash\WorkSheetImportTrashModel();
            $trashContainer = new \App\Models\WorksheetImportTrash\WorksheetContainerTrashModel();
            $trashDo        = new \App\Models\WorksheetImportTrash\WorksheetDoTrashModel();
            $trashFasilitas = new \App\Models\WorksheetImportTrash\WorksheetFasilitasTrashModel();
            $trashTruck     = new \App\Models\WorksheetImportTrash\WorksheetTruckingTrashModel();
            $trashLartas    = new \App\Models\WorksheetImportTrash\WorksheetLartasTrashModel();
            $trashInfo      = new \App\Models\WorksheetImportTrash\WorksheetInformasiTambahanTrashModel();

            // Ambil worksheet import utama
            $row = $this->importModel->find($id);
            if (!$row) {
                $db->transComplete();
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Worksheet import tidak ditemukan.'
                ])->setStatusCode(404);
            }

            // who deleted
            $deletedBy = null;
            if (function_exists('user') && user()) {
                $deletedBy = user()->username ?? user()->email ?? null;
            }
            if (empty($deletedBy)) {
                $session = session();
                $deletedBy = $session->get('username') ?? $session->get('email') ?? 'system';
            }

            $now = date('Y-m-d H:i:s');

            // Siapkan parent untuk trash
            $parentCopy = $row;
            $parentCopy['deleted_at'] = $now;
            $parentCopy['deleted_by'] = $deletedBy;

            // unset id supaya tidak bentrok PK dengan tabel trash (trash autonumber sendiri)
            unset($parentCopy['id']);

            // Insert ke trash parent
            $newTrashId = $trashMain->insert($parentCopy);
            if (!$newTrashId) {
                // gagal insert ke trash
                $db->transComplete();
                log_message('error', 'Gagal insert worksheet ke trash. original id=' . $id);
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Gagal memindahkan worksheet ke trash.'
                ])->setStatusCode(500);
            }

            // Helper untuk copy relasi
            $copyToTrash = function ($dataList, $trashModel) use ($newTrashId, $deletedBy, $now) {
                foreach ($dataList as $d) {
                    // hapus id utama agar tidak bentrok
                    if (isset($d['id'])) unset($d['id']);

                    // set id_ws ke id trash parent agar child 'link' ke parent di trash
                    $d['id_ws'] = $newTrashId;
                    $d['deleted_at'] = $now;
                    $d['deleted_by'] = $deletedBy;

                    // insert via model (model harus punya allowedFields yang sesuai)
                    $trashModel->insert($d);
                }
            };

            // Ambil dan copy semua child -> ke trash child, kemudian delete dari main
            // CONTAINER
            $containers = $this->containerModel->where('id_ws', $id)->findAll();
            if (!empty($containers)) {
                $copyToTrash($containers, $trashContainer);
                $this->containerModel->where('id_ws', $id)->delete();
            }

            // DO
            $dos = $this->doModel->where('id_ws', $id)->findAll();
            if (!empty($dos)) {
                $copyToTrash($dos, $trashDo);
                $this->doModel->where('id_ws', $id)->delete();
            }

            // FASILITAS
            $fas = $this->fasilitasModel->where('id_ws', $id)->findAll();
            if (!empty($fas)) {
                $copyToTrash($fas, $trashFasilitas);
                $this->fasilitasModel->where('id_ws', $id)->delete();
            }

            // TRUCKING
            $trucks = $this->truckingModel->where('id_ws', $id)->findAll();
            if (!empty($trucks)) {
                $copyToTrash($trucks, $trashTruck);
                $this->truckingModel->where('id_ws', $id)->delete();
            }

            // LARTAS
            $lartas = $this->lartasModel->where('id_ws', $id)->findAll();
            if (!empty($lartas)) {
                $copyToTrash($lartas, $trashLartas);
                $this->lartasModel->where('id_ws', $id)->delete();
            }

            // INFORMASI TAMBAHAN
            $infos = $this->informasiTambahanModel->where('id_ws', $id)->findAll();
            if (!empty($infos)) {
                $copyToTrash($infos, $trashInfo);
                $this->informasiTambahanModel->where('id_ws', $id)->delete();
            }

            // Hapus parent dari tabel utama
            $this->importModel->delete($id);

            $db->transComplete();

            if ($db->transStatus() === false) {
                log_message('error', 'Transaksi moveToTrash gagal untuk worksheet id=' . $id);
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Gagal memindahkan worksheet ke trash (transaksi gagal).'
                ])->setStatusCode(500);
            }

            return $this->response->setJSON([
                'status' => 'ok',
                'message' => 'Worksheet import berhasil dihapus.'
            ]);
        } catch (\Throwable $e) {
            $db->transComplete();
            log_message('error', 'Worksheet Import delete error: ' . $e->getMessage());

            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat menghapus worksheet.'
            ])->setStatusCode(500);
        }
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
        $truckings           = $this->truckingExportModel->getByWorksheet($id);
        $dos                 = $this->doExportModel->where('id_ws', $id)->findAll();
        $lartass             = $this->lartasExportModel->getByWorksheet($id);
        $informasiTambahans  = $this->informasiTambahanExportModel->getByWorksheet($id);
        $fasilitass          = $this->fasilitasExportModel->getByWorksheet($id);

        return view('worksheet/edit_export', [
            'worksheet'           => $worksheet,
            'containers'          => $containers,
            'truckings'           => $truckings,
            'dos'                 => $dos,
            'lartass'             => $lartass,
            'informasitambahans' => $informasiTambahans,
            'fasilitass'          => $fasilitass,
        ]);
    }

    /**
     * ==========================
     * UPDATE DATA UTAMA EXPORT
     * ==========================
     */
    public function updateExport($id)
    {
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
            'terminal'          => $this->request->getPost('terminal'),
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

        // Hilangkan nilai null agar tidak overwrite data lama
        $data = array_filter($data, fn($v) => $v !== null);

        // Ambil record lama untuk merge
        $existing = $this->exportModel->find($id);
        $merged   = array_merge($existing ?? [], $data);

        /**
         * ==========================
         * CEK REQUIRED FIELDS (EXPORT)
         * ==========================
         */
        $requiredFields = [
            'no_ws',
            'no_aju',
            'pengurusan_peb',
            'peb_nopen',
            'tgl_aju',
            'tgl_nopen',
            'shipper',
            'consignee',
            'vessel',
            'no_voyage',
            'pol',
            'pod',
            'shipping_line',
            'commodity',
            'party',
            'qty',
            'kemasan',
            'net',
            'gross',
            'bl',
            'tgl_bl',
            'no_invoice',
            'tgl_invoice',
            'etd',
            'pengurusan_do',
            'asuransi',
            'top',
            'pengurusan_lartas',
            'jenis_fasilitas',
            'jenis_tambahan',
            'penjaluran',
            'tgl_npe',
            'depo',
            'stuffing',
            'terminal',
            'closing'
        ];

        $allFilled = true;
        foreach ($requiredFields as $field) {
            if (empty($merged[$field])) {
                $allFilled = false;
                break;
            }
        }

        /**
         * ==========================
         * PENJALURAN (EXPORT)
         * ==========================
         */
        $penjaluran = $this->request->getPost('penjaluran');
        $tglSpjm    = $this->request->getPost('tgl_spjm');

        if ($penjaluran === 'NPE') {
            $tglSpjm = null;
        }

        $this->exportModel->update($id, [
            'penjaluran' => $penjaluran,
            'tgl_spjm'   => $tglSpjm,
        ]);

        /**
         * ==========================
         * DOKUMEN ORIGINAL (EXPORT)
         * ==========================
         */
        $dokOri  = $this->request->getPost('dok_ori');
        $tglOri  = $this->request->getPost('tgl_ori');

        if ($dokOri === 'Belum Ada') {
            $tglOri = null;
        }

        $this->exportModel->update($id, [
            'dok_ori' => $dokOri,
            'tgl_ori' => $tglOri
        ]);

        /**
         * ==========================
         * CONTAINER EXPORT
         * ==========================
         */
        $this->containerExportModel->where('id_ws', $id)->delete();

        $noContainer = $this->request->getPost('no_container');
        $ukuran      = $this->request->getPost('ukuran');
        $tipe        = $this->request->getPost('tipe');

        if ($merged['jenis_con'] === 'FCL' && !empty($noContainer)) {
            $hasContainer = false;

            foreach ($noContainer as $i => $no) {
                if (!empty(trim($no))) {
                    $this->containerExportModel->insert([
                        'id_ws'        => $id,
                        'no_container' => trim($no),
                        'ukuran'       => $ukuran[$i] ?? null,
                        'tipe'         => $tipe[$i] ?? null,
                        'created_at'   => date('Y-m-d H:i:s')
                    ]);
                    $hasContainer = true;
                }
            }

            if (!$hasContainer) {
                $allFilled = false;
            }
        }

        /**
         * ==========================
         * TRUCKING EXPORT
         * ==========================
         */
        $this->truckingExportModel->where('id_ws', $id)->delete();

        $jenisTrucking = $this->request->getPost('jenis_trucking');
        $noMobil       = $this->request->getPost('no_mobil');
        $tipeMobil     = $this->request->getPost('tipe_mobil');
        $namaSupir     = $this->request->getPost('nama_supir');
        $alamat        = $this->request->getPost('alamat');
        $telpSupir     = $this->request->getPost('telp_supir');

        if ($jenisTrucking === 'Pengurusan Trucking' && !empty($noMobil)) {
            $hasTrucking = false;

            foreach ($noMobil as $i => $nopol) {
                if (!empty(trim($nopol))) {
                    $this->truckingExportModel->insert([
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
        }

        /**
         * ==========================
         * DO EXPORT
         * ==========================
         */
        $this->doExportModel->where('id_ws', $id)->delete();

        $tipeDo      = $this->request->getPost('tipe_do');
        $pengambilDo = $this->request->getPost('pengambil_do');
        $tglMatiDo   = $this->request->getPost('tgl_mati_do');

        if ($merged['pengurusan_do'] === 'Pengambilan Delivery Order' && !empty($tipeDo)) {
            $hasDo = false;

            foreach ($tipeDo as $i => $tipe) {
                if (!empty(trim($tipe))) {
                    $this->doExportModel->insert([
                        'id_ws'        => $id,
                        'tipe_do'      => trim($tipe),
                        'pengambil_do' => $pengambilDo[$i] ?? null,
                        'tgl_mati_do'  => $tglMatiDo[$i] ?? null,
                        'created_at'   => date('Y-m-d H:i:s')
                    ]);
                    $hasDo = true;
                }
            }

            if (!$hasDo) {
                $allFilled = false;
            }
        }

        /**
         * ==========================
         * LARTAS EXPORT
         * ==========================
         */
        $this->lartasExportModel->where('id_ws', $id)->delete();

        $namaLartas = $this->request->getPost('nama_lartas');
        $noLartas   = $this->request->getPost('no_lartas');
        $tglLartas  = $this->request->getPost('tgl_lartas');

        if ($merged['pengurusan_lartas'] === 'Pembuatan Lartas' && !empty($namaLartas)) {
            $hasLartas = false;

            foreach ($namaLartas as $i => $nama) {
                if (!empty(trim($nama))) {
                    $this->lartasExportModel->insert([
                        'id_ws'        => $id,
                        'nama_lartas'  => trim($nama),
                        'no_lartas'    => $noLartas[$i] ?? null,
                        'tgl_lartas'   => $tglLartas[$i] ?? null,
                        'created_at'   => date('Y-m-d H:i:s')
                    ]);
                    $hasLartas = true;
                }
            }

            if (!$hasLartas) {
                $allFilled = false;
            }
        }

        /**
         * ==========================
         * FASILITAS EXPORT
         * ==========================
         */
        $this->fasilitasExportModel->where('id_ws', $id)->delete();

        $tipeFasilitas = $this->request->getPost('tipe_fasilitas');
        $namaFasilitas = $this->request->getPost('nama_fasilitas');
        $tglFasilitas  = $this->request->getPost('tgl_fasilitas');
        $noFasilitas   = $this->request->getPost('no_fasilitas');

        if ($merged['jenis_fasilitas'] !== 'Tidak Ada Fasilitas' && !empty($namaFasilitas)) {
            $hasFasilitas = false;

            foreach ($namaFasilitas as $i => $nama) {
                if (!empty(trim($nama))) {
                    $this->fasilitasExportModel->insert([
                        'id_ws'          => $id,
                        'tipe_fasilitas' => $tipeFasilitas[$i] ?? null,
                        'nama_fasilitas' => trim($nama),
                        'tgl_fasilitas'  => $tglFasilitas[$i] ?? null,
                        'no_fasilitas'   => $noFasilitas[$i] ?? null,
                        'created_at'     => date('Y-m-d H:i:s')
                    ]);
                    $hasFasilitas = true;
                }
            }

            if (!$hasFasilitas) {
                $allFilled = false;
            }
        }

        /**
         * ==========================
         * INFORMASI TAMBAHAN EXPORT
         * ==========================
         */
        $this->informasiTambahanExportModel->where('id_ws', $id)->delete();

        $namaPengurusan = $this->request->getPost('nama_pengurusan');
        $tglPengurusan  = $this->request->getPost('tgl_pengurusan');

        if ($merged['jenis_tambahan'] === 'Pengurusan Tambahan' && !empty($namaPengurusan)) {
            $hasTambahan = false;

            foreach ($namaPengurusan as $i => $nama) {
                if (!empty(trim($nama))) {
                    $this->informasiTambahanExportModel->insert([
                        'id_ws'           => $id,
                        'nama_pengurusan' => trim($nama),
                        'tgl_pengurusan'  => $tglPengurusan[$i] ?? null,
                        'created_at'      => date('Y-m-d H:i:s')
                    ]);
                    $hasTambahan = true;
                }
            }

            if (!$hasTambahan) {
                $allFilled = false;
            }
        }

        // Jangan ubah status di updateImport
        unset($data['status']);

        // Update worksheet_import TANPA menyentuh kolom status
        $this->exportModel->update($id, $data);

        session()->setFlashdata('success', 'Data worksheet export berhasil diperbarui.');
        return redirect()->to(base_url('worksheet') . '?type=export');
    }



    /**
     * ===========================================================
     * CEK KELENGKAPAN WORKSHEET EXPORT (MENYESUAIKAN CEK IMPORT)
     * ===========================================================
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
        // Cek field utama
        // ======================
        $requiredFields = [
            'no_ws'             => 'Nomor Worksheet',
            'no_aju'            => 'Nomor Pengajuan PEB',
            'tgl_aju'           => 'Tanggal Pengajuan PEB',
            'pengurusan_peb'    => 'Pengurusan PEB',
            'peb_nopen'         => 'Nomor Pendaftaran PEB',
            'tgl_nopen'         => 'Tanggal Pendaftaran PEB',
            'penjaluran'        => 'Pilih Penjaluran',
            'tgl_npe'           => 'Tanggal NPE',
            'shipper'           => 'Shipper',
            'consignee'         => 'Consignee',
            'vessel'            => 'Vessel',
            'no_voyage'         => 'Nomor Voyage',
            'pol'               => 'Port of Loading (POL)',
            'pod'               => 'Port of Discharge (POD)',
            'depo'              => 'Nama Depo',
            'stuffing'          => 'Tanggal Stuffing',
            'terminal'          => 'Lokasi Terminal',
            'closing'           => 'Tanggal Closing',
            'shipping_line'     => 'Shipping Line',
            'commodity'         => 'Commodity',
            'party'             => 'Party',
            'qty'               => 'Quantity',
            'dok_ori'           => 'Dokumen Original',
            'pengurusan_do'     => 'Pengurusan Delivery Order',
            'jenis_con'         => 'Jenis Container',
            'jenis_trucking'    => 'Pengurusan Trucking',
            'pengurusan_lartas' => 'Pengurusan Lartas',
            'jenis_fasilitas'   => 'Jenis Fasilitas',
            'jenis_tambahan'    => 'Jenis Tambahan',
            'net'               => 'Net Weight',
            'gross'             => 'Gross Weight',
            'bl'                => 'Bill of Lading (BL)',
            'tgl_bl'            => 'Tanggal BL',
            'no_invoice'        => 'Nomor Invoice',
            'tgl_invoice'       => 'Tanggal Invoice',
            'etd'               => 'ETD',
            'asuransi'          => 'Asuransi',
            'top'               => 'TOP',
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
        // Cek Container jika FCL
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
                    if (
                        empty($c['no_container']) ||
                        empty($c['ukuran']) ||
                        empty($c['tipe'])
                    ) {
                        $incomplete[] = [
                            'name'  => 'container_detail',
                            'label' => 'Beberapa kolom Container (No Container, Ukuran, atau Tipe) belum lengkap'
                        ];
                        break;
                    }
                }
            }
        }

        // ==============================
        // 3. Cek Trucking jika Pengurusan Trucking
        // ==============================
        if (($data['jenis_trucking'] ?? '') === 'Pengurusan Trucking') {

            $truckings = $this->truckingExportModel->where('id_ws', $id)->findAll();

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
                            'label' => 'Beberapa kolom Trucking (No Mobil, Tipe Mobil, Nama Supir, Alamat, Telp) belum lengkap'
                        ];
                        break;
                    }
                }
            }
        } else {
            // Jika trucking sendiri → hapus data trucking lama
            $this->truckingExportModel->where('id_ws', $id)->delete();
        }

        // ==============================
        // 4. Cek DO jika Pengurusan DO
        // ==============================
        if (($data['pengurusan_do'] ?? '') === 'Pengambilan DO') {

            $dos = $this->doExportModel->where('id_ws', $id)->findAll();

            if (empty($dos)) {
                $incomplete[] = [
                    'name'  => 'do',
                    'label' => 'Data DO wajib diisi untuk Pengambilan DO'
                ];
            } else {
                foreach ($dos as $d) {
                    if (
                        empty($d['tipe_do']) ||
                        empty($d['pengambil_do']) ||
                        empty($d['tgl_mati_do']) ||
                        $d['tgl_mati_do'] === '0000-00-00'
                    ) {
                        $incomplete[] = [
                            'name'  => 'do_detail',
                            'label' => 'Beberapa kolom DO (Tipe DO, Pengambil DO, Tanggal Mati DO) belum lengkap'
                        ];
                        break;
                    }
                }
            }
        } else {
            // DO sendiri → hapus data lama
            $this->doExportModel->where('id_ws', $id)->delete();
        }

        // ==============================
        // 5. Cek Lartas jika Pembuatan Lartas
        // ==============================
        if (($data['pengurusan_lartas'] ?? '') === 'Pembuatan Lartas') {

            $lartas = $this->lartasExportModel->where('id_ws', $id)->findAll();

            if (empty($lartas)) {
                $incomplete[] = [
                    'name'  => 'lartas',
                    'label' => 'Data Lartas wajib diisi untuk Pembuatan Lartas'
                ];
            } else {
                foreach ($lartas as $l) {
                    if (
                        empty($l['nama_lartas']) ||
                        empty($l['no_lartas']) ||
                        empty($l['tgl_lartas']) ||
                        $l['tgl_lartas'] === '0000-00-00'
                    ) {
                        $incomplete[] = [
                            'name'  => 'lartas_detail',
                            'label' => 'Beberapa kolom Lartas (Nama, Nomor, Tanggal) belum lengkap'
                        ];
                        break;
                    }
                }
            }
        } else {
            // lartas sendiri → hapus data lama
            $this->lartasExportModel->where('id_ws', $id)->delete();
        }

        // ==============================
        // 6. Cek Fasilitas jika Pengurusan Fasilitas atau Fasilitas Sendiri
        // ==============================
        if (
            ($data['jenis_fasilitas'] ?? '') === 'Pengurusan Fasilitas' ||
            ($data['jenis_fasilitas'] ?? '') === 'Fasilitas Sendiri'
        ) {

            $fasilitas = $this->fasilitasExportModel->where('id_ws', $id)->findAll();

            if (empty($fasilitas)) {
                $incomplete[] = [
                    'name'  => 'fasilitas',
                    'label' => 'Data Fasilitas wajib diisi'
                ];
            } else {
                foreach ($fasilitas as $f) {
                    if (
                        empty($f['tipe_fasilitas']) ||
                        empty($f['nama_fasilitas']) ||
                        empty($f['tgl_fasilitas']) ||
                        empty($f['no_fasilitas']) ||
                        $f['tgl_fasilitas'] === '0000-00-00'
                    ) {
                        $incomplete[] = [
                            'name'  => 'fasilitas_detail',
                            'label' => 'Beberapa kolom Fasilitas (Tipe, Nama, Nomor Fasilitas) belum lengkap'
                        ];
                        break;
                    }
                }
            }
        } else {
            $this->fasilitasExportModel->where('id_ws', $id)->delete();
        }

        // ==============================
        // 7. Cek Informasi Tambahan
        // ==============================
        if (($data['jenis_tambahan'] ?? '') === 'Pengurusan Tambahan') {

            $tambahans = $this->informasiTambahanExportModel->where('id_ws', $id)->findAll();

            if (empty($tambahans)) {
                $incomplete[] = [
                    'name'  => 'informasi_tambahan',
                    'label' => 'Data Informasi Tambahan wajib diisi'
                ];
            } else {
                foreach ($tambahans as $t) {
                    if (
                        empty($t['nama_pengurusan']) ||
                        empty($t['tgl_pengurusan']) ||
                        $t['tgl_pengurusan'] === '0000-00-00'
                    ) {
                        $incomplete[] = [
                            'name'  => 'informasi_tambahan_detail',
                            'label' => 'Beberapa kolom Informasi Tambahan belum lengkap'
                        ];
                        break;
                    }
                }
            }
        } else {
            $this->informasiTambahanExportModel->where('id_ws', $id)->delete();
        }

        // ==============================
        // HASIL AKHIR
        // ==============================
        if (empty($incomplete)) {

            $this->exportModel->update($id, [
                'status' => 'completed'
            ]);

            return $this->response->setJSON([
                'status'  => 'complete',
                'message' => 'Semua data worksheet export sudah lengkap.'
            ]);
        }

        $this->exportModel->update($id, [
            'status' => 'not completed'
        ]);

        return $this->response->setJSON([
            'status'         => 'incomplete',
            'message'        => 'Beberapa data worksheet export belum diisi.',
            'missing_fields' => $incomplete
        ]);
    }

    /**
     * PRINT WORKSHEET EXPORT
     */
    public function printExport($encoded_id)
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

        // ==============
        // GET DATA
        // ==============
        $ws = $this->exportModel->find($id);

        if (!$ws) {
            return redirect()->back()->with('error', 'Worksheet tidak ditemukan.');
        }

        // ==============
        // RELATIONS
        // ==============
        $container  = $this->containerExportModel->getByWorksheet($id);
        $trucking   = $this->truckingExportModel->getByWorksheet($id);
        $do         = $this->doExportModel->where('id_ws', $id)->findAll();
        $lartas     = $this->lartasExportModel->getByWorksheet($id);
        $tambahan   = $this->informasiTambahanExportModel->getByWorksheet($id);
        $fasilitas  = $this->fasilitasExportModel->getByWorksheet($id);

        // ==============
        // VIEW
        // ==============
        $html = view('worksheet/print_export', [
            'ws'        => $ws,
            'container' => $container,
            'trucking'  => $trucking,
            'do'        => $do,
            'lartas'    => $lartas,
            'fasilitas' => $fasilitas,
            'tambahan'  => $tambahan,
        ]);

        // ==============
        // PDF SETUP
        // ==============
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);

        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $filename = 'Worksheet_Export_' . $ws['no_ws'] . '.pdf';

        return $dompdf->stream($filename, ["Attachment" => false]);
    }

    // ============================================================
    // HAPUS WORKSHEET EXPORT + PINDAHKAN SEMUA RELASI KE TRASH
    // ============================================================
    public function deleteExport($id)
    {
        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // EXPORT trash models
            $trashMain      = new \App\Models\WorksheetExportTrash\WorkSheetExportTrashModel();
            $trashContainer = new \App\Models\WorksheetExportTrash\WorksheetContainerExportTrashModel();
            $trashDo        = new \App\Models\WorksheetExportTrash\WorksheetDoExportTrashModel();
            $trashFasilitas = new \App\Models\WorksheetExportTrash\WorksheetFasilitasExportTrashModel();
            $trashTruck     = new \App\Models\WorksheetExportTrash\WorksheetTruckingExportTrashModel();
            $trashLartas    = new \App\Models\WorksheetExportTrash\WorksheetLartasExportTrashModel();
            $trashInfo      = new \App\Models\WorksheetExportTrash\WorksheetInformasiTambahanExportTrashModel();

            // Ambil worksheet export utama
            $row = $this->exportModel->find($id);
            if (!$row) {
                $db->transComplete();
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Worksheet export tidak ditemukan.'
                ])->setStatusCode(404);
            }

            // who deleted
            $deletedBy = null;
            if (function_exists('user') && user()) {
                $deletedBy = user()->username ?? user()->email ?? null;
            }
            if (empty($deletedBy)) {
                $session = session();
                $deletedBy = $session->get('username') ?? $session->get('email') ?? 'system';
            }

            $now = date('Y-m-d H:i:s');

            // Siapkan parent untuk trash
            $parentCopy = $row;
            $parentCopy['deleted_at'] = $now;
            $parentCopy['deleted_by'] = $deletedBy;

            // unset id supaya tidak bentrok PK dengan tabel trash (trash autonumber sendiri)
            unset($parentCopy['id']);

            // Insert ke trash parent
            $newTrashId = $trashMain->insert($parentCopy);
            if (!$newTrashId) {
                // gagal insert ke trash
                $db->transComplete();
                log_message('error', 'Gagal insert worksheet export ke trash. original id=' . $id);
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Gagal memindahkan worksheet export ke trash.'
                ])->setStatusCode(500);
            }

            // Helper untuk copy relasi
            $copyToTrash = function ($dataList, $trashModel) use ($newTrashId, $deletedBy, $now) {
                foreach ($dataList as $d) {
                    if (isset($d['id'])) unset($d['id']);
                    $d['id_ws'] = $newTrashId;
                    $d['deleted_at'] = $now;
                    $d['deleted_by'] = $deletedBy;
                    $trashModel->insert($d);
                }
            };

            // Ambil dan copy semua child -> ke trash child, kemudian delete dari main
            // CONTAINER
            $containers = $this->containerExportModel->where('id_ws', $id)->findAll();
            if (!empty($containers)) {
                $copyToTrash($containers, $trashContainer);
                $this->containerExportModel->where('id_ws', $id)->delete();
            }

            // DO
            $dos = $this->doExportModel->where('id_ws', $id)->findAll();
            if (!empty($dos)) {
                $copyToTrash($dos, $trashDo);
                $this->doExportModel->where('id_ws', $id)->delete();
            }

            // FASILITAS
            $fas = $this->fasilitasExportModel->where('id_ws', $id)->findAll();
            if (!empty($fas)) {
                $copyToTrash($fas, $trashFasilitas);
                $this->fasilitasExportModel->where('id_ws', $id)->delete();
            }

            // TRUCKING
            $trucks = $this->truckingExportModel->where('id_ws', $id)->findAll();
            if (!empty($trucks)) {
                $copyToTrash($trucks, $trashTruck);
                $this->truckingExportModel->where('id_ws', $id)->delete();
            }

            // LARTAS
            $lartas = $this->lartasExportModel->where('id_ws', $id)->findAll();
            if (!empty($lartas)) {
                $copyToTrash($lartas, $trashLartas);
                $this->lartasExportModel->where('id_ws', $id)->delete();
            }

            // INFORMASI TAMBAHAN
            $infos = $this->informasiTambahanExportModel->where('id_ws', $id)->findAll();
            if (!empty($infos)) {
                $copyToTrash($infos, $trashInfo);
                $this->informasiTambahanExportModel->where('id_ws', $id)->delete();
            }

            // Hapus parent dari tabel utama
            $this->exportModel->delete($id);

            $db->transComplete();

            if ($db->transStatus() === false) {
                log_message('error', 'Transaksi moveToTrash gagal untuk worksheet export id=' . $id);
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Gagal memindahkan worksheet export ke trash (transaksi gagal).'
                ])->setStatusCode(500);
            }

            return $this->response->setJSON([
                'status' => 'ok',
                'message' => 'Worksheet export berhasil dihapus.'
            ]);

        } catch (\Throwable $e) {
            $db->transComplete();
            log_message('error', 'Worksheet Export delete error: ' . $e->getMessage());

            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat menghapus worksheet export.'
            ])->setStatusCode(500);
        }
    }

    

}
