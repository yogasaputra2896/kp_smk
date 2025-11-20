<?php

namespace App\Models\WorksheetImportTrash;

use CodeIgniter\Model;

class WorksheetFasilitasTrashModel extends Model
{
    protected $table            = 'worksheet_fasilitas_import_trash';
    protected $primaryKey       = 'id';
    protected $allowedFields    = [
        'id_ws',
        'tipe_fasilitas',
        'nama_fasilitas',
        'tgl_fasilitas',
        'no_fasilitas',
        'created_at',
        'deleted_by',
        'deleted_at',
    ];

    public $useTimestamps = false;

    /**
     * Ambil semua fasilitas berdasarkan id_ws
     */
    public function getByWorksheet($id_ws)
    {
        return $this->where('id_ws', $id_ws)->findAll();
    }
}
