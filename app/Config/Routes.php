<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

/**
 * ==========================
 * DEFAULT ROUTES
 * ==========================
 */
$routes->get('/', function () {
    return redirect()->to('dashboard');
});

$routes->get('dashboard', 'Dashboard::index', ['filter' => 'login']);

/**
 * ===================
 * ROUTES BOOKING JOB
 * ===================
 */
$routes->group('booking-job', [
    'namespace' => 'App\Controllers',
    'filter'    => 'role:admin,staff'
], function ($routes) {
    $routes->get('/', 'BookingJob::index');
    $routes->get('list', 'BookingJob::list');
    $routes->get('nextno', 'BookingJob::nextNo');
    $routes->post('store', 'BookingJob::store');
    $routes->get('edit/(:num)', 'BookingJob::edit/$1');
    $routes->post('update/(:num)', 'BookingJob::update/$1');
    $routes->post('delete/(:num)', 'BookingJob::delete/$1');
    $routes->get('export-excel/(:any)', 'BookingJob::exportExcel/$1');
    $routes->get('export-pdf/(:any)', 'BookingJob::exportPdf/$1');
    $routes->get('get-years', 'BookingJob::getYears');
    $routes->get('get-months/(:num)', 'BookingJob::getMonths/$1');
    $routes->get('export-pdf', 'BookingJob::exportPdf');
    $routes->get('print-note/(:segment)', 'BookingJob::printNote/$1');
    $routes->post('send-to-worksheet/(:num)', 'BookingJob::sendToWorksheet/$1');
});

/**
 * ========================
 * ROUTES BOOKING JOB TRASH
 * ========================
 */
$routes->group('booking-job-trash', [
    'namespace' => 'App\Controllers',
    'filter'    => 'role:admin'
], function ($routes) {
    $routes->get('/', 'BookingJobTrash::index');
    $routes->get('list', 'BookingJobTrash::list');
    $routes->post('restore/(:num)', 'BookingJobTrash::restore/$1');
    $routes->post('delete-permanent/(:num)', 'BookingJobTrash::deletePermanent/$1');
});

/**
 * ==================================
 * ROUTES WORKSHEET (IMPORT & EXPORT)
 * ==================================
 */
$routes->group('worksheet', [
    'namespace' => 'App\Controllers',
    'filter'    => 'role:admin,staff,accounting'
], function ($routes) {

    // ======= Halaman utama & list =======
    $routes->get('/', 'Worksheet::index');
    $routes->get('list', 'Worksheet::list'); // ?type=import/export
    $routes->get('create', 'Worksheet::create');
    $routes->post('store', 'Worksheet::store');

    // ======= Import Worksheet =======
    $routes->get('import/edit/(:num)', 'Worksheet::editImport/$1');
    $routes->post('import/update/(:num)', 'Worksheet::updateImport/$1');
    $routes->get('checkImport/(:num)', 'Worksheet::checkImport/$1');
    $routes->get('print-import/(:segment)', 'Worksheet::printImport/$1');
    $routes->post('import/delete/(:num)', 'Worksheet::deleteImport/$1');

    // ======= Export Worksheet =======
    $routes->get('export/edit/(:num)', 'Worksheet::editExport/$1');
    $routes->post('export/update/(:num)', 'Worksheet::updateExport/$1');
    $routes->get('checkExport/(:num)', 'Worksheet::checkExport/$1');
    $routes->get('print-export/(:segment)', 'Worksheet::printExport/$1');
    $routes->post('export/delete/(:num)', 'Worksheet::deleteExport/$1');


    // ======= Redirect Booking =======
    $routes->get('redirectToBooking', 'Worksheet::redirectToBooking');
});

// ==============================
// WORKSHEET IMPORT EXPORT (API)
// ==============================

// IMPORT
$routes->get('worksheet-import/get-years', 'Worksheet::getImportYears');
$routes->get('worksheet-import/get-months/(:num)', 'Worksheet::getImportMonths/$1');
$routes->get('worksheet-import/export-excel', 'Worksheet::exportImportExcel');
$routes->get('worksheet-import/export-pdf', 'Worksheet::exportImportPDF');

