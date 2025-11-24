<?php

namespace App\Models\Master;

use CodeIgniter\Model;

class MasterVesselModel extends Model
{
    protected $table            = 'master_vessel';
    protected $primaryKey       = 'id';

    protected $allowedFields    = [
        'kode', 'nama_vessel', 'negara_vessel'
    ];

    protected $useTimestamps = true;
}
