<?php

namespace App\Models\WorksheetImportTrash;

use CodeIgniter\Model;

class WorksheetInformasiTambahanTrashModel extends Model
{
    protected $table            = 'worksheet_informasi_tambahan_import_trash';
    protected $primaryKey       = 'id';
    protected $allowedFields    = [
        'id_ws',
        'nama_pengurusan',
        'tgl_pengurusan',
        'created_at',
        'deleted_by',
        'deleted_at'
    ];

    public    $useTimestamps    = false; // karena hanya ada created_at
    
    /**
     * Ambil data informasi tambahan berdasarkan worksheet id
     */
    public function getByWorksheet($id_ws)
    {
        return $this->where('id_ws', $id_ws)->findAll();
    }
}
