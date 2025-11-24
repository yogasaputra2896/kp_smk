<?php

namespace App\Models\Master;

use CodeIgniter\Model;

class MasterPortModel extends Model
{
    protected $table            = 'master_port';
    protected $primaryKey       = 'id';

    protected $allowedFields    = [
        'kode', 'nama_port', 'negara_port'
    ];

    protected $useTimestamps = true;
}
