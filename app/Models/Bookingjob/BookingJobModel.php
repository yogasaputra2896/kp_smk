<?php

namespace App\Models\Bookingjob;

use CodeIgniter\Model;

class BookingJobModel extends Model
{
    protected $table            = 'booking_job';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;

    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields    = [
        'no_job',
        'type',
        'consignee',
        'party',
        'eta',
        'pol',
        'no_pib_po',
        'shipping_line',
        'bl',
        'master_bl',
        'status'
    ];

    // otomatis isi created_at & updated_at
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
