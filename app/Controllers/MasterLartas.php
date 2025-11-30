<?php

namespace App\Controllers;

use App\Models\Master\MasterLartasModel;
use CodeIgniter\Controller;

class MasterLartas extends Controller
{
    // ============================================================
    // INDEX — TAMPIL LIST
    // ============================================================
    public function index()
    {
        return view('master/lartas/index');
    }

    // ===============================
    // LIST DATA (AJAX)
    // ===============================
    public function list()
    {
        $model = new MasterLartasModel();

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
            'kode'       => 'required|min_length[2]|max_length[6]|is_unique[master_lartas.kode]',
            'nama_lartas' => 'required|min_length[5]',
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'status' => 'error',
                'errors' => $validation->getErrors()
            ]);
        }

        $model = new MasterLartasModel();
        $model->save([
            'kode'        => $this->request->getPost('kode'),
            'nama_lartas' => $this->request->getPost('nama_lartas'),
        ]);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Data Lartas berhasil ditambahkan!'
        ]);
    }

    // ============================================================
    // EDIT — GET DATA BY ID
    // ============================================================
    public function edit($id)
    {
        $model = new MasterLartasModel();
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

        $model = new MasterLartasModel();
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

        $model = new MasterLartasModel();
        $data  = $model->select('nama_lartas')
            ->like('nama_lartas', $q)
            ->limit(10)
            ->findAll();

        $results = [];

        foreach ($data as $row) {
            $results[] = [
                'id'     => $row['nama_lartas'],
                'text'   => $row['nama_lartas'],
                'exists' => true
            ];
        }

        return $this->response->setJSON($results);
    }

    // ============================================================
    // UPDATE — SIMPAN PERUBAHAN
    // ============================================================
    public function update($id)
    {
        $model = new MasterLartasModel();
        $existing = $model->find($id);

        if (!$existing) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Data tidak ditemukan!'
            ]);
        }

        $rules = [
            'kode'       => "required|min_length[2]|max_length[6]|is_unique[master_lartas.kode,id,{$id}]",
            'nama_lartas' => 'required|min_length[5]',
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'status' => 'error',
                'errors' => \Config\Services::validation()->getErrors()
            ]);
        }

        $model->update($id, [
            'kode'        => $this->request->getPost('kode'),
            'nama_lartas' => $this->request->getPost('nama_lartas'),
        ]);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Data Lartas berhasil diperbarui!'
        ]);
    }

    // ============================================================
    // DELETE — HAPUS DATA
    // ============================================================
    public function delete($id)
    {
        $model = new MasterLartasModel();

        if (!$model->find($id)) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Data tidak ditemukan!'
            ]);
        }

        $model->delete($id);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Data Lartas berhasil dihapus!'
        ]);
    }
}
