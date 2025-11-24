<?php

namespace App\Models\Master;

use CodeIgniter\Model;

class MasterPelayaranModel extends Model
{
    protected $table            = 'master_pelayaran';
    protected $primaryKey       = 'id';

    protected $allowedFields    = [
        'kode', 'nama_pelayaran', 'npwp_pelayaran', 'alamat_pelayaran'
    ];

    protected $useTimestamps = true;
}
