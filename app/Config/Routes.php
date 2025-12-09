<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

//
// --------------------------------------------------------------
// DEFAULT ROUTES
// --------------------------------------------------------------
//

$routes->get('/', function () {
    return redirect()->to('dashboard');
});

//
// --------------------------------------------------------------
// LOAD ROUTES DARI MYTH/AUTH TERLEBIH DAHULU
// AGAR BISA KITA OVERRIDE SETELAHNYA
// --------------------------------------------------------------
//

//
// --------------------------------------------------------------
// OVERRIDE ROUTES LOGIN/LOGOUT DENGAN CONTROLLER KAMU
// --------------------------------------------------------------
//
$routes->group('', ['namespace' => 'App\Controllers'], function ($routes) {
    $routes->get('login', 'AuthController::login');
    $routes->post('login', 'AuthController::attemptLogin');
    $routes->get('logout', 'AuthController::logout');
});

//
// --------------------------------------------------------------
// DASHBOARD
// --------------------------------------------------------------
//
$routes->get('dashboard', 'Dashboard::index', ['filter' => 'login']);

//
// --------------------------------------------------------------
// BOOKING JOB
// --------------------------------------------------------------
//
$routes->group('booking-job', ['filter' => 'role:admin,exim'], function ($routes) {
    $routes->get('/', 'BookingJob::index');
    $routes->get('list', 'BookingJob::list');
    $routes->get('nextno', 'BookingJob::nextNo');
    $routes->get('generate-no-pib', 'BookingJob::generateNoPIB');
    $routes->get('search-consignee', 'BookingJob::searchConsignee');
    $routes->get('search-port', 'BookingJob::searchPort');
    $routes->get('search-pelayaran', 'BookingJob::searchPelayaran');
    $routes->get('add', 'BookingJob::addPage');
    $routes->get('edit/(:num)', 'BookingJob::editPage/$1');;
    $routes->get('export-excel-range', 'BookingJob::exportExcelRange');
    $routes->get('export-pdf-range', 'BookingJob::exportPdfRange');
    $routes->get('print-note/(:segment)', 'BookingJob::printNote/$1');
    $routes->post('store', 'BookingJob::store');
    $routes->post('update/(:num)', 'BookingJob::update/$1');
    $routes->post('delete/(:num)', 'BookingJob::delete/$1');
    $routes->post('send-to-worksheet/(:num)', 'BookingJob::sendToWorksheet/$1');
});

//
// --------------------------------------------------------------
// BOOKING JOB TRASH
// --------------------------------------------------------------
//
$routes->group('booking-job-trash', ['filter' => 'role:admin,exim'], function ($routes) {
    $routes->get('/', 'BookingJobTrash::index');
    $routes->get('list', 'BookingJobTrash::list');
    $routes->post('restore/(:num)', 'BookingJobTrash::restore/$1');
    $routes->post('delete-permanent/(:num)', 'BookingJobTrash::deletePermanent/$1');
});

