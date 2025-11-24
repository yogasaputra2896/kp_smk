<?php

namespace App\Controllers;

use App\Models\Master\MasterPelayaranModel;
use CodeIgniter\Controller;

class MasterPelayaran extends Controller
{
    // ============================================================
    // VIEW INDEX
    // ============================================================
    public function index()
    {
        $model = new MasterPelayaranModel();
        $data['pelayaran'] = $model->orderBy('id', 'DESC')->findAll();

        return view('master/pelayaran/index', $data);
    }

    // ============================================================
    // STORE DATA
    // ============================================================
    public function store()
    {
        $validation = \Config\Services::validation();

        $rules = [
            'kode'            => 'required|min_length[2]|max_length[20]|is_unique[master_pelayaran.kode]',
            'nama_pelayaran'  => 'required|min_length[3]',
            'npwp_pelayaran'  => 'permit_empty|min_length[5]',
            'alamat_pelayaran'=> 'permit_empty|min_length[5]',
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'status' => 'error',
                'errors' => $validation->getErrors()
            ]);
        }

        $model = new MasterPelayaranModel();

        $model->save([
            'kode'             => $this->request->getPost('kode'),
            'nama_pelayaran'   => $this->request->getPost('nama_pelayaran'),
            'npwp_pelayaran'   => $this->request->getPost('npwp_pelayaran'),
            'alamat_pelayaran' => $this->request->getPost('alamat_pelayaran'),
        ]);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Data pelayaran berhasil ditambahkan!'
        ]);
    }

    // ============================================================
    // EDIT (GET DATA)
    // ============================================================
    public function edit($id)
    {
        $model = new MasterPelayaranModel();
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

    // ============================================================
    // UPDATE DATA
    // ============================================================
    public function update($id)
    {
        $model = new MasterPelayaranModel();
        $existing = $model->find($id);

        if (!$existing) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Data tidak ditemukan'
            ]);
        }

        $rules = [
            'kode'            => "required|min_length[2]|max_length[20]|is_unique[master_pelayaran.kode,id,{$id}]",
            'nama_pelayaran'  => 'required|min_length[3]',
            'npwp_pelayaran'  => 'permit_empty|min_length[5]',
            'alamat_pelayaran'=> 'permit_empty|min_length[5]',
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'status' => 'error',
                'errors' => \Config\Services::validation()->getErrors()
            ]);
        }

        $model->update($id, [
            'kode'             => $this->request->getPost('kode'),
            'nama_pelayaran'   => $this->request->getPost('nama_pelayaran'),
            'npwp_pelayaran'   => $this->request->getPost('npwp_pelayaran'),
            'alamat_pelayaran' => $this->request->getPost('alamat_pelayaran'),
        ]);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Data pelayaran berhasil diperbarui!'
        ]);
    }

    // ============================================================
    // DELETE
    // ============================================================
    public function delete($id)
    {
        $model = new MasterPelayaranModel();

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
