<?php

namespace App\Models;

use CodeIgniter\Model;

class WorksheetTruckingModel extends Model
{
    protected $table            = 'worksheet_trucking';
    protected $primaryKey       = 'id';
    protected $allowedFields    = [
        'id_ws',
        'no_mobil',
        'tipe_mobil',
        'nama_supir',
        'telp_supir',
        'created_at'
    ];

    public    $useTimestamps    = false; // karena hanya ada created_at saja

    /**
     * Ambil data trucking berdasarkan worksheet id
     */
    public function getByWorksheet($id_ws)
    {
        return $this->where('id_ws', $id_ws)->findAll();
    }
}
