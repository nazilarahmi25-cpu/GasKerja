<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

/*
|--------------------------------------------------------------------------
| PUBLIC
|--------------------------------------------------------------------------
*/

$routes->get('/', 'Home::index');
$routes->get('about-us', 'Home::about_us');

// 'detail-lowongan' adalah route LAMA (dead code, lihat Home::detail_lowongan()).
// 'lowongan/(:num)' adalah route AKTIF untuk halaman detail lowongan publik
// — (:num) menangkap ID lowongan dari URL, contoh /lowongan/5 → $lowonganId=5.
$routes->get('detail-lowongan', 'Home::detail_lowongan');
$routes->get('lowongan/(:num)', 'Home::detailLowongan/$1');
$routes->get('apply-lowongan', 'Home::apply_lowongan');

$routes->get('profil', 'Home::profil');
$routes->get('notifikasi', 'Home::notifikasi');


/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/

$routes->get('login', 'Home::login');
$routes->post('login', 'Home::processLogin');

$routes->get('logout', 'Home::logout');

$routes->get('register', 'Home::register');
$routes->post('register', 'Home::processRegister');

$routes->get('register-perusahaan', 'Home::register_perusahaan');
$routes->post('register-perusahaan', 'Home::processRegisterPerusahaan');


/*
|--------------------------------------------------------------------------
| DASHBOARD
|--------------------------------------------------------------------------
*/

$routes->get('dashboard-pencari', 'Home::dashboard_pencari', ['filter' => 'auth:pencari_kerja']);
$routes->get('dashboard-perusahaan', 'Home::dashboard_perusahaan', ['filter' => 'auth:perusahaan']);
$routes->get('dashboard-admin', 'AdminController::dashboard', ['filter' => 'auth:admin']);


/*
|--------------------------------------------------------------------------
| PROFIL
|--------------------------------------------------------------------------
*/

$routes->post('profil/update', 'Home::updateProfil');


/*
|--------------------------------------------------------------------------
| LAMARAN
|--------------------------------------------------------------------------
*/

$routes->post('apply-lowongan', 'Home::processApply');


/*
|--------------------------------------------------------------------------
| ADMIN
|--------------------------------------------------------------------------
*/

$routes->get('admin/pengguna', 'AdminController::pengguna');
$routes->get('admin/pengguna/hapus/(:num)', 'AdminController::hapusPengguna/$1');

$routes->get('admin/perusahaan', 'AdminController::perusahaan');
$routes->get('admin/perusahaan/hapus/(:num)', 'AdminController::hapusPerusahaan/$1');

$routes->get('admin/lowongan', 'AdminController::lowongan');
$routes->get('admin/lowongan/tambah', 'AdminController::tambahLowongan');
$routes->post('admin/lowongan/simpan', 'AdminController::simpanLowongan');

$routes->get('admin/lowongan/edit/(:num)', 'AdminController::editLowongan/$1');
$routes->post('admin/lowongan/update/(:num)', 'AdminController::updateLowongan/$1');

$routes->get('admin/lowongan/hapus/(:num)', 'AdminController::hapusLowongan/$1');

$routes->get('admin/lamaran', 'AdminController::lamaran');
$routes->get('admin/lamaran/status/(:num)/(:alpha)', 'AdminController::updateStatusLamaran/$1/$2');
$routes->get('admin/lamaran/hapus/(:num)', 'AdminController::hapusLamaran/$1');


/*
|--------------------------------------------------------------------------
| PERUSAHAAN
|--------------------------------------------------------------------------
*/

$routes->get('perusahaan/lowongan', 'PerusahaanController::lowongan');

$routes->get('perusahaan/tambah-lowongan', 'PerusahaanController::tambah');
$routes->post('perusahaan/simpan-lowongan', 'PerusahaanController::simpan');

$routes->get('perusahaan/edit-lowongan/(:num)', 'PerusahaanController::edit/$1');
$routes->post('perusahaan/update-lowongan/(:num)', 'PerusahaanController::update/$1');

$routes->get('perusahaan/hapus-lowongan/(:num)', 'PerusahaanController::hapus/$1');

$routes->get('perusahaan/pelamar/(:num)', 'PerusahaanController::pelamar/$1');


/*
|--------------------------------------------------------------------------
| API CRUD LOWONGAN (AJAX) — DEAD CODE, jangan dijadikan acuan
|--------------------------------------------------------------------------
| LowonganController (beda dari LowonganModel) adalah percobaan awal CRUD
| lowongan lewat AJAX yang tidak pernah selesai diintegrasikan ke frontend.
| Nama field yang dipakai di controller-nya (tipe_pekerjaan, gaji_min,
| gaji_max, deadline) bahkan TIDAK COCOK dengan kolom asli tabel `lowongan`
| (tipe_kerja, gaji tunggal, tidak ada kolom deadline) — jadi store()/
| update() di controller ini tidak akan pernah berhasil menyimpan data
| dengan benar. Alur CRUD lowongan yang benar-benar dipakai ada di
| PerusahaanController (milik sendiri) dan AdminController (semua).
|--------------------------------------------------------------------------
*/

$routes->get('lowongan', 'LowonganController::index');
$routes->post('lowongan/store', 'LowonganController::store');
$routes->get('lowongan/edit/(:num)', 'LowonganController::edit/$1');
$routes->post('lowongan/update/(:num)', 'LowonganController::update/$1');
$routes->delete('lowongan/delete/(:num)', 'LowonganController::delete/$1');
