<?php

namespace App\Models\Master;

use CodeIgniter\Model;

class MasterShipperModel extends Model
{
    protected $table            = 'master_shipper';
    protected $primaryKey       = 'id';

    protected $allowedFields    = [
        'kode',
        'nama_shipper',
        'alamat_shipper'
    ];

    protected $useTimestamps = true;
}