// EXPORT
$routes->get('worksheet-export/get-years', 'Worksheet::getExportYears');
$routes->get('worksheet-export/get-months/(:num)', 'Worksheet::getExportMonths/$1');
$routes->get('worksheet-export/export-excel', 'Worksheet::exportExportExcel');
$routes->get('worksheet-export/export-pdf', 'Worksheet::exportExportPDF');



/**
 * ==============================
 * ROUTES WORKSHEET IMPORT TRASH
 * ==============================
 */

$routes->group('worksheet-import-trash', [
    'namespace' => 'App\Controllers',
    'filter'    => 'role:admin'
], function ($routes) {

    // Halaman index trash
    $routes->get('/', 'WorksheetImportTrash::index');

    // Load data list trash (AJAX)
    $routes->get('list', 'WorksheetImportTrash::list');

    // Restore data
    // PARAM:
    // 1 = nama tabel (worksheet_import, worksheet_container_import, dst)
    // 2 = ID TRASH (id dari tabel trash)
    $routes->post('restore/(:segment)/(:num)', 'WorksheetImportTrash::restore/$1/$2');

    // Delete permanent
    $routes->post('delete-permanent/(:segment)/(:num)', 'WorksheetImportTrash::deletePermanent/$1/$2');
});

/**
 * ==============================
 * ROUTES WORKSHEET EXPORT TRASH
 * ==============================
 */

$routes->group('worksheet-export-trash', [
    'namespace' => 'App\Controllers',
    'filter'    => 'role:admin'
], function ($routes) {
    $routes->get('/', 'WorksheetExportTrash::index');
    $routes->get('list', 'WorksheetExportTrash::list');
    $routes->post('restore/(:segment)/(:num)', 'WorksheetExportTrash::restore/$1/$2');
    $routes->post('delete-permanent/(:segment)/(:num)', 'WorksheetExportTrash::deletePermanent/$1/$2');
});

/**
 * ==============================
 * ROUTES MASTERDATA
 * ==============================
 */

//Consignee
$routes->group('master-data/consignee', ['filter' => 'role:admin,staff,accounting'], function ($routes) {

    $routes->get('/',              'MasterConsignee::index');
    $routes->get('list',           'MasterConsignee::list');
    $routes->get('search/kode', 'MasterConsignee::searchKode');
    $routes->get('search/nama', 'MasterConsignee::searchNama');
    $routes->get('search/npwp',    'MasterConsignee::searchNpwp');
    $routes->post('store',         'MasterConsignee::store');
    $routes->get('edit/(:num)',    'MasterConsignee::edit/$1');
    $routes->post('update/(:num)', 'MasterConsignee::update/$1');
    $routes->post('delete/(:num)', 'MasterConsignee::delete/$1');
});

//Pelayaran
$routes->group('master-data/pelayaran', ['filter' => 'role:admin,staff,accounting'], function ($routes) {

    $routes->get('/',              'MasterPelayaran::index');
    $routes->get('list',           'MasterPelayaran::list');
    $routes->get('search/kode',    'MasterPelayaran::searchKode');
    $routes->get('search/nama',    'MasterPelayaran::searchNama');
    $routes->get('search/npwp',    'MasterPelayaran::searchNpwp');
    $routes->post('store',         'MasterPelayaran::store');
    $routes->get('edit/(:num)',    'MasterPelayaran::edit/$1');
    $routes->post('update/(:num)', 'MasterPelayaran::update/$1');
    $routes->post('delete/(:num)', 'MasterPelayaran::delete/$1');
});

