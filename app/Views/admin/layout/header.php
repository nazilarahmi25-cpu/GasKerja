<?php
/**
 * Bagian atas layout admin: doctype, <head> (Bootstrap 5 + Bootstrap
 * Icons lewat CDN, plus admin.css), dan topbar (logo + notifikasi +
 * profil). Di-include oleh admin/layout/template.php, bersama sidebar.php
 * dan footer.php, supaya struktur ini tidak perlu diulang di tiap
 * halaman admin.
 */
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Admin - GasKerja' ?></title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="<?= base_url('assets/css/admin.css') ?>">
</head>
<body>

<header class="admin-topbar d-flex align-items-center justify-content-between px-4">
    <!-- Logo: markup sama persis dengan app/Views/layout/header.php (beranda) -->
    <a href="<?= base_url('dashboard-admin') ?>" class="logo-wrap">
        <img src="<?= base_url('assets/image/logo.png') ?>" alt="Logo GasKerja" class="logo">
        <span class="logo-text"></span>
    </a>

    <div class="d-flex align-items-center gap-4">
        <i class="bi bi-bell fs-5 admin-icon-muted"></i>
        <div class="d-flex align-items-center gap-2">
            <div class="admin-avatar"><i class="bi bi-person-fill"></i></div>
            <span class="fw-medium"><?= esc(session()->get('nama') ?? 'Admin') ?></span>
        </div>
    </div>
</header>

<div class="admin-body d-flex">
