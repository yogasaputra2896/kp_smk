<?php

namespace App\Models\WorksheetImport;

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
    
    public function getWithNoWs($ids)
    {
        return $this->select('worksheet_do_import.*, worksheet_import.no_ws')
            ->join('worksheet_import', 'worksheet_import.id = worksheet_do_import.id_ws')
            ->whereIn('worksheet_do_import.id_ws', $ids)
            ->findAll();
    }
}
