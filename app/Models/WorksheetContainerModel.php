<?php

namespace App\Models;

use CodeIgniter\Model;

class WorksheetContainerModel extends Model
{
    protected $table            = 'worksheet_container';
    protected $primaryKey       = 'id';
    protected $allowedFields    = [
        'id_ws',
        'no_container',
        'ukuran',
        'tipe',
        'created_at'
    ];

    protected $useTimestamps = false; // karena hanya ada created_at, bukan created_at & updated_at bawaan CI4

    /**
     * Ambil semua container berdasarkan id worksheet (id_ws)
     */
    public function getByWorksheet($id_ws)
    {
        return $this->where('id_ws', $id_ws)->findAll();
    }

    /**
     * Hapus semua container berdasarkan id worksheet (ketika update LCL)
     */
    public function deleteByWorksheet($id_ws)
    {
        return $this->where('id_ws', $id_ws)->delete();
    }
}
