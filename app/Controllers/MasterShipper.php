<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Master\MasterShipperModel;

class MasterShipper extends BaseController
{
    // INDEX
    public function index()
    {
        return view('master/shipper/index');
    }

    // LIST DATA (AJAX)
    public function list()
    {
        $model = new MasterShipperModel();
        return $this->response->setJSON([
            'data' => $model->orderBy('id', 'ASC')->findAll()
        ]);
    }

    // SEARCH KODE (SELECT2)
    public function searchKode()
    {
        $q = $this->request->getGet('term');
        $model = new MasterShipperModel();

        $data = $model->select('kode')
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

    // SEARCH NAMA (SELECT2)
    public function searchNama()
    {
        $q = $this->request->getGet('term');
        $model = new MasterShipperModel();

        $data = $model->select('nama_shipper')
            ->like('nama_shipper', $q)
            ->limit(10)
            ->findAll();

        $results = [];

        foreach ($data as $row) {
            $results[] = [
                'id'     => $row['nama_shipper'],
                'text'   => $row['nama_shipper'],
                'exists' => true
            ];
        }

        return $this->response->setJSON($results);
    }

    // STORE (ADD)
    public function store()
    {
        $rules = [
            'kode'         => 'required|min_length[2]|max_length[6]|is_unique[master_shipper.kode]',
            'nama_shipper' => 'required|min_length[3]|max_length[100]',
            'alamat_shipper' => 'required|min_length[10]'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'status' => 'error',
                'errors' => $this->validator->getErrors()
            ]);
        }

        $model = new MasterShipperModel();
        $model->save($this->request->getPost());

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Data shipper berhasil ditambahkan!'
        ]);
    }

    // EDIT (GET DATA)
    public function edit($id)
    {
        $model = new MasterShipperModel();
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

    // UPDATE
    public function update($id)
    {
        $model = new MasterShipperModel();
        $existing = $model->find($id);

        if (!$existing) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Data tidak ditemukan'
            ]);
        }

        $rules = [
            'kode' => "required|min_length[2]|max_length[6]|is_unique[master_shipper.kode,id,{$id}]",
            'nama_shipper' => 'required|min_length[3]',
            'alamat_shipper' => 'required|min_length[10]'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'status' => 'error',
                'errors' => $this->validator->getErrors()
            ]);
        }

        $model->update($id, $this->request->getPost());

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Data shipper berhasil diperbarui!'
        ]);
    }

    // DELETE
    public function delete($id)
    {
        $model = new MasterShipperModel();

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
