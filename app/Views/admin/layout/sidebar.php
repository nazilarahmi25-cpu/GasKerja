<?php
/**
 * Menu sidebar admin. Setiap halaman admin mengirim variabel
 * $activeMenu (contoh: 'dashboard', 'lowongan') lewat controller supaya
 * item menu yang sesuai dapat class 'active' (background biru muda +
 * garis aksen kiri) secara otomatis lewat perbandingan $active===$key.
 *
 * PENTING: key 'mitra' pakai label tampilan "Mitra Kerja", tapi URL-nya
 * tetap 'admin/perusahaan' — SENGAJA tidak diubah menjadi "mitra" di
 * backend (nama tabel, controller, route semua tetap "perusahaan").
 * Cuma teks yang tampil ke admin yang pakai istilah "Mitra Kerja".
 */
$menu = [
    'dashboard' => ['url' => 'dashboard-admin', 'label' => 'Dashboard', 'icon' => 'bi-house-door'],
    'pelamar'   => ['url' => 'admin/pelamar', 'label' => 'Pelamar', 'icon' => 'bi-people'],
    'mitra'     => ['url' => 'admin/perusahaan', 'label' => 'Mitra Kerja', 'icon' => 'bi-building'],
    'lowongan'  => ['url' => 'admin/lowongan', 'label' => 'Lowongan', 'icon' => 'bi-briefcase'],
];
$active = $activeMenu ?? '';
?>
<aside class="admin-sidebar">
    <nav class="nav flex-column">
        <?php foreach ($menu as $key => $item): ?>
            <a href="<?= base_url($item['url']) ?>"
               class="admin-menu-item <?= $active === $key ? 'active' : '' ?>">
                <i class="bi <?= $item['icon'] ?>"></i>
                <span><?= $item['label'] ?></span>
            </a>
        <?php endforeach; ?>

        <a href="<?= base_url('logout') ?>" class="admin-menu-item admin-menu-logout">
            <i class="bi bi-box-arrow-right"></i>
            <span>Logout</span>
        </a>
    </nav>
</aside>

<!-- Area konten utama tiap halaman admin dirender di sini lewat renderSection('content') -->
<main class="admin-content flex-grow-1">
