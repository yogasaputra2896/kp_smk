<?php

namespace App\Models\Bookingjob;

use CodeIgniter\Model;

class BookingJobTrashModel extends Model
{
    protected $table            = 'booking_job_trash';
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
        'status',
        'deleted_by',
        'deleted_at',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';
}
