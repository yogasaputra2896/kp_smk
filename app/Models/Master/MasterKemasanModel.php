<?php

namespace App\Models\Master;

use CodeIgniter\Model;

class MasterKemasanModel extends Model
{
    protected $table            = 'master_kemasan';
    protected $primaryKey       = 'id';

    protected $allowedFields    = [
        'kode', 'jenis_kemasan'
    ];

    protected $useTimestamps = true;
}
