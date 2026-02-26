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
    $routes->get('edit/(:num)', 'Rtlh::edit/$1');
    $routes->post('update/(:num)', 'Rtlh::update/$1');
    $routes->post('delete/(:num)', 'Rtlh::delete/$1');
});

$routes->group('wilayah-kumuh', function($routes) {
    $routes->get('/', 'WilayahKumuh::index');
    $routes->get('create', 'WilayahKumuh::create');
    $routes->post('store', 'WilayahKumuh::store');
    $routes->get('detail/(:num)', 'WilayahKumuh::detail/$1');
    $routes->get('edit/(:num)', 'WilayahKumuh::edit/$1');
    $routes->post('update/(:num)', 'WilayahKumuh::update/$1');
    $routes->post('delete/(:num)', 'WilayahKumuh::delete/$1');
});
