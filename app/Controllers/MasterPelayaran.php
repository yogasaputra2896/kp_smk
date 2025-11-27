<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Master\MasterPelayaranModel;

class MasterPelayaran extends BaseController
{
    // ===============================
    // INDEX VIEW
    // ===============================
    public function index()
    {
        return view('master/pelayaran/index');
    }

    // ===============================
    // LIST DATA (AJAX)
    // ===============================
    public function list()
    {
        $model = new MasterPelayaranModel();

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

        $model = new MasterPelayaranModel();
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

        $model = new MasterPelayaranModel();
        $data  = $model->select('nama_pelayaran')
                       ->like('nama_pelayaran', $q)
                       ->limit(10)
                       ->findAll();

        $results = [];

        foreach ($data as $row) {
            $results[] = [
                'id'     => $row['nama_pelayaran'],
                'text'   => $row['nama_pelayaran'],
                'exists' => true
            ];
        }

        return $this->response->setJSON($results);
    }

    public function searchNpwp()
    {
        $q = $this->request->getGet('term');

        $model = new MasterPelayaranModel();

        $data = $model->select('npwp_pelayaran')
                    ->like('npwp_pelayaran', $q)
                    ->limit(16)
                    ->findAll();

        $results = [];

        foreach ($data as $row) {
            $results[] = [
                'id'     => $row['npwp_pelayaran'],
                'text'   => $row['npwp_pelayaran'],
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
            'kode'             => 'required|min_length[4]|max_length[6]|is_unique[master_pelayaran.kode]',
            'nama_pelayaran'   => 'required|min_length[5]|max_length[50]',
            'npwp_pelayaran'   => 'required|min_length[16]|max_length[16]',
            'alamat_pelayaran' => 'required|min_length[10]'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'status' => 'error',
                'errors' => $this->validator->getErrors()
            ]);
        }

        $model = new MasterPelayaranModel();
        $model->save($this->request->getPost());

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Data pelayaran berhasil ditambahkan!'
        ]);
    }

    // ===============================
    // GET DATA (EDIT)
    // ===============================
    public function edit($id)
    {
        $model = new MasterPelayaranModel();
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
        $model = new MasterPelayaranModel();
        $existing = $model->find($id);

        if (!$existing) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Data tidak ditemukan'
            ]);
        }

        $rules = [
            'kode'             => 'required|min_length[4]|max_length[6]|is_unique[master_pelayaran.kode,id,{$id}]',
            'nama_pelayaran'   => 'required|min_length[5]|max_length[50]',
            'npwp_pelayaran'   => 'required|min_length[16]|max_length[16]',
            'alamat_pelayaran' => 'required|min_length[10]'
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
            'message' => 'Data pelayaran berhasil diperbarui!'
        ]);
    }

    // ===============================
    // DELETE DATA
    // ===============================
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
