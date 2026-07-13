<?php
/**
 * Template induk untuk semua halaman admin. Setiap halaman admin (misal
 * admin/dashboard.php) memanggil `$this->extend('admin/layout/template')`
 * lalu mengisi blok `content` — template ini yang menyusun potongan
 * header + sidebar + isi halaman + footer jadi satu halaman utuh, supaya
 * struktur (logo, menu, dsb) tidak perlu ditulis ulang di setiap halaman.
 */
?>
<?= $this->include('admin/layout/header') ?>
<?= $this->include('admin/layout/sidebar') ?>
<?= $this->renderSection('content') ?>
<?= $this->include('admin/layout/footer') ?>
