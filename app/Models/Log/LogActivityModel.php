<?php

namespace App\Models\Log;

use CodeIgniter\Model;

class LogActivityModel extends Model
{
    protected $table            = 'log_activity';
    protected $primaryKey       = 'id';
    protected $allowedFields    = [
        'user_id',
        'username',
        'role',
        'activity',
        'created_at'
    ];

    protected $useTimestamps    = false;

    public function getLogsByRole($role)
    {
        if ($role === 'admin') {
            return $this->orderBy('id', 'DESC')->findAll();
        }

        return $this->where('role', $role)->orderBy('id', 'DESC')->findAll();
    }
}
