<?php

namespace App\Models\Master;

use CodeIgniter\Model;

class MasterLartasModel extends Model
{
    protected $table            = 'master_lartas';
    protected $primaryKey       = 'id';

    protected $allowedFields    = [
        'kode', 'nama_lartas'
    ];

    protected $useTimestamps = true;
}
