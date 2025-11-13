<?php

namespace App\Models\WorksheetExport;

use CodeIgniter\Model;

class WorksheetFasilitasExportModel extends Model
{
    protected $table            = 'worksheet_fasilitas_export';
    protected $primaryKey       = 'id';
    protected $allowedFields    = [
        'id_ws',
        'tipe_fasilitas',
        'nama_fasilitas',
        'tgl_fasilitas',
        'no_fasilitas',
        'created_at'
    ];

    protected $useTimestamps = false;

    /**
     * Ambil semua fasilitas berdasarkan id_ws
     */
    public function getByWorksheet($id_ws)
    {
        return $this->where('id_ws', $id_ws)->findAll();
    }

    /**
     * Hapus semua fasilitas berdasarkan id_ws
     */
    public function deleteByWorksheet($id_ws)
    {
        return $this->where('id_ws', $id_ws)->delete();
    }
}
