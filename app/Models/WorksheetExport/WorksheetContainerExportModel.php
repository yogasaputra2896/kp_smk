<?php

namespace App\Models\WorksheetExport;

use CodeIgniter\Model;

class WorksheetContainerExportModel extends Model
{
    protected $table            = 'worksheet_container_export';
    protected $primaryKey       = 'id';
    protected $allowedFields    = [
        'id_ws',
        'no_container',
        'ukuran',
        'tipe',
        'created_at'
    ];

    protected $useTimestamps = false; // karena hanya ada created_at

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

    public function getWithNoWs($ids)
    {
        return $this->select('worksheet_container_export.*, worksheet_export.no_ws')
            ->join('worksheet_export', 'worksheet_export.id = worksheet_container_export.id_ws')
            ->whereIn('worksheet_container_export.id_ws', $ids)
            ->findAll();
    }
}
