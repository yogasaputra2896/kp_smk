<?php

namespace App\Models\WorksheetExport;

use CodeIgniter\Model;

class WorksheetDoExportModel extends Model
{
    protected $table = 'worksheet_do_export';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'id_ws',
        'tipe_do',
        'pengambil_do',
        'tgl_mati_do',
        'created_at'
    ];

    protected $useTimestamps = false; // hanya ada created_at

    /**
     * Hapus semua data DO berdasarkan ID worksheet (id_ws)
     */
    public function deleteByWorksheet($id_ws)
    {
        return $this->where('id_ws', $id_ws)->delete();
    }

    /**
     * Ambil semua data DO berdasarkan ID worksheet (id_ws)
     */
    public function getByWorksheet($id_ws)
    {
        return $this->where('id_ws', $id_ws)->findAll();
    }
}
