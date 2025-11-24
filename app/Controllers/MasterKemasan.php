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
        $model = new MasterKemasanModel();
        $data['kemasan'] = $model->orderBy('id', 'DESC')->findAll();

        return view('master/kemasan/index', $data);
    }

    // ============================================================
    // STORE — SIMPAN DATA BARU
    // ============================================================
    public function store()
    {
        $validation = \Config\Services::validation();

        $rules = [
            'kode'          => 'required|min_length[1]|max_length[20]|is_unique[master_kemasan.kode]',
            'jenis_kemasan' => 'required|min_length[2]',
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
            'kode'          => "required|min_length[1]|max_length[20]|is_unique[master_kemasan.kode,id,{$id}]",
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
