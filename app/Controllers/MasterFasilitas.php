<?php

namespace App\Controllers;

use App\Models\Master\MasterFasilitasModel;
use CodeIgniter\Controller;

class MasterFasilitas extends Controller
{
    // ============================================================
    // INDEX — LIST DATA
    // ============================================================
    public function index()
    {
        $model = new MasterFasilitasModel();
        $data['fasilitas'] = $model->orderBy('id', 'DESC')->findAll();

        return view('master/fasilitas/index', $data);
    }

    // ============================================================
    // STORE — TAMBAH DATA
    // ============================================================
    public function store()
    {
        $validation = \Config\Services::validation();

        $rules = [
            'kode'          => 'required|min_length[1]|max_length[20]|is_unique[master_fasilitas.kode]',
            'tipe_fasilitas' => 'required|min_length[2]',
            'nama_fasilitas' => 'required|min_length[2]',
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'status' => 'error',
                'errors' => $validation->getErrors()
            ]);
        }

        $model = new MasterFasilitasModel();
        $model->save([
            'kode'           => $this->request->getPost('kode'),
            'tipe_fasilitas' => $this->request->getPost('tipe_fasilitas'),
            'nama_fasilitas' => $this->request->getPost('nama_fasilitas'),
        ]);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Data fasilitas berhasil ditambahkan!'
        ]);
    }

    // ============================================================
    // EDIT — GET DATA BY ID
    // ============================================================
    public function edit($id)
    {
        $model = new MasterFasilitasModel();
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
        $model = new MasterFasilitasModel();
        $existing = $model->find($id);

        if (!$existing) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Data tidak ditemukan!'
            ]);
        }

        $rules = [
            'kode'           => "required|min_length[1]|max_length[20]|is_unique[master_fasilitas.kode,id,{$id}]",
            'tipe_fasilitas' => 'required|min_length[2]',
            'nama_fasilitas' => 'required|min_length[2]',
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'status' => 'error',
                'errors' => \Config\Services::validation()->getErrors()
            ]);
        }

        $model->update($id, [
            'kode'           => $this->request->getPost('kode'),
            'tipe_fasilitas' => $this->request->getPost('tipe_fasilitas'),
            'nama_fasilitas' => $this->request->getPost('nama_fasilitas'),
        ]);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Data fasilitas berhasil diperbarui!'
        ]);
    }

    // ============================================================
    // DELETE — HAPUS DATA
    // ============================================================
    public function delete($id)
    {
        $model = new MasterFasilitasModel();

        if (!$model->find($id)) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Data tidak ditemukan!'
            ]);
        }

        $model->delete($id);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Data fasilitas berhasil dihapus!'
        ]);
    }
}
