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
    $routes->get('print-note/(:num)', 'BookingJob::printNote/$1');
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
    $routes->post('delete/(:num)', 'Worksheet::delete/$1');

    // ======= Import Worksheet =======
    $routes->get('import/edit/(:num)', 'Worksheet::editImport/$1');
    $routes->post('import/update/(:num)', 'Worksheet::updateImport/$1');
    $routes->get('checkImport/(:num)', 'Worksheet::checkImport/$1');

    // ======= Export Worksheet =======
    $routes->get('export/edit/(:num)', 'Worksheet::editExport/$1');
    $routes->post('export/update/(:num)', 'Worksheet::updateExport/$1');
    $routes->get('checkExport/(:num)', 'Worksheet::checkExport/$1');

    // ======= Redirect Booking =======
    $routes->get('redirectToBooking', 'Worksheet::redirectToBooking');
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
