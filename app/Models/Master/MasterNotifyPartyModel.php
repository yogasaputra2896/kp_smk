<?php

namespace App\Models\Master;

use CodeIgniter\Model;

class MasterNotifyPartyModel extends Model
{
    protected $table            = 'master_notify_party';
    protected $primaryKey       = 'id';

    protected $allowedFields    = [
        'kode', 'nama_notify', 'npwp_notify', 'alamat_notify'
    ];

    protected $useTimestamps = true;
}
