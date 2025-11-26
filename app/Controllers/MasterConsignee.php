<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Master\MasterConsigneeModel;

class MasterConsignee extends BaseController
{
    // ===============================
    // INDEX
    // ===============================
    public function index()
    {
        return view('master/consignee/index');
    }

    // ===============================
    // LIST DATA (AJAX)
    // ===============================
    public function list()
    {
        $model = new MasterConsigneeModel();

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

        $model = new MasterConsigneeModel();
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

        $model = new MasterConsigneeModel();
        $data  = $model->select('nama_consignee')
                       ->like('nama_consignee', $q)
                       ->limit(10)
                       ->findAll();

        $results = [];

        foreach ($data as $row) {
            $results[] = [
                'id'     => $row['nama_consignee'],
                'text'   => $row['nama_consignee'],
                'exists' => true
            ];
        }

        return $this->response->setJSON($results);
    }

    public function searchNpwp()
    {
        $q = $this->request->getGet('term');

        $model = new MasterConsigneeModel();

        $data = $model->select('npwp_consignee')
                    ->like('npwp_consignee', $q)
                    ->limit(10)
                    ->findAll();

        $results = [];

        foreach ($data as $row) {
            $results[] = [
                'id'     => $row['npwp_consignee'],
                'text'   => $row['npwp_consignee'],
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
            'kode'             => 'required|min_length[4]|max_length[6]|is_unique[master_consignee.kode]',
            'nama_consignee'   => 'required|min_length[5]|max_length[50]',
            'npwp_consignee'   => 'required|min_length[16]|max_length[16]',
            'alamat_consignee' => 'required|min_length[10]|max_length[100]'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'status' => 'error',
                'errors' => $this->validator->getErrors()
            ]);
        }

        $model = new MasterConsigneeModel();
        $model->save($this->request->getPost());

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Data consignee berhasil ditambahkan!'
        ]);
    }

    // ===============================
    // GET DATA (EDIT)
    // ===============================
    public function edit($id)
    {
        $model = new MasterConsigneeModel();
        $data  = $model->find($id);

        if (!$data) {
            return $this->response->setJSON([
                'status' => 'error',
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
        $model = new MasterConsigneeModel();
        $existing = $model->find($id);

        if (!$existing) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Data tidak ditemukan'
            ]);
        }

        // is_unique harus mengabaikan row yang sedang diedit
        $rules = [
            'kode' => "required|min_length[4]|max_length[6]|is_unique[master_consignee.kode,id,{$id}]",
            'nama_consignee'   => 'required|min_length[5]|max_length[50]',
            'npwp_consignee'   => 'required|min_length[16]|max_length[16]',
            'alamat_consignee' => 'required|min_length[10]|max_length[100]'
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
            'message' => 'Data consignee berhasil diperbarui!'
        ]);
    }

    // ===============================
    // DELETE
    // ===============================
    public function delete($id)
    {
        $model = new MasterConsigneeModel();

        if (!$model->find($id)) {
            return $this->response->setJSON([
                'status' => 'error',
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
