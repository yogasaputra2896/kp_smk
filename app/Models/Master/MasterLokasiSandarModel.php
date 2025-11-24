<?php

namespace App\Models\Master;

use CodeIgniter\Model;

class MasterLokasiSandarModel extends Model
{
    protected $table            = 'master_lokasi_sandar';
    protected $primaryKey       = 'id';

    protected $allowedFields    = [
        'kode', 'nama_sandar', 'alamat_sandar'
    ];

    protected $useTimestamps = true;
}
