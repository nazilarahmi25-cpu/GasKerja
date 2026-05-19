<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'Home::index');

$routes->get('/about-us', 'Home::about_us');

$routes->get('/login', 'Home::login');

$routes->get('/register', 'Home::register');

$routes->get('/dashboard-pencari', 'Home::dashboard_pencari');

$routes->get('/dashboard-perusahaan', 'Home::dashboard_perusahaan');

$routes->get('/dashboard-admin', 'Home::dashboard_admin');

$routes->get('/detail-lowongan', 'Home::detail_lowongan');

$routes->get('/apply-lowongan', 'Home::apply_lowongan');

$routes->get('/profil', 'Home::profil');

$routes->get('/notifikasi', 'Home::notifikasi');