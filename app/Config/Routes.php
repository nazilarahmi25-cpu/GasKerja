<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'Home::index');

$routes->get('/about-us', 'Home::about_us');

$routes->get('/login', 'Home::login');
$routes->post('/login', 'Home::processLogin');

$routes->get('/register', 'Home::register');
$routes->post('/register', 'Home::processRegister');

$routes->get('/register-perusahaan', 'Home::register_perusahaan');
$routes->post('/register-perusahaan', 'Home::processRegisterPerusahaan');

$routes->get('/dashboard-pencari', 'Home::dashboard_pencari');

$routes->get('/dashboard-perusahaan', 'Home::dashboard_perusahaan');

$routes->get('/dashboard-admin', 'Home::dashboard_admin');

$routes->get('/detail-lowongan', 'Home::detail_lowongan');

$routes->get('/apply-lowongan', 'Home::apply_lowongan');
$routes->post('/apply-lowongan', 'Home::processApply');

$routes->get('/profil', 'Home::profil');
$routes->post('/profil', 'Home::updateProfil');

$routes->get('/notifikasi', 'Home::notifikasi');

// ✅ TAMBAHKAN INI — CRUD Lowongan
$routes->get('/lowongan', 'LowonganController::index');
$routes->get('/lowongan/create', 'LowonganController::create');
$routes->post('/lowongan/store', 'LowonganController::store');
$routes->get('/lowongan/edit/(:num)', 'LowonganController::edit/$1');
$routes->post('/lowongan/update/(:num)', 'LowonganController::update/$1');
$routes->get('/lowongan/delete/(:num)', 'LowonganController::delete/$1');