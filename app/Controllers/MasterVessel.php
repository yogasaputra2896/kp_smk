<?php

namespace App\Controllers;

use App\Models\Master\MasterVesselModel;
use CodeIgniter\Controller;

class MasterVessel extends Controller
{
    // ============================================================
    // INDEX — TAMPIL LIST
    // ============================================================
    public function index()
    {
        $model = new MasterVesselModel();
        $data['vessels'] = $model->orderBy('id', 'DESC')->findAll();

        return view('master/vessel/index', $data);
    }

    // ============================================================
    // STORE — SIMPAN DATA BARU
    // ============================================================
    public function store()
    {
        $validation = \Config\Services::validation();

        $rules = [
            'kode'         => 'required|min_length[2]|max_length[20]|is_unique[master_vessel.kode]',
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
    // EDIT — GET DATA BY ID
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

    // ============================================================
    // UPDATE — SIMPAN PERUBAHAN DATA
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
            'kode'         => "required|min_length[2]|max_length[20]|is_unique[master_vessel.kode,id,{$id}]",
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
    // DELETE — HAPUS DATA
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
