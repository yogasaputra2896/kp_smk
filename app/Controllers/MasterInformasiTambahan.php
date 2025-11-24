<?php

namespace App\Controllers;

use App\Models\Master\MasterInformasiTambahanModel;
use CodeIgniter\Controller;

class MasterInformasiTambahan extends Controller
{
    // ============================================================
    // INDEX — LIST DATA
    // ============================================================
    public function index()
    {
        $model = new MasterInformasiTambahanModel();
        $data['info'] = $model->orderBy('id', 'DESC')->findAll();

        return view('master/informasi_tambahan/index', $data);
    }

    // ============================================================
    // STORE — TAMBAH DATA
    // ============================================================
    public function store()
    {
        $validation = \Config\Services::validation();

        $rules = [
            'kode'           => 'required|min_length[1]|max_length[20]|is_unique[master_informasi_tambahan.kode]',
            'nama_pengurusan' => 'required|min_length[2]',
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'status' => 'error',
                'errors' => $validation->getErrors()
            ]);
        }

        $model = new MasterInformasiTambahanModel();
        $model->save([
            'kode'            => $this->request->getPost('kode'),
            'nama_pengurusan' => $this->request->getPost('nama_pengurusan'),
        ]);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Data informasi tambahan berhasil ditambahkan!'
        ]);
    }

    // ============================================================
    // EDIT — GET DATA BY ID
    // ============================================================
    public function edit($id)
    {
        $model = new MasterInformasiTambahanModel();
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
        $model = new MasterInformasiTambahanModel();
        $existing = $model->find($id);

        if (!$existing) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Data tidak ditemukan!'
            ]);
        }

        $rules = [
            'kode'            => "required|min_length[1]|max_length[20]|is_unique[master_informasi_tambahan.kode,id,{$id}]",
            'nama_pengurusan' => 'required|min_length[2]',
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'status' => 'error',
                'errors' => \Config\Services::validation()->getErrors()
            ]);
        }

        $model->update($id, [
            'kode'            => $this->request->getPost('kode'),
            'nama_pengurusan' => $this->request->getPost('nama_pengurusan'),
        ]);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Data informasi tambahan berhasil diperbarui!'
        ]);
    }

    // ============================================================
    // DELETE — HAPUS DATA
    // ============================================================
    public function delete($id)
    {
        $model = new MasterInformasiTambahanModel();

        if (!$model->find($id)) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Data tidak ditemukan!'
            ]);
        }

        $model->delete($id);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Data informasi tambahan berhasil dihapus!'
        ]);
    }
}
