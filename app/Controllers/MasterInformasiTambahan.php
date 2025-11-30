<?php

namespace App\Controllers;

use App\Models\Master\MasterInformasiTambahanModel;
use CodeIgniter\Controller;

class MasterInformasiTambahan extends Controller
{

    public function index()
    {
        return view('master/info_tambahan/index');
    }

    // ===============================
    // LIST DATA (AJAX)
    // ===============================
    public function list()
    {
        $model = new MasterInformasiTambahanModel();

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
            'kode'           => 'required|min_length[2]|max_length[6]|is_unique[master_informasi_tambahan.kode]',
            'nama_pengurusan' => 'required|min_length[5]',
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'status' => 'error',
                'errors' => $validation->getErrors()
            ]);
        }

        $model = new MasterInformasiTambahanModel();
        $model->save([
            'kode'            => $this->request->getPost('kode'),
            'nama_pengurusan' => $this->request->getPost('nama_pengurusan'),
        ]);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Data informasi tambahan berhasil ditambahkan!'
        ]);
    }

    // ============================================================
    // EDIT — GET DATA BY ID
    // ============================================================
    public function edit($id)
    {
        $model = new MasterInformasiTambahanModel();
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

        $model = new MasterInformasiTambahanModel();
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

        $model = new MasterInformasiTambahanModel();
        $data  = $model->select('nama_pengurusan')
            ->like('nama_pengurusan', $q)
            ->limit(10)
            ->findAll();

        $results = [];

        foreach ($data as $row) {
            $results[] = [
                'id'     => $row['nama_pengurusan'],
                'text'   => $row['nama_pengurusan'],
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
        $model = new MasterInformasiTambahanModel();
        $existing = $model->find($id);

        if (!$existing) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Data tidak ditemukan!'
            ]);
        }

        $rules = [
            'kode'            => "required|min_length[1]|max_length[20]|is_unique[master_informasi_tambahan.kode,id,{$id}]",
            'nama_pengurusan' => 'required|min_length[2]',
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'status' => 'error',
                'errors' => \Config\Services::validation()->getErrors()
            ]);
        }

        $model->update($id, [
            'kode'            => $this->request->getPost('kode'),
            'nama_pengurusan' => $this->request->getPost('nama_pengurusan'),
        ]);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Data informasi tambahan berhasil diperbarui!'
        ]);
    }

    // ============================================================
    // DELETE — HAPUS DATA
    // ============================================================
    public function delete($id)
    {
        $model = new MasterInformasiTambahanModel();

        if (!$model->find($id)) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Data tidak ditemukan!'
            ]);
        }

        $model->delete($id);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Data informasi tambahan berhasil dihapus!'
        ]);
    }
}
