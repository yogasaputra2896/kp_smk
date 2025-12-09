<?php

namespace App\Models\User;

use CodeIgniter\Model;

class UserTrashModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $returnType       = 'App\Entities\User';
    protected $useSoftDeletes   = true;

    protected $allowedFields    = [
        'email', 'username', 'password_hash', 'reset_hash', 'reset_at', 'reset_expires',
        'activate_hash', 'status', 'status_message', 'active', 'force_pass_reset',
        'permissions', 'deleted_at'
    ];

    protected $useTimestamps    = true;

    /**
     * Ambil hanya user yang terhapus (soft delete)
     */
    public function onlyDeleted()
    {
        return $this->where('deleted_at IS NOT NULL')->findAll();
    }

    /**
     * Ambil user trash + role (auth_groups)
     */
    public function getDeletedWithRole()
    {
        return $this->select('users.id, username, email, deleted_at, auth_groups.name as role')
            ->join('auth_groups_users', 'auth_groups_users.user_id = users.id', 'left')
            ->join('auth_groups', 'auth_groups.id = auth_groups_users.group_id', 'left')
            ->where('users.deleted_at IS NOT NULL')
            ->findAll();
    }
}
