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
        $model = new MasterLartasModel();
        $data['lartas'] = $model->orderBy('id', 'DESC')->findAll();

        return view('master/lartas/index', $data);
    }

    // ============================================================
    // STORE — SIMPAN DATA BARU
    // ============================================================
    public function store()
    {
        $validation = \Config\Services::validation();

        $rules = [
            'kode'       => 'required|min_length[1]|max_length[20]|is_unique[master_lartas.kode]',
            'nama_lartas' => 'required|min_length[2]',
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
            'kode'       => "required|min_length[1]|max_length[20]|is_unique[master_lartas.kode,id,{$id}]",
            'nama_lartas' => 'required|min_length[2]',
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
