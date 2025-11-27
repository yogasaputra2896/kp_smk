<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Master\MasterNotifyPartyModel;

class MasterNotifyParty extends BaseController
{
    // ===============================
    // INDEX
    // ===============================
    public function index()
    {
        return view('master/notify_party/index');
    }

    // ===============================
    // LIST DATA (AJAX)
    // ===============================
    public function list()
    {
        $model = new MasterNotifyPartyModel();

        return $this->response->setJSON([
            'data' => $model->orderBy('id', 'ASC')->findAll()
        ]);
    }

    // ===============================
    // SEARCH KODE (SELECT2)
    // ===============================
    public function searchKode()
    {
        $q = $this->request->getGet('term');

        $model = new MasterNotifyPartyModel();
        $data  = $model->distinct()
            ->select('kode')
            ->like('kode', $q)
            ->limit(10)
            ->findAll();

        $results = [];

        foreach ($data as $row) {
            $results[] = [
                'id'   => $row['kode'],
                'text' => $row['kode'],
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

        $model = new MasterNotifyPartyModel();
        $data  = $model->distinct()
            ->select('nama_notify')
            ->like('nama_notify', $q)
            ->limit(10)
            ->findAll();

        $results = [];

        foreach ($data as $row) {
            $results[] = [
                'id'   => $row['nama_notify'],
                'text' => $row['nama_notify'],
                'exists' => true
            ];
        }

        return $this->response->setJSON($results);
    }

    // ===============================
    // SEARCH NPWP (SELECT2)
    // ===============================
    public function searchNpwp()
    {
        $q = $this->request->getGet('term');

        $model = new MasterNotifyPartyModel();
        $data  = $model->distinct()
            ->select('npwp_notify')
            ->like('npwp_notify', $q)
            ->limit(10)
            ->findAll();

        $results = [];

        foreach ($data as $row) {
            $results[] = [
                'id'   => $row['npwp_notify'],
                'text' => $row['npwp_notify'],
                'exists' => true
            ];
        }

        return $this->response->setJSON($results);
    }

    // ===============================
    // STORE DATA
    // ===============================
    public function store()
    {
        $rules = [
            'kode'          => 'required|min_length[4]|max_length[6]|is_unique[master_notify_party.kode]',
            'nama_notify'   => 'required|min_length[5]|max_length[50]',
            'npwp_notify'   => 'required|min_length[16]|max_length[16]',
            'alamat_notify' => 'required|min_length[10]'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'status' => 'error',
                'errors' => $this->validator->getErrors()
            ]);
        }

        $model = new MasterNotifyPartyModel();
        $model->save($this->request->getPost());

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Data Notify Party berhasil ditambahkan!'
        ]);
    }

    // ===============================
    // GET DATA (EDIT)
    // ===============================
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

    // ===============================
    // UPDATE DATA
    // ===============================
    public function update($id)
    {
        $model = new MasterNotifyPartyModel();
        $existing = $model->find($id);

        if (!$existing) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Data tidak ditemukan'
            ]);
        }

        // VALIDASI — abaikan id yg sedang diupdate
        $rules = [
            'kode'          => "required|min_length[4]|max_length[6]|is_unique[master_notify_party.kode,id,$id]",
            'nama_notify'   => 'required|min_length[5]|max_length[50]',
            'npwp_notify'   => 'required|min_length[16]|max_length[16]',
            'alamat_notify' => 'required|min_length[10]'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'status' => 'error',
                'errors' => $this->validator->getErrors()
            ]);
        }

        $model->update($id, $this->request->getPost());

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Data Notify Party berhasil diperbarui!'
        ]);
    }

    // ===============================
    // DELETE DATA
    // ===============================
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
