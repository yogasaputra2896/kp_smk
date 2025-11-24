<?php

namespace App\Models\WorksheetImport;

use CodeIgniter\Model;

class WorksheetLartasModel extends Model
{
    protected $table            = 'worksheet_lartas_import';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields    = [
        'id_ws',
        'nama_lartas',
        'no_lartas',
        'tgl_lartas',
        'created_at'
    ];

    protected $useTimestamps = false;

    /**
     * Ambil semua data lartas berdasarkan id worksheet
     */
    public function getByWorksheet($id_ws)
    {
        return $this->where('id_ws', $id_ws)->findAll();
    }

    /**
     * Hapus semua data lartas berdasarkan id worksheet
     */
    public function deleteByWorksheet($id_ws)
    {
        return $this->where('id_ws', $id_ws)->delete();
    }

    /**
     * Simpan banyak data lartas sekaligus
     */
    public function insertBatchLartas($id_ws, $data)
    {
        $insertData = [];
        $now = date('Y-m-d H:i:s');

        foreach ($data as $item) {
            if (!empty($item['nama_lartas']) || !empty($item['no_lartas'])) {
                $insertData[] = [
                    'id_ws'       => $id_ws,
                    'nama_lartas' => trim($item['nama_lartas']),
                    'no_lartas'   => trim($item['no_lartas']),
                    'tgl_lartas'  => !empty($item['tgl_lartas']) ? $item['tgl_lartas'] : null,
                    'created_at'  => $now
                ];
            }
        }

        if (!empty($insertData)) {
            return $this->insertBatch($insertData);
        }
        return false;
    }
    public function getWithNoWs($ids)
    {
        return $this->select('worksheet_lartas_import.*, worksheet_import.no_ws')
            ->join('worksheet_import', 'worksheet_import.id = worksheet_lartas_import.id_ws')
            ->whereIn('worksheet_lartas_import.id_ws', $ids)
            ->findAll();
    }
}
