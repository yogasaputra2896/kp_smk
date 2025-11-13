<?php

namespace App\Models\WorksheetImport;

use CodeIgniter\Model;

class WorksheetTruckingModel extends Model
{
    protected $table            = 'worksheet_trucking_import';
    protected $primaryKey       = 'id';
    protected $allowedFields    = [
        'id_ws',
        'no_mobil',
        'tipe_mobil',
        'nama_supir',
        'alamat',
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
