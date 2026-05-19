<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>

    <!-- Hubungkan CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
</head>
<body>

<header class="navbar">
    <div class="container nav-content">
        <a href="<?= base_url('/') ?>" class="logo-wrap">
            <img src="<?= base_url('assets/image/logo.png') ?>" alt="GasKerja Logo" class="logo">
            <span class="logo-text"></span>
        </a>

        <nav class="menu">
            <a href="<?= base_url('/') ?>">Home</a>
            <a href="<?= base_url('/about-us') ?>">About Us</a>
            <a href="<?= base_url('/login') ?>">Login</a>
            <a href="<?= base_url('/register') ?>" class="btn-primary">Daftar</a>
        </nav>
    </div>
</header>