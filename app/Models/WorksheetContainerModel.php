<?php

namespace App\Models;

use CodeIgniter\Model;

class WorksheetContainerModel extends Model
{
    protected $table            = 'worksheet_container';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['id_ws', 'no_container', 'tipe', 'created_at'];
    public    $useTimestamps    = false; // karena kamu hanya punya created_at saja

    public function getByWorksheet($id_ws)
    {
        return $this->where('id_ws', $id_ws)->findAll();
    }
}
