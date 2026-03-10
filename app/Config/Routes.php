<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Auth::index');
$routes->get('login', 'Auth::index');
$routes->post('login/process', 'Auth::login');
$routes->get('logout', 'Auth::logout');
$routes->get('dashboard', 'Home::index');
$routes->get('settings', 'Settings::index');

// MODUL DATA UTAMA
$routes->group('psu', function($routes) {
    $routes->get('/', 'Psu::index');
    $routes->get('export-excel', 'Psu::exportExcel');
    $routes->post('import-csv', 'Psu::importCsv');
    $routes->get('create', 'Psu::create');
    $routes->post('store', 'Psu::store');
    $routes->get('detail/(:num)', 'Psu::detail/$1');
    $routes->get('edit/(:num)', 'Psu::edit/$1');
    $routes->post('update/(:num)', 'Psu::update/$1');
    $routes->post('delete/(:num)', 'Psu::delete/$1');
});

$routes->group('perumahan-formal', function($routes) {
    $routes->get('/', 'PerumahanFormal::index');
    $routes->get('export-excel', 'PerumahanFormal::exportExcel');
    $routes->post('import-csv', 'PerumahanFormal::importCsv');
    $routes->get('create', 'PerumahanFormal::create');
    $routes->get('detail/(:num)', 'PerumahanFormal::detail/$1');
    $routes->post('delete/(:num)', 'PerumahanFormal::delete/$1');
});

$routes->group('pisew', function($routes) {
    $routes->get('/', 'Pisew::index');
    $routes->get('export-excel', 'Pisew::exportExcel');
    $routes->post('import-csv', 'Pisew::importCsv');
    $routes->get('create', 'Pisew::create');
    $routes->post('store', 'Pisew::store');
    $routes->get('detail/(:num)', 'Pisew::detail/$1');
    $routes->get('edit/(:num)', 'Pisew::edit/$1');
    $routes->post('update/(:num)', 'Pisew::update/$1');
    $routes->post('delete/(:num)', 'Pisew::delete/$1');
});

$routes->group('arsinum', function($routes) {
    $routes->get('/', 'Arsinum::index');
    $routes->get('export-excel', 'Arsinum::exportExcel');
    $routes->post('import-csv', 'Arsinum::importCsv');
    $routes->get('create', 'Arsinum::create');
    $routes->post('store', 'Arsinum::store');
    $routes->get('detail/(:num)', 'Arsinum::detail/$1');
    $routes->get('edit/(:num)', 'Arsinum::edit/$1');
    $routes->post('update/(:num)', 'Arsinum::update/$1');
    $routes->post('delete/(:num)', 'Arsinum::delete/$1');
});

$routes->group('aset-tanah', function($routes) {
    $routes->get('/', 'AsetTanah::index');
    $routes->get('export-excel', 'AsetTanah::exportExcel');
    $routes->post('import-csv', 'AsetTanah::importCsv');
    $routes->get('create', 'AsetTanah::create');
    $routes->post('store', 'AsetTanah::store');
    $routes->get('detail/(:num)', 'AsetTanah::detail/$1');
    $routes->get('edit/(:num)', 'AsetTanah::edit/$1');
    $routes->post('update/(:num)', 'AsetTanah::update/$1');
    $routes->post('delete/(:num)', 'AsetTanah::delete/$1');
});

$routes->group('rtlh', function($routes) {
    $routes->get('/', 'Rtlh::index');
    $routes->get('rekap-desa', 'Rtlh::rekapDesa');
    $routes->get('export-excel', 'Rtlh::exportExcel');
    $routes->post('import-csv', 'Rtlh::importCsv');
    $routes->get('create', 'Rtlh::create');
    $routes->post('store', 'Rtlh::store');
    $routes->get('detail/(:num)', 'Rtlh::detail/$1');
    $routes->post('mark-tuntas/(:num)', 'Rtlh::markTuntas/$1');
    $routes->get('edit/(:num)', 'Rtlh::edit/$1');
    $routes->post('update/(:num)', 'Rtlh::update/$1');
    $routes->post('delete/(:num)', 'Rtlh::delete/$1');
});

$routes->group('wilayah-kumuh', function($routes) {
    $routes->get('/', 'WilayahKumuh::index');
    $routes->get('export-excel', 'WilayahKumuh::exportExcel');
    $routes->post('import-csv', 'WilayahKumuh::importCsv');
    $routes->get('peta', 'WilayahKumuh::peta');
    $routes->get('create', 'WilayahKumuh::create');
    $routes->post('store', 'WilayahKumuh::store');
    $routes->get('detail/(:num)', 'WilayahKumuh::detail/$1');
    $routes->get('edit/(:num)', 'WilayahKumuh::edit/$1');
    $routes->post('update/(:num)', 'WilayahKumuh::update/$1');
    $routes->post('delete/(:num)', 'WilayahKumuh::delete/$1');
});

$routes->get('bansos-rtlh', 'Placeholder::bansos');

// SISTEM & PENGATURAN
$routes->group('roles', function($routes) {
    $routes->get('/', 'Roles::index');
    $routes->get('create', 'Roles::create');
    $routes->post('store', 'Roles::store');
    $routes->get('edit/(:num)', 'Roles::edit/$1');
    $routes->post('update/(:num)', 'Roles::update/$1');
    $routes->get('delete/(:num)', 'Roles::delete/$1');
});

$routes->group('users', function($routes) {
    $routes->get('/', 'Users::index');
    $routes->get('create', 'Users::create');
    $routes->post('store', 'Users::store');
    $routes->get('edit/(:num)', 'Users::edit/$1');
    $routes->post('update/(:num)', 'Users::update/$1');
    $routes->post('delete/(:num)', 'Users::delete/$1');
});

$routes->get('logs', 'Logs::index');
$routes->get('logs/clear', 'Logs::clear');

$routes->group('trash', function($routes) {
    $routes->get('/', 'Trash::index');
    $routes->get('restore/(:num)', 'Trash::restore/$1');
    $routes->get('delete-perm/(:num)', 'Trash::deletePermanently/$1');
});

$routes->group('ref-master', function($routes) {
    $routes->get('/', 'RefMaster::index');
    $routes->get('create', 'RefMaster::create');
    $routes->post('store', 'RefMaster::store');
    $routes->get('edit/(:num)', 'RefMaster::edit/$1');
    $routes->post('update/(:num)', 'RefMaster::update/$1');
    $routes->get('delete/(:num)', 'RefMaster::delete/$1');
});
