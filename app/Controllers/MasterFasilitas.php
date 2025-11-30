<?php

namespace App\Controllers;

use App\Models\Master\MasterFasilitasModel;
use CodeIgniter\Controller;

class MasterFasilitas extends Controller
{
    // ============================================================
    // INDEX — LIST DATA
    // ============================================================
    public function index()
    {
        return view('master/fasilitas/index');
    }

    // ===============================
    // LIST DATA (AJAX)
    // ===============================
    public function list()
    {
        $model = new MasterFasilitasModel();

        return $this->response->setJSON([
            'data' => $model->orderBy('id', 'ASC')->findAll()
        ]);
    }

    // ============================================================
    // STORE — TAMBAH DATA
    // ============================================================
    public function store()
    {
        $validation = \Config\Services::validation();

        $rules = [
            'kode'          => 'required|min_length[2]|max_length[7]|is_unique[master_fasilitas.kode]',
            'tipe_fasilitas' => 'required|min_length[5]',
            'nama_fasilitas' => 'required|min_length[5]',
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'status' => 'error',
                'errors' => $validation->getErrors()
            ]);
        }

        $model = new MasterFasilitasModel();
        $model->save([
            'kode'           => $this->request->getPost('kode'),
            'tipe_fasilitas' => $this->request->getPost('tipe_fasilitas'),
            'nama_fasilitas' => $this->request->getPost('nama_fasilitas'),
        ]);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Data fasilitas berhasil ditambahkan!'
        ]);
    }

    // ============================================================
    // EDIT — GET DATA BY ID
    // ============================================================
    public function edit($id)
    {
        $model = new MasterFasilitasModel();
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

        $model = new MasterFasilitasModel();
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
    // SEARCH TIPE (SELECT2)
    // ===============================
    public function searchTipe()
    {
        $q = $this->request->getGet('term');

        $model = new MasterFasilitasModel();

        $data = $model->distinct()
            ->select('tipe_fasilitas')
            ->like('tipe_fasilitas', $q)
            ->orderBy('tipe_fasilitas', 'ASC')
            ->limit(16)
            ->findAll();

        $results = [];

        foreach ($data as $row) {
            $results[] = [
                'id'   => $row['tipe_fasilitas'],
                'text' => $row['tipe_fasilitas']
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

        $model = new MasterFasilitasModel();
        $data  = $model->select('nama_fasilitas')
            ->like('nama_fasilitas', $q)
            ->limit(10)
            ->findAll();

        $results = [];

        foreach ($data as $row) {
            $results[] = [
                'id'     => $row['nama_fasilitas'],
                'text'   => $row['nama_fasilitas'],
                'exists' => true
            ];
        }

        return $this->response->setJSON($results);
    }

    // ============================================================
    // UPDATE — SIMPAN PERUBAHAN
    // ============================================================
    public function update($id)
    {
        $model = new MasterFasilitasModel();
        $existing = $model->find($id);

        if (!$existing) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Data tidak ditemukan!'
            ]);
        }

        $rules = [
            'kode'           => "required|min_length[2]|max_length[7]|is_unique[master_fasilitas.kode,id,{$id}]",
            'tipe_fasilitas' => 'required|min_length[5]',
            'nama_fasilitas' => 'required|min_length[5]',
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'status' => 'error',
                'errors' => \Config\Services::validation()->getErrors()
            ]);
        }

        $model->update($id, [
            'kode'           => $this->request->getPost('kode'),
            'tipe_fasilitas' => $this->request->getPost('tipe_fasilitas'),
            'nama_fasilitas' => $this->request->getPost('nama_fasilitas'),
        ]);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Data fasilitas berhasil diperbarui!'
        ]);
    }

    // ============================================================
    // DELETE — HAPUS DATA
    // ============================================================
    public function delete($id)
    {
        $model = new MasterFasilitasModel();

        if (!$model->find($id)) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Data tidak ditemukan!'
            ]);
        }

        $model->delete($id);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Data fasilitas berhasil dihapus!'
        ]);
    }
}
