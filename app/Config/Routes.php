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

// Placeholders
$routes->get('psu', 'Placeholder::psu');
$routes->get('perumahan-formal', 'Placeholder::formal');
$routes->get('bansos-rtlh', 'Placeholder::bansos');
$routes->get('pisew', 'Placeholder::pisew');
$routes->get('arsinum', 'Placeholder::arsinum');
$routes->get('aset-tanah', 'Placeholder::aset_tanah');

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
    $routes->post('delete/(:num)', 'RefMaster::delete/$1');
});

$routes->group('rtlh', function($routes) {
    $routes->get('/', 'Rtlh::index');
    $routes->get('create', 'Rtlh::create');
    $routes->post('store', 'Rtlh::store');
    $routes->get('detail/(:num)', 'Rtlh::detail/$1');
    $routes->post('log-export/(:num)', 'Rtlh::logExport/$1');
    $routes->get('edit/(:num)', 'Rtlh::edit/$1');
    $routes->post('update/(:num)', 'Rtlh::update/$1');
    $routes->post('delete/(:num)', 'Rtlh::delete/$1');
});

$routes->group('wilayah-kumuh', function($routes) {
    $routes->get('/', 'WilayahKumuh::index');
    $routes->get('peta', 'WilayahKumuh::peta');
    $routes->get('create', 'WilayahKumuh::create');
    $routes->post('store', 'WilayahKumuh::store');
    $routes->get('detail/(:num)', 'WilayahKumuh::detail/$1');
    $routes->get('edit/(:num)', 'WilayahKumuh::edit/$1');
    $routes->post('update/(:num)', 'WilayahKumuh::update/$1');
    $routes->post('delete/(:num)', 'WilayahKumuh::delete/$1');
});
