<?php

namespace App\Controllers;

use App\Models\Master\MasterKemasanModel;
use CodeIgniter\Controller;

class MasterKemasan extends Controller
{
    // ============================================================
    // INDEX — TAMPIL LIST
    // ============================================================
    public function index()
    {
        return view('master/kemasan/index');
    }

    // ===============================
    // LIST DATA (AJAX)
    // ===============================
    public function list()
    {
        $model = new MasterKemasanModel();

        return $this->response->setJSON([
            'data' => $model->orderBy('id', 'ASC')->findAll()
        ]);
    }

    // ============================================================
    // STORE — SIMPAN DATA BARU
    // ============================================================
    public function store()
    {
        $validation = \Config\Services::validation();

        $rules = [
            'kode'          => 'required|min_length[2]|max_length[6]|is_unique[master_kemasan.kode]',
            'jenis_kemasan' => 'required|min_length[2]'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'status' => 'error',
                'errors' => $validation->getErrors()
            ]);
        }

        $model = new MasterKemasanModel();
        $model->save([
            'kode'          => $this->request->getPost('kode'),
            'jenis_kemasan' => $this->request->getPost('jenis_kemasan'),
        ]);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Data kemasan berhasil ditambahkan!'
        ]);
    }

    // ============================================================
    // EDIT — GET DATA BY ID
    // ============================================================
    public function edit($id)
    {
        $model = new MasterKemasanModel();
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

        $model = new MasterKemasanModel();
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

        $model = new MasterKemasanModel();
        $data  = $model->select('jenis_kemasan')
            ->like('jenis_kemasan', $q)
            ->limit(10)
            ->findAll();

        $results = [];

        foreach ($data as $row) {
            $results[] = [
                'id'     => $row['jenis_kemasan'],
                'text'   => $row['jenis_kemasan'],
                'exists' => true
            ];
        }

        return $this->response->setJSON($results);
    }

    // ============================================================
    // UPDATE — SIMPAN PERUBAHAN DATA
    // ============================================================
    public function update($id)
    {
        $model    = new MasterKemasanModel();
        $existing = $model->find($id);

        if (!$existing) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Data tidak ditemukan'
            ]);
        }

        // validasi update
        $rules = [
            'kode'          => "required|min_length[2]|max_length[6]|is_unique[master_kemasan.kode,id,{$id}]",
            'jenis_kemasan' => 'required|min_length[2]',
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'status' => 'error',
                'errors' => \Config\Services::validation()->getErrors()
            ]);
        }

        $model->update($id, [
            'kode'          => $this->request->getPost('kode'),
            'jenis_kemasan' => $this->request->getPost('jenis_kemasan'),
        ]);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Data kemasan berhasil diperbarui!'
        ]);
    }

    // ============================================================
    // DELETE — HAPUS DATA
    // ============================================================
    public function delete($id)
    {
        $model = new MasterKemasanModel();

        if (!$model->find($id)) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Data tidak ditemukan'
            ]);
        }

        $model->delete($id);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Data kemasan berhasil dihapus!'
        ]);
    }
}
