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
        $model = new MasterPortModel();
        $data['port'] = $model->orderBy('id', 'DESC')->findAll();

        return view('master/port/index', $data);
    }

    // ============================================================
    // STORE DATA
    // ============================================================
    public function store()
    {
        $validation = \Config\Services::validation();

        $rules = [
            'kode'         => 'required|min_length[2]|max_length[20]|is_unique[master_port.kode]',
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
            'kode'         => "required|min_length[2]|max_length[20]|is_unique[master_port.kode,id,{$id}]",
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