//Port
$routes->group('master-data/port', ['filter' => 'role:admin,staff,accounting'], function ($routes) {

    $routes->get('/',              'MasterPort::index');
    $routes->get('list',           'MasterPort::list');
    $routes->get('search/kode',    'MasterPort::searchKode');
    $routes->get('search/nama',    'MasterPort::searchNama');
    $routes->get('search/negara',  'MasterPort::searchNegara');
    $routes->post('store',         'MasterPort::store');
    $routes->get('edit/(:num)',    'MasterPort::edit/$1');
    $routes->post('update/(:num)', 'MasterPort::update/$1');
    $routes->post('delete/(:num)', 'MasterPort::delete/$1');
});

//Notify Party
$routes->group('master-data/notify-party', ['filter' => 'role:admin,staff,accounting'], function ($routes) {

    $routes->get('/',              'MasterNotifyParty::index');
    $routes->get('list',           'MasterNotifyParty::list');
    $routes->get('search/kode',    'MasterNotifyParty::searchKode');
    $routes->get('search/nama',    'MasterNotifyParty::searchNama');
    $routes->get('search/npwp',    'MasterNotifyParty::searchNpwp');
    $routes->post('store',         'MasterNotifyParty::store');
    $routes->get('edit/(:num)',    'MasterNotifyParty::edit/$1');
    $routes->post('update/(:num)', 'MasterNotifyParty::update/$1');
    $routes->post('delete/(:num)', 'MasterNotifyParty::delete/$1');
});





/**
 * ==========================
 * ROUTES MANAGEMENT USER
 * ==========================
 */
/**
 * ==========================
 * ROUTES USER MANAGEMENT
 * ==========================
 */
$routes->group('user-management', [
    'namespace' => 'App\Controllers',
    'filter'    => 'role:admin'
], function ($routes) {
    // Halaman utama user management
    $routes->get('/', 'UserManagement::index');

    // Daftar user (JSON)
    $routes->get('list', 'UserManagement::list');

    // Tambah user baru
    $routes->post('store', 'UserManagement::store');

    // Edit dan update user
    $routes->get('edit/(:num)', 'UserManagement::edit/$1');
    $routes->post('update/(:num)', 'UserManagement::update/$1');

    // Hapus user (ke trash)
    $routes->post('delete/(:num)', 'UserManagement::delete/$1');

    // Restore user dari trash
    $routes->post('restore/(:num)', 'UserManagement::restore/$1');
});


// ==========================================================================================================================================
// ==========================================================================================================================================
// ==========================================================================================================================================

/**
 * ==========================
 * ROUTES UNTUK ADMIN
 * ==========================
 */
$routes->group('admin', [
    'namespace' => 'App\Controllers\Admin',
    'filter'    => 'role:admin'
], function ($routes) {
    $routes->get('dashboard', 'Dashboard::index');
    $routes->resource('debitnote', ['controller' => 'DebitNote']);
    $routes->resource('earsip', ['controller' => 'Earsip']);
});

/**
 * ==========================
 * ROUTES UNTUK STAFF
 * ==========================
 */
$routes->group('staff', [
    'namespace' => 'App\Controllers\Staff',
    'filter'    => 'role:staff'
], function ($routes) {
    $routes->get('dashboard', 'Dashboard::index');
    $routes->get('worksheet', 'Worksheet::index');
    $routes->get('earsip', 'Earsip::index');
});

/**
 * ==========================
 * ROUTES UNTUK ACCOUNTING
 * ==========================
 */
$routes->group('accounting', [
    'namespace' => 'App\Controllers\Accounting',
    'filter'    => 'role:accounting'
], function ($routes) {
    $routes->get('dashboard', 'Dashboard::index');
    $routes->get('worksheet', 'Worksheet::index');
    $routes->get('debitnote', 'DebitNote::index');
    $routes->get('earsip', 'Earsip::index');
});

/**
 * ==========================
 * ROUTES AUTH (Myth/Auth)
 * ==========================
 */
if (file_exists(ROOTPATH . 'vendor/myth/auth/src/Config/Routes.php')) {
    require ROOTPATH . 'vendor/myth/auth/src/Config/Routes.php';
}
