<?php

namespace App\Models\User;

use CodeIgniter\Model;

class UserTrashModel extends Model
{
    protected $table            = 'user_trash'; 
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;

    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;      

    protected $allowedFields    = [
        'user_id',
        'username',
        'email',
        'role',
        'deleted_at',
        'deleted_by'
    ];

    // ==============================
    // VALIDATION RULES
    // ==============================
    protected $validationRules = [
        'user_id'   => 'required|integer',
        'username'  => 'required|min_length[3]|max_length[50]',
        'email'     => 'required|valid_email',
        'role'      => 'required',
    ];

    protected $validationMessages = [
        'user_id' => [
            'required' => 'ID user wajib diisi.'
        ],
        'username' => [
            'required' => 'Username wajib diisi.'
        ],
        'email' => [
            'required' => 'Email wajib diisi.',
            'valid_email' => 'Format email tidak valid.'
        ],
        'role' => [
            'required' => 'Role user wajib diisi.'
        ]
    ];

    // ==============================
    // CALLBACKS
    // ==============================
    protected $beforeInsert = ['setDeletedAt'];

    protected function setDeletedAt(array $data)
    {
        $data['data']['deleted_at'] = date('Y-m-d H:i:s');
        return $data;
    }

    // ==============================
    // CUSTOM METHODS
    // ==============================

    /**
     * Ambil semua user yang terhapus
     */
    public function getAllTrash()
    {
        return $this->orderBy('deleted_at', 'DESC')->findAll();
    }

    /**
     * Restore user ke tabel utama
     */
    public function restoreUser($userData)
    {
        $userModel = new \App\Models\User\UserModel();
        return $userModel->insert($userData);
    }

    /**
     * Hapus permanen dari trash
     */
    public function deletePermanent($id)
    {
        return $this->delete($id);
    }
}
