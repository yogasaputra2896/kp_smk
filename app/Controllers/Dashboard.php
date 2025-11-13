<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Bookingjob\BookingJobModel;
use App\Models\Bookingjob\BookingJobTrashModel;
use App\Models\WorksheetImport\WorkSheetImportModel;
use App\Models\WorksheetExport\WorkSheetExportModel;
use App\Models\User\UserModel;
use App\Models\User\UserTrashModel;

class Dashboard extends BaseController
{
    protected $bookingJobModel;
    protected $bookingJobTrashModel;
    protected $worksheetImportModel;
    protected $worksheetExportModel;
    protected $userModel;
    protected $userTrashModel;

    public function __construct()
    {
        $this->bookingJobModel       = new BookingJobModel();
        $this->bookingJobTrashModel  = new BookingJobTrashModel();
        $this->worksheetImportModel  = new WorkSheetImportModel();
        $this->worksheetExportModel  = new WorkSheetExportModel();
        $this->userModel             = new UserModel();
        $this->userTrashModel        = new UserTrashModel();
    }

    public function index()
    {
        $user = user(); // data user yang login

        // Statistik umum
        $totalBooking          = $this->bookingJobModel->countAllResults();
        $totalTrashBooking     = $this->bookingJobTrashModel->countAllResults();
        $totalWorksheetImport  = $this->worksheetImportModel->countAllResults();
        $totalWorksheetExport  = $this->worksheetExportModel->countAllResults();
        $totalWorksheet        = $totalWorksheetImport + $totalWorksheetExport;
        $totalUser             = $this->userModel->countAllResults();
        $totalDeletedUser     = $this->userTrashModel->countAllResults();

        // Booking Job per type
        $bookingByType = $this->bookingJobModel
            ->select('type, COUNT(id) as total')
            ->groupBy('type')
            ->findAll();

        // User per role (admin, staff, accounting)
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
            'user'                 => $user,
            'title'                => 'Dashboard',
            'totalBooking'         => $totalBooking,
            'bookingByType'        => $bookingByType,
            'totalTrashBooking'    => $totalTrashBooking,
            'totalWorksheet'       => $totalWorksheet,
            'totalWorksheetImport' => $totalWorksheetImport,
            'totalWorksheetExport' => $totalWorksheetExport,
            'totalUser'            => $totalUser,
            'totalDeletedUser'     => $totalDeletedUser, // disertakan di data
            'userByRole'           => $userByRole,
            'topConsignees'        => $topConsignees,
            'chartBookingJob'      => $chartBookingJob,
            'chartWorksheet'       => $chartWorksheet,
        ];

        // Tampilkan view sesuai role
        switch (true) {
            case in_groups('admin'):
                return view('dashboard/admin', $data);

            case in_groups('staff'):
                return view('dashboard/staff', $data);

            case in_groups('accounting'):
                return view('dashboard/accounting', $data);

            default:
                return redirect()->to('/')
                    ->with('error', 'Anda tidak punya akses ke dashboard.');
        }
    }
}
