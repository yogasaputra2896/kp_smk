<?php

namespace App\Models\WorksheetExport;

use CodeIgniter\Model;

class WorksheetInformasiTambahanExportModel extends Model
{
    protected $table            = 'worksheet_informasi_tambahan_export';
    protected $primaryKey       = 'id';
    protected $allowedFields    = [
        'id_ws',
        'nama_pengurusan',
        'tgl_pengurusan',
        'created_at'
    ];

    protected $useTimestamps    = false; // hanya ada created_at

    /**
     * Ambil data informasi tambahan berdasarkan worksheet id
     */
    public function getByWorksheet($id_ws)
    {
        return $this->where('id_ws', $id_ws)->findAll();
    }

    /**
     * Hapus data informasi tambahan berdasarkan worksheet id
     */
    public function deleteByWorksheet($id_ws)
    {
        return $this->where('id_ws', $id_ws)->delete();
    }
}
