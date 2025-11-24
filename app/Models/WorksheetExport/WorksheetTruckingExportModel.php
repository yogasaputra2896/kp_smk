<?php

namespace App\Models\WorksheetExport;

use CodeIgniter\Model;

class WorksheetTruckingExportModel extends Model
{
    protected $table            = 'worksheet_trucking_export';
    protected $primaryKey       = 'id';
    protected $allowedFields    = [
        'id_ws',
        'no_mobil',
        'tipe_mobil',
        'nama_supir',
        'alamat',
        'telp_supir',
        'created_at'
    ];

    public $useTimestamps = false; // karena hanya ada created_at saja

    /**
     * Ambil data trucking berdasarkan worksheet id
     */
    public function getByWorksheet($id_ws)
    {
        return $this->where('id_ws', $id_ws)->findAll();
    }

    /**
     * Hapus semua data trucking berdasarkan id worksheet
     */
    public function deleteByWorksheet($id_ws)
    {
        return $this->where('id_ws', $id_ws)->delete();
    }

    /**
     * Simpan banyak data trucking sekaligus
     */
    public function insertBatchTrucking($id_ws, $data)
    {
        $insertData = [];
        $now = date('Y-m-d H:i:s');

        foreach ($data as $item) {
            if (!empty($item['no_mobil']) || !empty($item['nama_supir'])) {
                $insertData[] = [
                    'id_ws'      => $id_ws,
                    'no_mobil'   => trim($item['no_mobil']),
                    'tipe_mobil' => trim($item['tipe_mobil'] ?? ''),
                    'nama_supir' => trim($item['nama_supir'] ?? ''),
                    'alamat'     => trim($item['alamat'] ?? ''),
                    'telp_supir' => trim($item['telp_supir'] ?? ''),
                    'created_at' => $now
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
        return $this->select('worksheet_trucking_export.*, worksheet_export.no_ws')
            ->join('worksheet_export', 'worksheet_export.id = worksheet_trucking_export.id_ws')
            ->whereIn('worksheet_trucking_export.id_ws', $ids)
            ->findAll();
    }
}
