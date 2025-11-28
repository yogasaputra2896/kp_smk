<?php

namespace App\Controllers;

use App\Models\Master\MasterVesselModel;
use CodeIgniter\Controller;

class MasterVessel extends Controller
{
    // ============================================================
    // VIEW INDEX
    // ============================================================
    public function index()
    {
        return view('master/vessel/index');
    }

    // ===============================
    // LIST DATA (AJAX)
    // ===============================
    public function list()
    {
        $model = new MasterVesselModel();

        return $this->response->setJSON([
            'data' => $model->orderBy('id', 'ASC')->findAll()
        ]);
    }

    // ============================================================
    // STORE DATA
    // ============================================================
    public function store()
    {
        $validation = \Config\Services::validation();

        $rules = [
            'kode'         => 'required|min_length[4]|max_length[6]|is_unique[master_vessel.kode]',
            'nama_vessel'  => 'required|min_length[3]',
            'negara_vessel'=> 'required|min_length[3]'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'status' => 'error',
                'errors' => $validation->getErrors(),
            ]);
        }

        $model = new MasterVesselModel();

        $model->save([
            'kode'          => $this->request->getPost('kode'),
            'nama_vessel'   => $this->request->getPost('nama_vessel'),
            'negara_vessel' => $this->request->getPost('negara_vessel'),
        ]);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Data Vessel berhasil ditambahkan!'
        ]);
    }

    // ============================================================
    // EDIT (GET DATA)
    // ============================================================
    public function edit($id)
    {
        $model = new MasterVesselModel();
        $data  = $model->find($id);

        if (!$data) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Data tidak ditemukan'
            ]);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => $data
        ]);
    }

    // ===============================
    // SEARCH KODE (SELECT2)
    // ===============================
    public function searchKode()
    {
        $q = $this->request->getGet('term');

        $model = new MasterVesselModel();
        $data  = $model->select('kode')
                       ->like('kode', $q)
                       ->limit(10)
                       ->findAll();

        $results = [];

        foreach ($data as $row) {
            $results[] = [
                'id'     => $row['kode'],
                'text'   => $row['kode'],
                'exists' => true
            ];
        }

        return $this->response->setJSON($results);
    }

    // ===============================
    // SEARCH NAMA (SELECT2)
    // ===============================
    public function searchNama()
    {
        $q = $this->request->getGet('term');

        $model = new MasterVesselModel();
        $data  = $model->select('nama_vessel')
                       ->like('nama_vessel', $q)
                       ->limit(10)
                       ->findAll();

        $results = [];

        foreach ($data as $row) {
            $results[] = [
                'id'     => $row['nama_vessel'],
                'text'   => $row['nama_vessel'],
                'exists' => true
            ];
        }

        return $this->response->setJSON($results);
    }

    // ===============================
    // SEARCH NEGARA  (SELECT2)
    // ===============================
    public function searchNegara()
    {
        $q = $this->request->getGet('term');

        $model = new MasterVesselModel();

        $data = $model->distinct()
                    ->select('negara_vessel')
                    ->like('negara_vessel', $q)
                    ->orderBy('negara_vessel', 'ASC')
                    ->limit(16)
                    ->findAll();

        $results = [];

        foreach ($data as $row) {
            $results[] = [
                'id'   => $row['negara_vessel'],
                'text' => $row['negara_vessel']
            ];
        }

        return $this->response->setJSON($results);
    }

    // ============================================================
    // UPDATE DATA
    // ============================================================
    public function update($id)
    {
        $model    = new MasterVesselModel();
        $existing = $model->find($id);

        if (!$existing) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Data tidak ditemukan'
            ]);
        }

        // validasi
        $rules = [
            'kode'         => "required|min_length[4]|max_length[6]|is_unique[master_vessel.kode,id,{$id}]",
            'nama_vessel'  => 'required|min_length[3]',
            'negara_vessel'=> 'required|min_length[3]'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'status' => 'error',
                'errors' => \Config\Services::validation()->getErrors()
            ]);
        }

        $model->update($id, [
            'kode'          => $this->request->getPost('kode'),
            'nama_vessel'   => $this->request->getPost('nama_vessel'),
            'negara_vessel' => $this->request->getPost('negara_vessel'),
        ]);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Data Vessel berhasil diperbarui!'
        ]);
    }

    // ============================================================
    // DELETE 
    // ============================================================
    public function delete($id)
    {
        $model = new MasterVesselModel();

        if (!$model->find($id)) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Data tidak ditemukan'
            ]);
        }

        $model->delete($id);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Data Vessel berhasil dihapus!'
        ]);
    }
}
