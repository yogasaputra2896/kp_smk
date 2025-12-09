<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\User\UserModel;
use Myth\Auth\Models\GroupModel;
use Myth\Auth\Entities\User;


class UserManagement extends BaseController
{
    protected $userModel;
    protected $groupModel;

    public function __construct()
    {
        $this->userModel  = new UserModel();
        $this->groupModel = new GroupModel();
    }

    /**
     * ============================
     * HALAMAN UTAMA (VIEW)
     * ============================
     */
    public function index()
    {
        return view('user_management/index'); // sesuai folder Yoga
    }

    /**
     * ============================
     * LIST DATA USER (JSON)
     * ============================
     */
    public function list()
    {
        $adminGroup = $this->groupModel->where('name', 'admin')->first();

        $users = $this->userModel
            ->select('users.id, users.username, users.email, users.active, auth_groups.name as role')
            ->join('auth_groups_users', 'auth_groups_users.user_id = users.id', 'left')
            ->join('auth_groups', 'auth_groups.id = auth_groups_users.group_id', 'left')
            ->where('auth_groups_users.group_id !=', $adminGroup->id)
            ->findAll();

        return $this->response->setJSON($users);
    }

    /**
     * ============================
     * GET USER BY ID (AJAX)
     * ============================
     */
    public function edit($id)
    {
        $user = $this->userModel
            ->select('users.id, username, email, active, auth_groups.name as role')
            ->join('auth_groups_users', 'auth_groups_users.user_id = users.id', 'left')
            ->join('auth_groups', 'auth_groups.id = auth_groups_users.group_id', 'left')
            ->where('users.id', $id)
            ->first();

        $roles = $this->groupModel->findAll();

        return $this->response->setJSON([
            'user'  => $user,
            'roles' => $roles
        ]);
    }

    /**
     * ============================
     * CREATE USER
     * ============================
     */
    public function store()
    {
        $data = $this->request->getPost();

        // Validasi...
        $rules = [
            'email'    => 'required|valid_email|is_unique[users.email]',
            'username' => 'required|min_length[3]|is_unique[users.username]',
            'password' => 'required|min_length[5]',
            'role'     => 'required',
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'status' => false,
                'errors' => $this->validator->getErrors(),
            ]);
        }

        // Ambil role
        $group = $this->groupModel->where('name', $data['role'])->first();

        // =============== INSERT USER ===============
        $user = new User([
            'email'    => $data['email'],
            'username' => $data['username'],
            'password' => $data['password'], // entity otomatis HASH
            'active'   => 1
        ]);

        $this->userModel->save($user);

        // Ambil ID
        $userId = $this->userModel->getInsertID();

        // =============== ASSIGN ROLE ===============
        $this->groupModel->addUserToGroup($userId, $group->id);

        return $this->response->setJSON(['status' => true]);
    }


    public function update($id)
    {
        $data = $this->request->getPost();

        // RULES (format is_unique versi stabil)
        $rules = [
            'email'    => 'required|valid_email|is_unique[users.email,id,' . $id . ']',
            'username' => 'required|min_length[3]|is_unique[users.username,id,' . $id . ']',
            'role'     => 'required',
            'active'   => 'required'
        ];

        if (!empty($data['password'])) {
            $rules['password'] = 'min_length[5]';
        }

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'status' => false,
                'errors' => $this->validator->getErrors(),
            ]);
        }

        // Ambil role
        $group = $this->groupModel->where('name', $data['role'])->first();
        if (!$group) {
            return $this->response->setJSON([
                'status' => false,
                'errors' => ['role' => 'Role tidak ditemukan di database']
            ]);
        }

        // Siapkan data update
        $updateData = [
            'id'       => $id,
            'email'    => $data['email'],
            'username' => $data['username'],
            'active'   => $data['active'],
        ];

        if (!empty($data['password'])) {
            $updateData['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }

        // TRX BIAR AMAN
        $db = \Config\Database::connect();
        $db->transStart();

        // Update user
        $this->userModel->save($updateData);

        // Reset group
        $db->table('auth_groups_users')->where('user_id', $id)->delete();

        // Tambah group baru
        $db->table('auth_groups_users')->insert([
            'user_id'  => $id,
            'group_id' => $group->id
        ]);

        $db->transComplete();

        if (!$db->transStatus()) {
            return $this->response->setJSON([
                'status' => false,
                'errors' => ['server' => 'Gagal update data ke database']
            ]);
        }

        return $this->response->setJSON(['status' => true]);
    }




    /**
     * ============================
     * DELETE (SOFT DELETE)
     * ============================
     */
    public function delete($id)
    {
        $user = $this->userModel->find($id);

        if (!$user) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'User tidak ditemukan.'
            ]);
        }

        // Nonaktifkan user
        $this->userModel->update($id, ['active' => 0]);

        // Soft delete
        $this->userModel->delete($id);

        return $this->response->setJSON(['status' => true]);
    }


    /**
     * ============================
     * TRASH MANAGEMENT
     * ============================
     */

    public function trashView()
    {
        return view('user_management/trash/index.php');
    }

    public function trashList()
    {
        $users = $this->userModel
            ->onlyDeleted()
            ->select('id, username, email, active, deleted_at')
            ->findAll();

        return $this->response->setJSON($users);
    }

    public function restore($id)
    {
        $this->userModel->update($id, ['deleted_at' => null]);

        return $this->response->setJSON(['status' => true]);
    }

    public function deletePermanent($id)
    {
        $this->userModel->delete($id, true); // TRUE = force delete

        return $this->response->setJSON(['status' => true]);
    }
}
