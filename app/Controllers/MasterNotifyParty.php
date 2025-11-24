<?php

namespace App\Controllers;

use App\Models\Master\MasterNotifyPartyModel;
use CodeIgniter\Controller;

class MasterNotifyParty extends Controller
{
    // ============================================================
    // INDEX — TAMPIL LIST
    // ============================================================
    public function index()
    {
        $model = new MasterNotifyPartyModel();
        $data['notify'] = $model->orderBy('id', 'DESC')->findAll();

        return view('master/notify_party/index', $data);
    }

    // ============================================================
    // STORE — SIMPAN DATA BARU
    // ============================================================
    public function store()
    {
        $validation = \Config\Services::validation();

        $rules = [
            'kode'        => 'required|min_length[2]|max_length[20]|is_unique[master_notify_party.kode]',
            'nama_notify' => 'required|min_length[3]',
            'npwp_notify' => 'permit_empty|min_length[10]|max_length[30]',
            'alamat_notify' => 'permit_empty|min_length[5]'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'status' => 'error',
                'errors' => $validation->getErrors()
            ]);
        }

        $model = new MasterNotifyPartyModel();

        $model->save([
            'kode'          => $this->request->getPost('kode'),
            'nama_notify'   => $this->request->getPost('nama_notify'),
            'npwp_notify'   => $this->request->getPost('npwp_notify'),
            'alamat_notify' => $this->request->getPost('alamat_notify'),
        ]);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Data Notify Party berhasil ditambahkan!'
        ]);
    }

    // ============================================================
    // EDIT — GET DATA
    // ============================================================
    public function edit($id)
    {
        $model = new MasterNotifyPartyModel();
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
    // UPDATE — EDIT DATA
    // ============================================================
    public function update($id)
    {
        $model    = new MasterNotifyPartyModel();
        $existing = $model->find($id);

        if (!$existing) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Data tidak ditemukan'
            ]);
        }

        $rules = [
            'kode'        => "required|min_length[2]|max_length[20]|is_unique[master_notify_party.kode,id,{$id}]",
            'nama_notify' => 'required|min_length[3]',
            'npwp_notify' => 'permit_empty|min_length[10]|max_length[30]',
            'alamat_notify' => 'permit_empty|min_length[5]'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'status' => 'error',
                'errors' => \Config\Services::validation()->getErrors()
            ]);
        }

        $model->update($id, [
            'kode'          => $this->request->getPost('kode'),
            'nama_notify'   => $this->request->getPost('nama_notify'),
            'npwp_notify'   => $this->request->getPost('npwp_notify'),
            'alamat_notify' => $this->request->getPost('alamat_notify'),
        ]);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Data Notify Party berhasil diperbarui!'
        ]);
    }

    // ============================================================
    // DELETE — HAPUS DATA
    // ============================================================
    public function delete($id)
    {
        $model = new MasterNotifyPartyModel();

        if (!$model->find($id)) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Data tidak ditemukan'
            ]);
        }

        $model->delete($id);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Data Notify Party berhasil dihapus!'
        ]);
    }
}
