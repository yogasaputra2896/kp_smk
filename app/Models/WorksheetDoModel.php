<?php

namespace App\Models;

use CodeIgniter\Model;

class WorksheetDoModel extends Model
{
    protected $table = 'worksheet_do_import';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'id_ws', 'tipe_do', 'pengambil_do', 'tgl_mati_do', 'created_at'
    ];

    // Hapus semua data DO berdasarkan ID worksheet
    public function deleteByWorksheet($id_ws)
    {
        return $this->where('id_ws', $id_ws)->delete();
    }
}
