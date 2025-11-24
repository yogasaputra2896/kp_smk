<?php

namespace App\Models\Master;

use CodeIgniter\Model;

class MasterConsigneeModel extends Model
{
    protected $table            = 'master_consignee';
    protected $primaryKey       = 'id';

    protected $allowedFields    = [
        'kode', 'nama_consignee', 'npwp_consignee', 'alamat_consignee'
    ];

    protected $useTimestamps = true;
}
