<?php

namespace App\Models\WorksheetImport;

use CodeIgniter\Model;

class WorksheetFasilitasModel extends Model
{
    protected $table            = 'worksheet_fasilitas_import';
    protected $primaryKey       = 'id';
    protected $allowedFields    = [
        'id_ws',
        'tipe_fasilitas',
        'nama_fasilitas',
        'tgl_fasilitas',
        'no_fasilitas',
        'created_at'
    ];

    public $useTimestamps = false;

    /**
     * Ambil semua fasilitas berdasarkan id_ws
     */
    public function getByWorksheet($id_ws)
    {
        return $this->where('id_ws', $id_ws)->findAll();
    }
    
    public function getWithNoWs($ids)
    {
        return $this->select('worksheet_fasilitas_import.*, worksheet_import.no_ws')
            ->join('worksheet_import', 'worksheet_import.id = worksheet_fasilitas_import.id_ws')
            ->whereIn('worksheet_fasilitas_import.id_ws', $ids)
            ->findAll();
    }
}
