<?php

namespace App\Controllers;

use App\Models\Master\MasterPortModel;
use CodeIgniter\Controller;

class MasterPort extends Controller
{
    // ============================================================
    // VIEW INDEX
    // ============================================================
    public function index()
    {
        return view('master/port/index');
    }

    // ===============================
    // LIST DATA (AJAX)
    // ===============================
    public function list()
    {
        $model = new MasterPortModel();

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
            'kode'         => 'required|min_length[4]|max_length[6]|is_unique[master_port.kode]',
            'nama_port'    => 'required|min_length[3]',
            'negara_port'  => 'required|min_length[3]'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'status' => 'error',
                'errors' => $validation->getErrors()
            ]);
        }

        $model = new MasterPortModel();

        $model->save([
            'kode'        => $this->request->getPost('kode'),
            'nama_port'   => $this->request->getPost('nama_port'),
            'negara_port' => $this->request->getPost('negara_port'),
        ]);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Data port berhasil ditambahkan!'
        ]);
    }

    // ============================================================
    // EDIT (GET DATA)
    // ============================================================
    public function edit($id)
    {
        $model = new MasterPortModel();
        $data = $model->find($id);

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

        $model = new MasterPortModel();
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

        $model = new MasterPortModel();
        $data  = $model->select('nama_port')
                       ->like('nama_port', $q)
                       ->limit(10)
                       ->findAll();

        $results = [];

        foreach ($data as $row) {
            $results[] = [
                'id'     => $row['nama_port'],
                'text'   => $row['nama_port'],
                'exists' => true
            ];
        }

        return $this->response->setJSON($results);
    }

    // ===============================
    // SEARCH NEGARA (SELECT2)
    // ===============================
    public function searchNegara()
    {
        $q = $this->request->getGet('term');

        $model = new MasterPortModel();

        $data = $model->distinct()
                    ->select('negara_port')
                    ->like('negara_port', $q)
                    ->orderBy('negara_port', 'ASC')
                    ->limit(16)
                    ->findAll();

        $results = [];

        foreach ($data as $row) {
            $results[] = [
                'id'   => $row['negara_port'],
                'text' => $row['negara_port']
            ];
        }

        return $this->response->setJSON($results);
    }



    // ============================================================
    // UPDATE DATA
    // ============================================================
    public function update($id)
    {
        $model = new MasterPortModel();
        $existing = $model->find($id);

        if (!$existing) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Data tidak ditemukan'
            ]);
        }

        $rules = [
            'kode'         => "required|min_length[4]|max_length[6]|is_unique[master_port.kode,id,{$id}]",
            'nama_port'    => 'required|min_length[3]',
            'negara_port'  => 'required|min_length[3]',
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'status' => 'error',
                'errors' => \Config\Services::validation()->getErrors()
            ]);
        }

        $model->update($id, [
            'kode'        => $this->request->getPost('kode'),
            'nama_port'   => $this->request->getPost('nama_port'),
            'negara_port' => $this->request->getPost('negara_port'),
        ]);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Data port berhasil diperbarui!'
        ]);
    }

    // ============================================================
    // DELETE
    // ============================================================
    public function delete($id)
    {
        $model = new MasterPortModel();

        if (!$model->find($id)) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Data tidak ditemukan'
            ]);
        }

        $model->delete($id);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Data berhasil dihapus!'
        ]);
    }
}
