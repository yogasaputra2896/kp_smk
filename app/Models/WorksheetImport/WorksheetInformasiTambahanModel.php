<?php

namespace App\Models\WorksheetImport;

use CodeIgniter\Model;

class WorksheetInformasiTambahanModel extends Model
{
    protected $table            = 'worksheet_informasi_tambahan_import';
    protected $primaryKey       = 'id';
    protected $allowedFields    = [
        'id_ws',
        'nama_pengurusan',
        'tgl_pengurusan',
        'created_at'
    ];

    public    $useTimestamps    = false; // karena hanya ada created_at

    /**
     * Ambil data informasi tambahan berdasarkan worksheet id
     */
    public function getByWorksheet($id_ws)
    {
        return $this->where('id_ws', $id_ws)->findAll();
    }
    public function getWithNoWs($ids)
    {
        return $this->select('worksheet_informasi_tambahan_import.*, worksheet_import.no_ws')
            ->join('worksheet_import', 'worksheet_import.id = worksheet_informasi_tambahan_import.id_ws')
            ->whereIn('worksheet_informasi_tambahan_import.id_ws', $ids)
            ->findAll();
    }
}
