<?php

namespace App\Models\WorksheetImportTrash;

use CodeIgniter\Model;

class WorkSheetImportTrashModel extends Model
{
    protected $table            = 'worksheet_import_trash';
    protected $primaryKey       = 'id';
    protected $allowedFields    = [
        'no_ws',
        'pengurusan_pib',
        'no_aju',
        'tgl_aju',
        'no_po',
        'io_number',
        'pib_nopen',
        'tgl_nopen',
        'penjaluran',
        'tgl_spjm',
        'tgl_sppb',
        'shipper',
        'consignee',
        'notify_party',
        'vessel',
        'no_voyage',
        'pol',
        'terminal',
        'shipping_line',
        'commodity',
        'party',
        'jenis_con',
        'qty',
        'kemasan',
        'net',
        'gross',
        'bl',
        'tgl_bl',
        'master_bl',
        'tgl_master',
        'no_invoice',
        'tgl_invoice',
        'eta',
        'dok_ori',
        'tgl_ori',
        'pengurusan_do',
        'asuransi',
        'top',
        'jenis_trucking',
        'pengurusan_lartas',
        'jenis_tambahan',
        'jenis_fasilitas',
        'berita_acara',
        'status',
        'created_at',
        'updated_at',
        'deleted_by',
        'deleted_at',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';
/**
     * Mapping data dari booking_job ke worksheet_import
     */
    public function mapFromBooking(array $booking): array
    {
        // Ambil dan ubah tipe ke huruf kecil
        $filterType = strtolower($booking['type'] ?? '');

        // Mapping jenis_con berdasarkan tipe booking
        switch ($filterType) {
            case 'import_lcl':
                $jenisCon = 'LCL';
                break;
            case 'import_fcl_jaminan':
            case 'import_fcl_nonjaminan':
            case 'export':
                $jenisCon = 'FCL';
                break;
            default:
                $jenisCon = 'FCL';
                break;
        }

        return [
            'no_ws'         => $booking['no_job'] ?? null,
            'no_aju'        => $booking['no_pib_po'] ?? null,
            'consignee'     => $booking['consignee'] ?? null,
            'party'         => $booking['party'] ?? null,
            'eta'           => $booking['eta'] ?? null,
            'pol'           => $booking['pol'] ?? null,
            'bl'            => $booking['bl'] ?? null,
            'master_bl'     => $booking['master_bl'] ?? null,
            'shipping_line' => $booking['shipping_line'] ?? null,
            'jenis_con'     => $jenisCon,
            'status'        => 'not completed',
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s')
        ];
    }

    /**
     * Format row untuk list (misalnya DataTables / API JSON)
     */
    public function formatListRow(array $r): array
    {
        return [
            'id'            => $r['id'],
            'no_ws'         => $r['no_ws'],
            'no_aju'        => $r['no_aju'] ?? null,
            'consignee'     => $r['consignee'] ?? null,
            'party'         => $r['party'] ?? null,
            'eta'           => $r['eta'] ?? null,
            'pol'           => $r['pol'] ?? null,
            'shipping_line' => $r['shipping_line'] ?? null,
            'bl'            => $r['bl'] ?? null,
            'master_bl'     => $r['master_bl'] ?? null,
            'jenis_con'     => $r['jenis_con'] ?? 'FCL', // default juga FCL di list
            'status'        => $r['status'] ?? 'not completed',
            'created_at'    => $r['created_at'] ?? null,
        ];
    }
}
