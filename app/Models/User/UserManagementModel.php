<?php

namespace App\Models\User;

use CodeIgniter\Model;

class UserManagementModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'email',
        'username',
        'fullname',
        'password_hash',
        'status',
        'active',
        'created_at',
        'updated_at'
    ];
    protected $useTimestamps = true;

    public function getAllUsersWithRole()
    {
        return $this->db->table('users u')
            ->select('u.id, u.username, u.email, u.status, u.active, u.created_at, ag.name as role')
            ->join('auth_groups_users agu', 'agu.user_id = u.id', 'left')
            ->join('auth_groups ag', 'ag.id = agu.group_id', 'left')
            ->orderBy('u.created_at', 'DESC')
            ->get()
            ->getResultArray();
    }
}
