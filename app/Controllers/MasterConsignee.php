<?php

namespace App\Controllers;

use App\Models\Master\MasterConsigneeModel;
use CodeIgniter\Controller;

class MasterConsignee extends Controller
{
    public function index()
    {
        $model = new MasterConsigneeModel();
        $data['consignees'] = $model->orderBy('id', 'DESC')->findAll();

        return view('master/consignee/index', $data);
    }

    public function list()
    {
        $model = new MasterConsigneeModel();

        return $this->response->setJSON([
            'data' => $model->orderBy('id', 'DESC')->findAll()
        ]);
    }


    // ===============================
    // STORE DATA
    // ===============================
    public function store()
    {
        $validation = \Config\Services::validation();

        $rules = [
            'kode'            => 'required|min_length[2]|max_length[20]|is_unique[master_consignee.kode]',
            'nama_consignee'  => 'required|min_length[3]',
            'npwp_consignee'  => 'permit_empty|min_length[5]',
            'alamat_consignee'=> 'permit_empty|min_length[5]'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'status' => 'error',
                'errors' => $validation->getErrors()
            ]);
        }

        $model = new MasterConsigneeModel();

        $model->save([
            'kode'             => $this->request->getPost('kode'),
            'nama_consignee'   => $this->request->getPost('nama_consignee'),
            'npwp_consignee'   => $this->request->getPost('npwp_consignee'),
            'alamat_consignee' => $this->request->getPost('alamat_consignee'),
        ]);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Data consignee berhasil ditambahkan!'
        ]);
    }

    // ===============================
    // GET DATA BY ID (EDIT)
    // ===============================
    public function edit($id)
    {
        $model = new MasterConsigneeModel();
        $data = $model->find($id);

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

        $rules = [
            'kode'            => "required|min_length[2]|max_length[20]|is_unique[master_consignee.kode,id,{$id}]",
            'nama_consignee'  => 'required|min_length[3]',
            'npwp_consignee'  => 'permit_empty|min_length[5]',
            'alamat_consignee'=> 'permit_empty|min_length[5]'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'status' => 'error',
                'errors' => \Config\Services::validation()->getErrors()
            ]);
        }

        $model->update($id, [
            'kode'             => $this->request->getPost('kode'),
            'nama_consignee'   => $this->request->getPost('nama_consignee'),
            'npwp_consignee'   => $this->request->getPost('npwp_consignee'),
            'alamat_consignee' => $this->request->getPost('alamat_consignee'),
        ]);

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
