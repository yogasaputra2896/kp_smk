<?php

namespace App\Models\Master;

use CodeIgniter\Model;

class MasterInformasiTambahanModel extends Model
{
    protected $table            = 'master_informasi_tambahan';
    protected $primaryKey       = 'id';

    protected $allowedFields    = [
        'kode', 'nama_pengurusan'
    ];

    protected $useTimestamps = true;
}
