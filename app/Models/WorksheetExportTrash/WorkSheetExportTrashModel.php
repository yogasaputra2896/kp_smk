<?php

namespace App\Models\WorksheetExportTrash;

use CodeIgniter\Model;

class WorkSheetExportTrashModel extends Model
{
    protected $table            = 'worksheet_export_trash';
    protected $primaryKey       = 'id';
    protected $allowedFields    = [
        'no_ws',
        'no_aju',
        'pengurusan_peb',
        'peb_nopen',
        'tgl_aju',
        'tgl_nopen',
        'no_po',
        'io_number',
        'penjaluran',
        'tgl_npe',
        'tgl_spjm',
        'shipper',
        'consignee',
        'notify_party',
        'vessel',
        'no_voyage',
        'pol',
        'pod',
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
        'etd',
        'closing',
        'stuffing',
        'depo',
        'terminal',
        'dok_ori',
        'tgl_ori',
        'pengurusan_do',
        'asuransi',
        'jenis_trucking',
        'jenis_fasilitas',
        'jenis_tambahan',
        'pengurusan_lartas',
        'top',
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
     * Mapping data dari booking_job ke worksheet_export
     */
    public function mapFromBooking(array $booking): array
    {
        $filterType = strtolower($booking['type'] ?? '');

        // Ekspor selalu FCL (kebanyakan kasus ekspor menggunakan container penuh)
        $jenisCon = 'FCL';

        return [
            'no_ws'         => $booking['no_job'] ?? null,
            'no_aju'        => $booking['no_pib_po'] ?? null,
            'shipper'       => $booking['consignee'] ?? null,
            'party'         => $booking['party'] ?? null,
            'etd'           => $booking['eta'] ?? null,
            'pod'           => $booking['pol'] ?? null,
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
     * Format data untuk list tampilan (misalnya di DataTables / API JSON)
     */
    public function formatListRow(array $r): array
    {
        return [
            'id'            => $r['id'],
            'no_ws'         => $r['no_ws'],
            'no_aju'        => $r['no_aju'] ?? null,
            'shipper'       => $r['consignee'] ?? null,
            'pod'           => $r['pol'] ?? null,
            'shipping_line' => $r['shipping_line'] ?? null,
            'bl'            => $r['bl'] ?? null,
            'master_bl'     => $r['master_bl'] ?? null,
            'jenis_con'     => $r['jenis_con'] ?? 'FCL',
            'status'        => $r['status'] ?? 'not completed',
            'created_at'    => $r['created_at'] ?? null,
        ];
    }
}