// --------------------------------------------------------------
// WORKSHEET
// --------------------------------------------------------------
$routes->group('worksheet', ['filter' => 'role:admin,document'], function ($routes) {

    // ==========================
    // MAIN
    // ==========================
    $routes->get('/', 'Worksheet::index');
    $routes->get('list', 'Worksheet::list');
    $routes->get('create', 'Worksheet::create');
    $routes->post('store', 'Worksheet::store');

    // ==========================
    // IMPORT
    // ==========================
    $routes->get('import/edit/(:num)', 'Worksheet::editImport/$1');
    $routes->post('import/update/(:num)', 'Worksheet::updateImport/$1');
    $routes->get('checkImport/(:num)', 'Worksheet::checkImport/$1');
    $routes->get('print-import/(:segment)', 'Worksheet::printImport/$1');
    $routes->post('import/delete/(:num)', 'Worksheet::deleteImport/$1');

    // ==========================
    // EXPORT
    // ==========================
    $routes->get('export/edit/(:num)', 'Worksheet::editExport/$1');
    $routes->post('export/update/(:num)', 'Worksheet::updateExport/$1');
    $routes->get('checkExport/(:num)', 'Worksheet::checkExport/$1');
    $routes->get('print-export/(:segment)', 'Worksheet::printExport/$1');
    $routes->post('export/delete/(:num)', 'Worksheet::deleteExport/$1');

    // ==========================
    // MASTER DATA (SELECT2 AJAX)
    // ==========================
    $routes->get('search/consignee', 'Worksheet::searchConsignee');
    $routes->get('search/notify-party', 'Worksheet::searchNotifyParty');
    $routes->get('search/port', 'Worksheet::searchPort');
    $routes->get('search/pelayaran', 'Worksheet::searchPelayaran');
    $routes->get('search/vessel', 'Worksheet::searchVessel');
    $routes->get('search/fasilitas', 'Worksheet::searchFasilitas');
    $routes->get('search/lartas', 'Worksheet::searchLartas');
    $routes->get('search/kemasan', 'Worksheet::searchKemasan');
    $routes->get('search/informasi-tambahan', 'Worksheet::searchInformasiTambahan');
    $routes->get('search/lokasi-sandar', 'Worksheet::searchLokasiSandar');

    // ==========================
    // MASTER SEARCH (GLOBAL SEARCH)
    // ==========================
    $routes->get('master-search/(:segment)', 'Worksheet::masterSearch/$1');

    // ==========================
    // REDIRECT
    // ==========================
    $routes->get('redirectToBooking', 'Worksheet::redirectToBooking');
});


//
// WORKSHEET IMPORT/EXPORT HELPER ROUTES (no filter)
//
$routes->get('worksheet-import/get-years', 'Worksheet::getImportYears');
$routes->get('worksheet-import/get-months/(:num)', 'Worksheet::getImportMonths/$1');
$routes->get('worksheet-import/export-excel', 'Worksheet::exportImportExcel');
$routes->get('worksheet-import/export-pdf', 'Worksheet::exportImportPDF');

$routes->get('worksheet-export/get-years', 'Worksheet::getExportYears');
$routes->get('worksheet-export/get-months/(:num)', 'Worksheet::getExportMonths/$1');
$routes->get('worksheet-export/export-excel', 'Worksheet::exportExportExcel');
$routes->get('worksheet-export/export-pdf', 'Worksheet::exportExportPDF');


//
// --------------------------------------------------------------
// WORKSHEET TRASH (Import & Export)
// --------------------------------------------------------------
//
$routes->group('worksheet-import-trash', ['filter' => 'role:admin,document'], function ($routes) {
    $routes->get('/', 'WorksheetImportTrash::index');
    $routes->get('list', 'WorksheetImportTrash::list');
    $routes->post('restore/(:segment)/(:num)', 'WorksheetImportTrash::restore/$1/$2');
    $routes->post('delete-permanent/(:segment)/(:num)', 'WorksheetImportTrash::deletePermanent/$1/$2');
});

$routes->group('worksheet-export-trash', ['filter' => 'role:admin,document'], function ($routes) {
    $routes->get('/', 'WorksheetExportTrash::index');
    $routes->get('list', 'WorksheetExportTrash::list');
    $routes->post('restore/(:segment)/(:num)', 'WorksheetExportTrash::restore/$1/$2');
    $routes->post('delete-permanent/(:segment)/(:num)', 'WorksheetExportTrash::deletePermanent/$1/$2');
});

