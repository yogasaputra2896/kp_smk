<?php

namespace App\Controllers;

use App\Models\Master\MasterLokasiSandarModel;
use CodeIgniter\Controller;

class MasterLokasiSandar extends Controller
{
    // ============================================================
    // VIEW INDEX
    // ============================================================
    public function index()
    {
        return view('master/lokasi_sandar/index');
    }

    // ============================================================
    // LIST DATA (AJAX)
    // ============================================================
    public function list()
    {
        $model = new MasterLokasiSandarModel();

        return $this->response->setJSON([
            'data' => $model->orderBy('id', 'ASC')->findAll()
        ]);
    }


    // ============================================================
    // STORE DATA
    // ============================================================
    public function store()
    {
        $validation = \Config\Services::validation();

        $rules = [
            'kode'         => 'required|min_length[4]|max_length[6]|is_unique[master_lokasi_sandar.kode]',
            'nama_sandar'  => 'required|min_length[3]',
            'alamat_sandar' => 'required|min_length[3]'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'status' => 'error',
                'errors' => $validation->getErrors()
            ]);
        }

        $model = new MasterLokasiSandarModel();
        $model->save([
            'kode'          => $this->request->getPost('kode'),
            'nama_sandar'   => $this->request->getPost('nama_sandar'),
            'alamat_sandar' => $this->request->getPost('alamat_sandar'),
        ]);

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Data lokasi sandar berhasil ditambahkan!'
        ]);
    }

    // ============================================================
    // EDIT (GET DATA)
    // ============================================================
    public function edit($id)
    {
        $model = new MasterLokasiSandarModel();
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
    // SEARCH KODE (SELECT2)
    // ===============================
    public function searchKode()
    {
        $q = $this->request->getGet('term');

        $model = new MasterLokasiSandarModel();
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

        $model = new MasterLokasiSandarModel();
        $data  = $model->select('nama_sandar')
            ->like('nama_sandar', $q)
            ->limit(10)
            ->findAll();

        $results = [];

        foreach ($data as $row) {
            $results[] = [
                'id'     => $row['nama_sandar'],
                'text'   => $row['nama_sandar'],
                'exists' => true
            ];
        }

        return $this->response->setJSON($results);
    }

    // ============================================================
    // UPDATE — SIMPAN PERUBAHAN DATA
    // ============================================================
    public function update($id)
    {
        $model    = new MasterLokasiSandarModel();
        $existing = $model->find($id);

        if (!$existing) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Data tidak ditemukan'
            ]);
        }

        // validasi update
        $rules = [
            'kode'         => "required|min_length[4]|max_length[6]|is_unique[master_lokasi_sandar.kode,id,{$id}]",
            'nama_sandar'  => 'required|min_length[3]',
            'alamat_sandar' => 'required|min_length[3]',
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'status' => 'error',
                'errors' => \Config\Services::validation()->getErrors()
            ]);
        }

        $model->update($id, [
            'kode'          => $this->request->getPost('kode'),
            'nama_sandar'   => $this->request->getPost('nama_sandar'),
            'alamat_sandar' => $this->request->getPost('alamat_sandar'),
        ]);

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Data lokasi sandar berhasil diperbarui!'
        ]);
    }

    // ============================================================
    // DELETE — HAPUS DATA
    // ============================================================
    public function delete($id)
    {
        $model = new MasterLokasiSandarModel();

        if (!$model->find($id)) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Data tidak ditemukan'
            ]);
        }

        $model->delete($id);

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Data lokasi sandar berhasil dihapus!'
        ]);
    }
}
