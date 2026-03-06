<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// Auth & Environment Switching
$routes->match(['get', 'post'], 'login', 'Auth::login');
$routes->get('logout', 'Auth::logout');
$routes->get('switch-env/(:segment)', 'Auth::switchEnv/$1');
$routes->get('unauthorized', 'Home::unauthorized');

// Workspace Shell (AJAX Driven)
$routes->get('/', 'Workspace::index');
$routes->get('workspace', 'Workspace::index');
$routes->get('workspace/get_sidebar/(:segment)', 'Workspace::get_sidebar/$1');
$routes->get('workspace/render_menu/(:any)', 'Workspace::render_menu/$1');

// Dashboard fallback
$routes->get('dashboard', 'Workspace::render_menu/home/dashboard');

// Pendaftaran (Registration) Flow
$routes->group('pendaftaran', ['filter' => 'permission:registration.visit.view'], function($routes) {
    $routes->get('/', 'Pendaftaran::index');
    $routes->get('create', 'Pendaftaran::create', ['filter' => 'permission:registration.visit.create']);
    $routes->get('rajal', 'Pendaftaran::create', ['filter' => 'permission:registration.visit.create']);
    $routes->get('ranap', 'Pendaftaran::create', ['filter' => 'permission:registration.visit.create']);
    $routes->get('igd', 'Pendaftaran::create', ['filter' => 'permission:registration.visit.create']);
    $routes->get('cek_peserta_bpjs', 'Pendaftaran::cek_peserta_bpjs', ['filter' => 'permission:registration.visit.create']);
    $routes->get('cari_pasien', 'Pendaftaran::cari_pasien', ['filter' => 'permission:registration.visit.create']);
    $routes->get('cari_rujukan', 'Pendaftaran::cari_rujukan', ['filter' => 'permission:registration.visit.create']);
    $routes->get('cari_diagnosa', 'Pendaftaran::cari_diagnosa', ['filter' => 'permission:registration.visit.create']);
    $routes->post('store', 'Pendaftaran::store', ['filter' => 'permission:registration.visit.create']);
});

// Rawat Jalan (Poli) Flow
$routes->group('rajal', ['filter' => 'permission:rajal.view'], function($routes) {
    $routes->get('pemeriksaan', 'RawatJalan::worklist');
    $routes->get('periksa/(:num)', 'RawatJalan::periksa/$1');
    $routes->post('get_worklist', 'RawatJalan::get_worklist');
    $routes->post('save_vital/(:num)', 'RawatJalan::save_vital/$1');
    $routes->post('save_soap/(:num)', 'RawatJalan::save_soap/$1');
    $routes->post('selesai/(:num)', 'RawatJalan::selesai/$1');
    $routes->get('get_icd', 'RawatJalan::get_icd');
});

// Monitoring
$routes->get('monitoring/queue', 'Monitoring::queue', ['filter' => 'permission:clinical.rajal.view']);

// Master Data Management
$routes->group('master', function($routes) {
    // Pasien
    $routes->get('pasien', 'Master\Pasien::index', ['filter' => 'permission:master.pasien.view']);
    $routes->get('pasien/create', 'Master\Pasien::create', ['filter' => 'permission:master.pasien.create']);
    $routes->post('pasien/store', 'Master\Pasien::store', ['filter' => 'permission:master.pasien.create']);
    $routes->get('pasien/edit/(:num)', 'Master\Pasien::edit/$1', ['filter' => 'permission:master.pasien.edit']);
    $routes->post('pasien/update/(:num)', 'Master\Pasien::update/$1', ['filter' => 'permission:master.pasien.edit']);
    $routes->post('pasien/delete/(:num)', 'Master\Pasien::delete/$1', ['filter' => 'permission:master.pasien.delete']);
    
    // Poliklinik
    $routes->get('poliklinik', 'Master\Poliklinik::index', ['filter' => 'permission:master.poli.view']);
    $routes->get('poliklinik/create', 'Master\Poliklinik::create', ['filter' => 'permission:master.poli.create']);
    $routes->post('poliklinik/store', 'Master\Poliklinik::store', ['filter' => 'permission:master.poli.create']);
    
    // Dokter
    $routes->get('dokter', 'Master\Dokter::index', ['filter' => 'permission:master.dokter.view']);
    $routes->get('dokter/create', 'Master\Dokter::create', ['filter' => 'permission:master.dokter.create']);
    $routes->post('dokter/store', 'Master\Dokter::store', ['filter' => 'permission:master.dokter.create']);
});

// Administrator Tools
$routes->group('admin', function($routes) {
    // BPJS V-Claim Tools
    $routes->get('bpjs', 'Admin\BpjsTools::index', ['filter' => 'permission:admin.bpjs']);
    $routes->post('bpjs/signature', 'Admin\BpjsTools::generate_signature', ['filter' => 'permission:admin.bpjs']);
    $routes->post('bpjs/cek', 'Admin\BpjsTools::check_peserta', ['filter' => 'permission:admin.bpjs']);

    // BPJS MJKN Tools
    $routes->get('bpjsjkn', 'Admin\BpjsJkn::index', ['filter' => 'permission:admin.bpjsjkn']);
    $routes->post('bpjsjkn/get_dashboard_tanggal', 'Admin\BpjsJkn::get_dashboard_tanggal', ['filter' => 'permission:admin.bpjsjkn']);
    $routes->post('bpjsjkn/get_dashboard_bulan', 'Admin\BpjsJkn::get_dashboard_bulan', ['filter' => 'permission:admin.bpjsjkn']);
    $routes->post('bpjsjkn/get_antrean_tanggal', 'Admin\BpjsJkn::get_antrean_tanggal', ['filter' => 'permission:admin.bpjsjkn']);
    $routes->post('bpjsjkn/get_antrean_booking', 'Admin\BpjsJkn::get_antrean_booking', ['filter' => 'permission:admin.bpjsjkn']);
    $routes->post('bpjsjkn/get_task_logs', 'Admin\BpjsJkn::get_task_logs', ['filter' => 'permission:admin.bpjsjkn']);
    $routes->post('bpjsjkn/cari_pasien_local', 'Admin\BpjsJkn::cari_pasien_local', ['filter' => 'permission:admin.bpjsjkn']);
    $routes->post('bpjsjkn/do_checkin', 'Admin\BpjsJkn::do_checkin', ['filter' => 'permission:admin.bpjsjkn']);
});
