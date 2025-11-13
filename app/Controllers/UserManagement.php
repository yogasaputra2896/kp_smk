<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\User\UserModel;
use App\Models\User\UserTrashModel;

class UserManagement extends BaseController
{
    protected $userModel;
    protected $db;

    public function __construct()
    {
        helper(['form', 'url', 'auth']);
        $this->userModel = new UserModel();
        $this->db = \Config\Database::connect();
    }

    // ============================================================
    // HALAMAN INDEX
    // ============================================================
    public function index()
    {
        if (is_file(APPPATH . 'Views/user_management/index.php')) {
            return view('user_management/index');
        }
        return view('user/index');
    }

    // ============================================================
    // LIST USER (JSON)
    // ============================================================
    public function list()
    {
        $userModel = new \App\Models\UserManagementModel();
        $rows = $userModel->getAllUsersWithRole();

        // Siapkan data untuk tabel
        $data = [];
        foreach ($rows as $r) {
            $data[] = [
                'id'         => $r['id'],
                'username'   => $r['username'],
                'email'      => $r['email'],
                'fullname'   => $r['fullname'] ?? '-',
                'role'       => $r['role'] ?? 'N/A',
                'status'     => $r['status'] ?? ($r['active'] ? 'active' : 'inactive'),
                'active'     => $r['active'],
                'created_at' => $r['created_at'],
            ];
        }

        // RETURN langsung array, bukan { data: [...] }
        return $this->response->setJSON($data);
    }



    // ============================================================
    // TAMBAH USER BARU
    // ============================================================
    public function store()
    {
        $validation = \Config\Services::validation();

        $rules = [
            'username' => 'required|is_unique[users.username]',
            'email'    => 'required|valid_email|is_unique[users.email]',
            'fullname' => 'required',
            'password' => 'required|min_length[6]',
            'role'     => 'required|in_list[admin,staff,accounting,teknisi]',
        ];

        $messages = [
            'username' => [
                'required'  => 'Username wajib diisi.',
                'is_unique' => 'Username sudah digunakan.'
            ],
            'email' => [
                'required'  => 'Email wajib diisi.',
                'valid_email' => 'Email tidak valid.',
                'is_unique' => 'Email sudah digunakan.'
            ],
            'password' => [
                'required' => 'Password wajib diisi.',
                'min_length' => 'Password minimal 6 karakter.'
            ],
            'role' => ['required' => 'Role wajib diisi.']
        ];

        $validation->setRules($rules, $messages);

        if (!$validation->withRequest($this->request)->run()) {
            return $this->response->setStatusCode(422)->setJSON([
                'status'  => 'error',
                'message' => implode("\n", $validation->getErrors())
            ]);
        }

        $data = [
            'username' => $this->request->getPost('username'),
            'email'    => $this->request->getPost('email'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role'     => $this->request->getPost('role'),
            'status'   => 'active',
        ];

        try {
            $this->userModel->insert($data);
            return $this->response->setJSON([
                'status'  => 'ok',
                'message' => 'User berhasil ditambahkan.'
            ]);
        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)->setJSON([
                'status'  => 'error',
                'message' => 'Gagal menyimpan data user.'
            ]);
        }
    }

    // ============================================================
    // EDIT USER
    // ============================================================
    public function edit($id)
    {
        $user = $this->userModel->find($id);

        if (!$user) {
            return $this->response->setStatusCode(404)->setJSON([
                'status'  => 'error',
                'message' => 'Data user tidak ditemukan.'
            ]);
        }

        return $this->response->setJSON(['status' => 'ok', 'data' => $user]);
    }

    // ============================================================
    // UPDATE USER
    // ============================================================
    public function update($id)
    {
        $validation = \Config\Services::validation();

        $rules = [
            'username' => "required|is_unique[users.username,id,{$id}]",
            'email'    => "required|valid_email|is_unique[users.email,id,{$id}]",
            'fullname' => 'required',
            'role'     => 'required|in_list[admin,staff,accounting,teknisi]',
        ];

        $validation->setRules($rules);

        if (!$validation->withRequest($this->request)->run()) {
            return $this->response->setStatusCode(422)->setJSON([
                'status'  => 'error',
                'message' => implode("\n", $validation->getErrors())
            ]);
        }

        $data = [
            'username' => $this->request->getPost('username'),
            'email'    => $this->request->getPost('email'),
            'fullname' => $this->request->getPost('fullname'),
            'role'     => $this->request->getPost('role'),
            'status'   => $this->request->getPost('status') ?? 'active',
        ];

        // Jika password diisi, update juga
        if ($this->request->getPost('password')) {
            $data['password'] = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);
        }

        try {
            $this->userModel->update($id, $data);
            return $this->response->setJSON([
                'status'  => 'ok',
                'message' => 'Data user berhasil diperbarui.'
            ]);
        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)->setJSON([
                'status'  => 'error',
                'message' => 'Gagal memperbarui data user.'
            ]);
        }
    }

    // ============================================================
    // HAPUS USER (PINDAHKAN KE TRASH)
    // ============================================================
    public function delete($id)
    {
        try {
            $trashModel = new UserTrashModel();
            $row = $this->userModel->find($id);

            if (!$row) {
                return $this->response->setStatusCode(404)->setJSON([
                    'status'  => 'error',
                    'message' => 'User tidak ditemukan.'
                ]);
            }

            $row['deleted_at'] = date('Y-m-d H:i:s');
            $row['deleted_by'] = user() ? user()->username : 'system';

            unset($row['id']);
            $trashModel->insert($row);

            $this->db->table('users')->where('id', $id)->delete();

            return $this->response->setJSON([
                'status'  => 'ok',
                'message' => 'User berhasil dihapus dan dipindahkan ke trash.'
            ]);
        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)->setJSON([
                'status'  => 'error',
                'message' => 'Gagal menghapus user.'
            ]);
        }
    }

    // ============================================================
    // RESTORE DARI TRASH
    // ============================================================
    public function restore($id)
    {
        $trashModel = new UserTrashModel();
        $trash = $trashModel->find($id);

        if (!$trash) {
            return $this->response->setStatusCode(404)->setJSON([
                'status'  => 'error',
                'message' => 'Data user tidak ditemukan di trash.'
            ]);
        }

        $exists = $this->userModel
            ->where('username', $trash['username'])
            ->orWhere('email', $trash['email'])
            ->countAllResults();

        if ($exists > 0) {
            return $this->response->setStatusCode(409)->setJSON([
                'status'  => 'error',
                'message' => "Tidak bisa restore. Username/email sudah digunakan."
            ]);
        }

        unset($trash['id'], $trash['deleted_at'], $trash['deleted_by']);
        $trash['created_at'] = date('Y-m-d H:i:s');
        $trash['updated_at'] = date('Y-m-d H:i:s');

        $this->userModel->insert($trash);
        $trashModel->delete($id);

        return $this->response->setJSON([
            'status'  => 'ok',
            'message' => 'User berhasil direstore dari trash.'
        ]);
    }
}
