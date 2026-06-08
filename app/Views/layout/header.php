<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GasKerja - Cari Lowongan Kerja</title>

    <!-- CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">

    <!-- Google Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>

<body>

<header class="navbar">
    <div class="container nav-content">

        <!-- Logo -->
        <a href="<?= base_url('/') ?>" class="logo-wrap">
            <img src="<?= base_url('assets/image/logo.png') ?>" alt="Logo GasKerja" class="logo">
            <span class="logo-text"></span>
        </a>

        <!-- Menu -->
        <nav class="menu">
            <a href="<?= base_url('/') ?>">Beranda</a>
            <a href="<?= base_url('about-us') ?>">Tentang Kami</a>

            <a href="<?= base_url('login') ?>" class="login-btn">Masuk</a>
            <a href="<?= base_url('register') ?>" class="daftar-btn">Daftar</a>
        </nav>

    </div>
</header>