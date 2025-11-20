<?php

namespace App\Models\WorksheetImportTrash;

use CodeIgniter\Model;

class WorksheetDoTrashModel extends Model
{
    protected $table = 'worksheet_do_import_trash';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'id_ws', 
        'tipe_do', 
        'pengambil_do', 
        'tgl_mati_do', 
        'created_at',
        'deleted_by',
        'deleted_at'
    ];

    // Hapus semua data DO berdasarkan ID worksheet
    public function deleteByWorksheet($id_ws)
    {
        return $this->where('id_ws', $id_ws)->delete();
    }
}
