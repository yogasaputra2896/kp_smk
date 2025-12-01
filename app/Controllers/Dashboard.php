<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Bookingjob\BookingJobModel;
use App\Models\Bookingjob\BookingJobTrashModel;
use App\Models\WorksheetImport\WorkSheetImportModel;
use App\Models\WorksheetExport\WorkSheetExportModel;
use App\Models\WorksheetImportTrash\WorkSheetImportTrashModel;
use App\Models\WorksheetExportTrash\WorkSheetExportTrashModel;
use App\Models\User\UserModel;
use App\Models\User\UserTrashModel;

class Dashboard extends BaseController
{
    protected $bookingJobModel;
    protected $bookingJobTrashModel;
    protected $worksheetImportModel;
    protected $worksheetExportModel;
    protected $worksheetImportTrashModel;
    protected $worksheetExportTrashModel;
    protected $userModel;
    protected $userTrashModel;

    public function __construct()
    {
        $this->bookingJobModel            = new BookingJobModel();
        $this->bookingJobTrashModel       = new BookingJobTrashModel();
        $this->worksheetImportModel       = new WorkSheetImportModel();
        $this->worksheetExportModel       = new WorkSheetExportModel();
        $this->worksheetImportTrashModel  = new WorkSheetImportTrashModel();
        $this->worksheetExportTrashModel  = new WorkSheetExportTrashModel();
        $this->userModel                  = new UserModel();
        $this->userTrashModel             = new UserTrashModel();
    }

    public function index()
    {
        $user = user(); // data user yang login

        // Statistik umum
        $totalBooking           = $this->bookingJobModel->countAllResults();
        $totalTrashBooking      = $this->bookingJobTrashModel->countAllResults();

        // Total booking berdasarkan status
        $totalOpenJob = $this->bookingJobModel->where('status', 'open job')->countAllResults();
        $totalWorksheetStatus = $this->bookingJobModel->where('status', 'worksheet')->countAllResults();

        $totalWorksheetImport   = $this->worksheetImportModel->countAllResults();
        $totalWorksheetExport   = $this->worksheetExportModel->countAllResults();
        $totalWorksheet         = $totalWorksheetImport + $totalWorksheetExport;
        $totalUser              = $this->userModel->countAllResults();
        $totalDeletedUser       = $this->userTrashModel->countAllResults();

        // Tambahkan total worksheet yang sudah dihapus (trash)
        $totalDeletedWorksheetImport = $this->worksheetImportTrashModel->countAllResults();
        $totalDeletedWorksheetExport = $this->worksheetExportTrashModel->countAllResults();
        $totalDeletedWorksheet       = $totalDeletedWorksheetImport + $totalDeletedWorksheetExport;

        // Booking Job per type
        $bookingByType = $this->bookingJobModel
            ->select('type, COUNT(id) as total')
            ->groupBy('type')
            ->findAll();

        // User per role
        $db = \Config\Database::connect();
        $userByRole = $db->table('auth_groups_users ug')
            ->select('g.name as role, COUNT(ug.user_id) as total')
            ->join('auth_groups g', 'g.id = ug.group_id')
            ->groupBy('g.name')
            ->get()
            ->getResultArray();

        // Top 5 consignee
        $topConsignees = $this->bookingJobModel
            ->select('consignee, COUNT(id) as total')
            ->groupBy('consignee')
            ->orderBy('total', 'DESC')
            ->limit(5)
            ->findAll();

        // Data grafik Booking Job per type
        $chartBookingJob = $this->bookingJobModel
            ->select('type, COUNT(id) as total')
            ->groupBy('type')
            ->get()
            ->getResultArray();

        // Data grafik Worksheet
        $chartWorksheet = [
            'import' => $totalWorksheetImport,
            'export' => $totalWorksheetExport
        ];

        $data = [
            'user'                        => $user,
            'title'                       => 'Dashboard',
            'totalBooking'                => $totalBooking,
            'bookingByType'               => $bookingByType,
            'totalTrashBooking'           => $totalTrashBooking,
            'totalOpenJob'                => $totalOpenJob,
            'totalWorksheetStatus'        => $totalWorksheetStatus,
            'totalWorksheet'              => $totalWorksheet,
            'totalWorksheetImport'        => $totalWorksheetImport,
            'totalWorksheetExport'        => $totalWorksheetExport,
            'totalDeletedWorksheet'       => $totalDeletedWorksheet, // tambahan
            'totalUser'                   => $totalUser,
            'totalDeletedUser'            => $totalDeletedUser,
            'userByRole'                  => $userByRole,
            'topConsignees'               => $topConsignees,
            'chartBookingJob'             => $chartBookingJob,
            'chartWorksheet'              => $chartWorksheet,
        ];

        // Tampilkan view sesuai role
        switch (true) {
            case in_groups('admin'):
                return view('dashboard/admin', $data);

            case in_groups('exim'):
                return view('dashboard/exim', $data);

            case in_groups('document'):
                return view('dashboard/document', $data);

            default:
                return redirect()->to('login')
                    ->with('error', 'Akun Anda belum memiliki role.');
        }
    }
}
