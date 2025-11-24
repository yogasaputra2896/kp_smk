<?php

namespace App\Models\Master;

use CodeIgniter\Model;

class MasterFasilitasModel extends Model
{
    protected $table            = 'master_fasilitas';
    protected $primaryKey       = 'id';

    protected $allowedFields    = [
        'kode', 'tipe_fasilitas', 'nama_fasilitas'
    ];

    protected $useTimestamps = true;
}