//
// --------------------------------------------------------------
// MASTER DATA (Consignee, Pelayaran, Port, etc.)
// --------------------------------------------------------------
//
$routes->group('master-data', ['filter' => 'role:admin,exim,document'], function ($routes) {

    // CONSIGNEE
    $routes->group('consignee', function ($routes) {
        $routes->get('/', 'MasterConsignee::index');
        $routes->get('list', 'MasterConsignee::list');
        $routes->get('search/kode', 'MasterConsignee::searchKode');
        $routes->get('search/nama', 'MasterConsignee::searchNama');
        $routes->get('search/npwp', 'MasterConsignee::searchNpwp');
        $routes->post('store', 'MasterConsignee::store');
        $routes->get('edit/(:num)', 'MasterConsignee::edit/$1');
        $routes->post('update/(:num)', 'MasterConsignee::update/$1');
        $routes->post('delete/(:num)', 'MasterConsignee::delete/$1');
    });

    // PELAYARAN
    $routes->group('pelayaran', function ($routes) {
        $routes->get('/', 'MasterPelayaran::index');
        $routes->get('list', 'MasterPelayaran::list');
        $routes->get('search/kode', 'MasterPelayaran::searchKode');
        $routes->get('search/nama', 'MasterPelayaran::searchNama');
        $routes->get('search/npwp', 'MasterPelayaran::searchNpwp');
        $routes->post('store', 'MasterPelayaran::store');
        $routes->get('edit/(:num)', 'MasterPelayaran::edit/$1');
        $routes->post('update/(:num)', 'MasterPelayaran::update/$1');
        $routes->post('delete/(:num)', 'MasterPelayaran::delete/$1');
    });

    // PORT
    $routes->group('port', function ($routes) {
        $routes->get('/', 'MasterPort::index');
        $routes->get('list', 'MasterPort::list');
        $routes->get('search/kode', 'MasterPort::searchKode');
        $routes->get('search/nama', 'MasterPort::searchNama');
        $routes->get('search/negara', 'MasterPort::searchNegara');
        $routes->post('store', 'MasterPort::store');
        $routes->get('edit/(:num)', 'MasterPort::edit/$1');
        $routes->post('update/(:num)', 'MasterPort::update/$1');
        $routes->post('delete/(:num)', 'MasterPort::delete/$1');
    });

    // NOTIFY PARTY
    $routes->group('notify-party', function ($routes) {
        $routes->get('/', 'MasterNotifyParty::index');
        $routes->get('list', 'MasterNotifyParty::list');
        $routes->get('search/kode', 'MasterNotifyParty::searchKode');
        $routes->get('search/nama', 'MasterNotifyParty::searchNama');
        $routes->get('search/npwp', 'MasterNotifyParty::searchNpwp');
        $routes->post('store', 'MasterNotifyParty::store');
        $routes->get('edit/(:num)', 'MasterNotifyParty::edit/$1');
        $routes->post('update/(:num)', 'MasterNotifyParty::update/$1');
        $routes->post('delete/(:num)', 'MasterNotifyParty::delete/$1');
    });

    // VESSEL
    $routes->group('vessel', function ($routes) {
        $routes->get('/', 'MasterVessel::index');
        $routes->get('list', 'MasterVessel::list');
        $routes->get('search/kode', 'MasterVessel::searchKode');
        $routes->get('search/nama', 'MasterVessel::searchNama');
        $routes->get('search/negara', 'MasterVessel::searchNegara');
        $routes->post('store', 'MasterVessel::store');
        $routes->get('edit/(:num)', 'MasterVessel::edit/$1');
        $routes->post('update/(:num)', 'MasterVessel::update/$1');
        $routes->post('delete/(:num)', 'MasterVessel::delete/$1');
    });

    // LOKASI SANDAR
    $routes->group('lokasi-sandar', function ($routes) {
        $routes->get('/', 'MasterLokasiSandar::index');
        $routes->get('list', 'MasterLokasiSandar::list');
        $routes->get('search/kode', 'MasterLokasiSandar::searchKode');
        $routes->get('search/nama', 'MasterLokasiSandar::searchNama');
        $routes->post('store', 'MasterLokasiSandar::store');
        $routes->get('edit/(:num)', 'MasterLokasiSandar::edit/$1');
        $routes->post('update/(:num)', 'MasterLokasiSandar::update/$1');
        $routes->post('delete/(:num)', 'MasterLokasiSandar::delete/$1');
    });

    // KEMASAN
    $routes->group('kemasan', function ($routes) {
        $routes->get('/', 'MasterKemasan::index');
        $routes->get('list', 'MasterKemasan::list');
        $routes->get('search/kode', 'MasterKemasan::searchKode');
        $routes->get('search/nama', 'MasterKemasan::searchNama');
        $routes->post('store', 'MasterKemasan::store');
        $routes->get('edit/(:num)', 'MasterKemasan::edit/$1');
        $routes->post('update/(:num)', 'MasterKemasan::update/$1');
        $routes->post('delete/(:num)', 'MasterKemasan::delete/$1');
    });

    // LARTAS
    $routes->group('lartas', function ($routes) {
        $routes->get('/', 'MasterLartas::index');
        $routes->get('list', 'MasterLartas::list');
        $routes->get('search/kode', 'MasterLartas::searchKode');
        $routes->get('search/nama', 'MasterLartas::searchNama');
        $routes->post('store', 'MasterLartas::store');
        $routes->get('edit/(:num)', 'MasterLartas::edit/$1');
        $routes->post('update/(:num)', 'MasterLartas::update/$1');
        $routes->post('delete/(:num)', 'MasterLartas::delete/$1');
    });

    // FASILITAS
    $routes->group('fasilitas', function ($routes) {
        $routes->get('/', 'MasterFasilitas::index');
        $routes->get('list', 'MasterFasilitas::list');
        $routes->get('search/kode', 'MasterFasilitas::searchKode');
        $routes->get('search/tipe', 'MasterFasilitas::searchTipe');
        $routes->get('search/nama', 'MasterFasilitas::searchNama');
        $routes->post('store', 'MasterFasilitas::store');
        $routes->get('edit/(:num)', 'MasterFasilitas::edit/$1');
        $routes->post('update/(:num)', 'MasterFasilitas::update/$1');
        $routes->post('delete/(:num)', 'MasterFasilitas::delete/$1');
    });

    // INFORMASI TAMBAHAN
    $routes->group('info-tambahan', function ($routes) {
        $routes->get('/', 'MasterInformasiTambahan::index');
        $routes->get('list', 'MasterInformasiTambahan::list');
        $routes->get('search/kode', 'MasterInformasiTambahan::searchKode');
        $routes->get('search/nama', 'MasterInformasiTambahan::searchNama');
        $routes->post('store', 'MasterInformasiTambahan::store');
        $routes->get('edit/(:num)', 'MasterInformasiTambahan::edit/$1');
        $routes->post('update/(:num)', 'MasterInformasiTambahan::update/$1');
        $routes->post('delete/(:num)', 'MasterInformasiTambahan::delete/$1');
    });
});

// --------------------------------------------------------------
// USER MANAGEMENT (khusus admin)
// --------------------------------------------------------------
$routes->group('user-management', ['filter' => 'role:admin'], function ($routes) {


    $routes->get('/', 'UserManagement::index');
    $routes->get('list', 'UserManagement::list');
    $routes->post('store', 'UserManagement::store');
    $routes->get('edit/(:num)', 'UserManagement::edit/$1');
    $routes->post('update/(:num)', 'UserManagement::update/$1');
    $routes->post('delete/(:num)', 'UserManagement::delete/$1');
});

// ============================
//      USER TRASH MODULE
// ============================

$routes->group('user-management-trash', ['filter' => 'role:admin'], function ($routes) {
    $routes->get('/', 'UserManagement::trashView');
    $routes->get('trash-list', 'UserManagement::trashList');
    $routes->post('restore/(:num)', 'UserManagement::restore/$1');
    $routes->post('delete-permanent/(:num)', 'UserManagement::deletePermanent/$1');
});
