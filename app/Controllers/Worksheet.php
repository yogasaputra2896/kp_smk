<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\WorkSheetImportModel;
use App\Models\WorkSheetExportModel;
use App\Models\WorksheetContainerModel;

class Worksheet extends BaseController
{
    protected $importModel;
    protected $exportModel;
    protected $containerModel;

    public function __construct()
    {
        $this->importModel = new WorkSheetImportModel();
        $this->exportModel = new WorkSheetExportModel();
        $this->containerModel = new WorksheetContainerModel();
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
                    'id'           => $r['id'],
                    'no_ws'        => $r['no_ws'],
                    'no_aju'       => $r['no_aju'], // No PEB
                    'shipper'      => $r['shipper'],
                    'party'        => $r['party'],
                    'etd'          => $r['etd'],
                    'pod'          => $r['pod'],
                    'bl'           => $r['bl'],
                    'master_bl'    => $r['master_bl'],
                    'shipping_line' => $r['shipping_line'],
                    'status'       => $r['status'] ?? 'not completed',
                ];
            }

            return $this->response->setJSON(['data' => $data]);
        }

        // default import
        $rows = $this->importModel->orderBy('no_ws', 'ASC')->findAll();
        $data = [];

        foreach ($rows as $r) {
            $data[] = [
                'id'           => $r['id'],
                'no_ws'        => $r['no_ws'],
                'no_aju'       => $r['no_aju'], // No PIB
                'consignee'    => $r['consignee'],
                'party'        => $r['party'],
                'eta'          => $r['eta'],
                'pol'          => $r['pol'],
                'bl'           => $r['bl'],
                'master_bl'    => $r['master_bl'],
                'shipping_line' => $r['shipping_line'],
                'status'       => $r['status'] ?? 'not completed',
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

        // Ambil data kontainer (jika ada)
        $containers = $this->containerModel->where('id_ws', $id)->findAll();

        return view('worksheet/edit_import', [
            'worksheet'  => $worksheet,
            'containers' => $containers
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
            'no_ws'         => $this->request->getPost('no_ws'),
            'no_aju'        => $this->request->getPost('no_aju'),
            'tgl_aju'       => $this->request->getPost('tgl_aju'),
            'no_po'         => $this->request->getPost('no_po'),
            'io_number'     => $this->request->getPost('io_number'),
            'pib_nopen'     => $this->request->getPost('pib_nopen'),
            'tgl_nopen'     => $this->request->getPost('tgl_nopen'),
            'tgl_sppb'      => $this->request->getPost('tgl_sppb'),
            'shipper'       => $this->request->getPost('shipper'),
            'consignee'     => $this->request->getPost('consignee'),
            'notify_party'  => $this->request->getPost('notify_party'),
            'vessel'        => $this->request->getPost('vessel'),
            'no_voyage'     => $this->request->getPost('no_voyage'),
            'pol'           => $this->request->getPost('pol'),
            'terminal'      => $this->request->getPost('terminal'),
            'shipping_line' => $this->request->getPost('shipping_line'),
            'commodity'     => $this->request->getPost('commodity'),
            'party'         => $this->request->getPost('party'),
            'jenis_con'     => $this->request->getPost('jenis_con'), // VARCHAR FCL/LCL
            'qty'           => $this->request->getPost('qty'),
            'kemasan'       => $this->request->getPost('kemasan'),
            'net'           => $this->request->getPost('net'),
            'gross'         => $this->request->getPost('gross'),
            'bl'            => $this->request->getPost('bl'),
            'tgl_bl'        => $this->request->getPost('tgl_bl'),
            'master_bl'     => $this->request->getPost('master_bl'),
            'tgl_master'    => $this->request->getPost('tgl_master'),
            'no_invoice'    => $this->request->getPost('no_invoice'),
            'tgl_invoice'   => $this->request->getPost('tgl_invoice'),
            'eta'           => $this->request->getPost('eta'),
            'do'            => $this->request->getPost('do'),
            'tgl_mati_do'   => $this->request->getPost('tgl_mati_do'),
            'asuransi'      => $this->request->getPost('asuransi'),
            'top'           => $this->request->getPost('top'),
            'berita_acara'  => $this->request->getPost('berita_acara'),
            'updated_at'    => date('Y-m-d H:i:s')
        ];

        $data = array_filter($data, fn($v) => $v !== null);

        // Ambil data lama
        $existing = $this->importModel->find($id);
        $merged   = array_merge($existing, $data);

        // Field wajib untuk status "completed"
        $requiredFields = [
            'no_ws', 'no_aju', 'tgl_aju', 'no_po', 'pib_nopen', 'tgl_nopen',
            'tgl_sppb', 'shipper', 'consignee', 'notify_party', 'vessel',
            'no_voyage', 'pol', 'terminal', 'shipping_line', 'commodity',
            'party', 'qty', 'kemasan', 'net', 'gross', 'bl', 'tgl_bl',
            'master_bl', 'tgl_master', 'no_invoice', 'tgl_invoice', 'eta',
            'do', 'tgl_mati_do', 'asuransi', 'top', 'berita_acara'
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
         * SIMPAN DATA CONTAINER
         * ==========================
         */
        $containerModel = new \App\Models\WorkSheetContainerModel();
        $jenisCon = $this->request->getPost('jenis_con');
        $noContainer = $this->request->getPost('no_container');
        $tipe = $this->request->getPost('tipe');

        // Hapus data lama agar tidak duplikat
        $containerModel->where('id_ws', $id)->delete();

        // Jika FCL → wajib simpan container
        if ($jenisCon === 'FCL' && !empty($noContainer)) {
            $hasContainer = false;

            foreach ($noContainer as $i => $no) {
                if (!empty(trim($no))) {
                    $containerModel->insert([
                        'id_ws'        => $id,
                        'no_container' => trim($no),
                        'tipe'         => isset($tipe[$i]) ? trim($tipe[$i]) : null,
                        'created_at'   => date('Y-m-d H:i:s')
                    ]);
                    $hasContainer = true;
                }
            }

            if (!$hasContainer) {
                $allFilled = false; // Tidak ada container valid diinput
            }
        }

        // Jika LCL → tidak perlu isi kontainer (skip)
        // Jika FCL tapi kosong → otomatis status "not completed"

        $data['status'] = $allFilled ? 'completed' : 'not completed';

        // Update ke database utama
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
            'no_ws'         => 'Nomor Worksheet',
            'no_aju'        => 'Nomor AJU',
            'tgl_aju'       => 'Tanggal AJU',
            'no_po'         => 'Nomor PO',
            'pib_nopen'     => 'Nomor PIB / Nopen',
            'tgl_nopen'     => 'Tanggal Nopen',
            'tgl_sppb'      => 'Tanggal SPPB',
            'shipper'       => 'Shipper',
            'consignee'     => 'Consignee',
            'notify_party'  => 'Notify Party',
            'vessel'        => 'Vessel',
            'no_voyage'     => 'Nomor Voyage',
            'pol'           => 'Port of Loading (POL)',
            'terminal'      => 'Terminal',
            'shipping_line' => 'Shipping Line',
            'commodity'     => 'Commodity',
            'party'         => 'Party',
            'qty'           => 'Quantity',
            'kemasan'       => 'Kemasan',
            'net'           => 'Net Weight',
            'gross'         => 'Gross Weight',
            'bl'            => 'Bill of Lading (BL)',
            'tgl_bl'        => 'Tanggal BL',
            'master_bl'     => 'Master BL',
            'tgl_master'    => 'Tanggal Master BL',
            'no_invoice'    => 'Nomor Invoice',
            'tgl_invoice'   => 'Tanggal Invoice',
            'eta'           => 'ETA',
            'do'            => 'Delivery Order (DO)',
            'tgl_mati_do'   => 'Tanggal Mati DO',
            'asuransi'      => 'Asuransi',
            'top'           => 'TOP',
            'berita_acara'  => 'Berita Acara'
        ];

        $incomplete = [];
        foreach ($requiredFields as $field => $label) {
            if (empty($data[$field])) {
                $incomplete[] = [
                    'name'  => $field,
                    'label' => $label
                ];
            }
        }

        // Cek container jika FCL
        if ($data['jenis_con'] === 'FCL') {
            $containerModel = new \App\Models\WorkSheetContainerModel();
            $containers = $containerModel->where('id_ws', $id)->countAllResults();
            if ($containers == 0) {
                $incomplete[] = [
                    'name'  => 'container',
                    'label' => 'Data Kontainer Belum Diisi'
                ];
            }
        }

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
