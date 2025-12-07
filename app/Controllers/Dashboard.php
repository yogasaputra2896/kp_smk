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
use App\Models\Log\LogActivityModel;

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
    protected $logActivityModel;

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
        $this->logActivityModel           = new LogActivityModel();
    }

    public function index()
    {
        /**
         * @var \Myth\Auth\Entities\User $user
         */
        $user = user(); // data user yang login

        // Ambil role user sekarang
        $groupModel = model('Myth\Auth\Models\GroupModel');
        $roles = $groupModel->getGroupsForUser($user->id);
        $currentRole = isset($roles[0]['name']) ? $roles[0]['name'] : 'unknown';

        // === LOG ACTIVITY ===
        $logs = $this->logActivityModel->getLogsByRole($currentRole);

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

        // Booking Job per hari dalam 1 bulan ini
        $startMonth = date('Y-m-01'); // tanggal 1 bulan ini
        $endMonth   = date('Y-m-t');  // tanggal terakhir bulan ini

        // Booking Job per type
        $bookingByType = $this->bookingJobModel
            ->select('type, COUNT(id) as total')
            ->groupBy('type')
            ->findAll();

        $bookingPerDay = $this->bookingJobModel
            ->select("DATE(created_at) as date, COUNT(id) as total")
            ->where("DATE(created_at) >=", $startMonth)
            ->where("DATE(created_at) <=", $endMonth)
            ->groupBy("DATE(created_at)")
            ->orderBy("date", "ASC")
            ->findAll();

        // Worksheet per hari (import + export)
        $db = \Config\Database::connect();

        $worksheetPerDay = $db->query("
            SELECT DATE(created_at) AS date, COUNT(*) AS total
            FROM (
                SELECT created_at FROM worksheet_import
                UNION ALL
                SELECT created_at FROM worksheet_export
            ) AS ws
            WHERE DATE(created_at) >= ?
            AND DATE(created_at) <= ?
            GROUP BY DATE(created_at)
            ORDER BY DATE(created_at) ASC
        ", [$startMonth, $endMonth])->getResultArray();



        // User per role
        $db = \Config\Database::connect();
        $userByRole = $db->table('auth_groups_users AS ug')
            ->select('g.name AS role, COUNT(ug.user_id) AS total')
            ->join('auth_groups AS g', 'g.id = ug.group_id', 'left')
            ->groupBy('g.name')
            ->orderBy('total', 'DESC')
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

        // Completed Import
        $completedImport = $this->worksheetImportModel
            ->where('status', 'completed')
            ->countAllResults();

        // Completed Export
        $completedExport = $this->worksheetExportModel
            ->where('status', 'completed')
            ->countAllResults();

        $statusCompleted = $completedImport + $completedExport;


        // Not Completed Import
        $notCompletedImport = $this->worksheetImportModel
            ->where('status !=', 'completed')
            ->countAllResults();

        // Not Completed Export
        $notCompletedExport = $this->worksheetExportModel
            ->where('status !=', 'completed')
            ->countAllResults();

        $statusNotCompleted = $notCompletedImport + $notCompletedExport;



        $data = [
            'user'                        => $user,
            'title'                       => 'Dashboard',
            'totalBooking'                => $totalBooking,
            'bookingByType'               => $bookingByType,
            'bookingPerDay'               => $bookingPerDay,
            'totalTrashBooking'           => $totalTrashBooking,
            'totalOpenJob'                => $totalOpenJob,
            'totalWorksheetStatus'        => $totalWorksheetStatus,
            'totalWorksheet'              => $totalWorksheet,
            'totalWorksheetImport'        => $totalWorksheetImport,
            'totalWorksheetExport'        => $totalWorksheetExport,
            'totalDeletedWorksheet'       => $totalDeletedWorksheet,
            'totalUser'                   => $totalUser,
            'totalDeletedUser'            => $totalDeletedUser,
            'userByRole'                  => $userByRole,
            'topConsignees'               => $topConsignees,
            'chartBookingJob'             => $chartBookingJob,
            'chartWorksheet'              => $chartWorksheet,
            'logs'                        => $logs,
            'statusCompleted'             => $statusCompleted,
            'statusNotCompleted'          => $statusNotCompleted,
            'worksheetPerDay'             => $worksheetPerDay

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
