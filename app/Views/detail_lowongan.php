<?php
/**
 * Halaman detail satu lowongan (publik). Data $lowongan disiapkan oleh
 * Home::detailLowongan() lewat LowonganModel::findDenganPerusahaan() —
 * kalau lowongan tidak ditemukan, sudah dihapus, atau statusnya bukan
 * 'aktif', controller sudah menghentikan request dengan 404 SEBELUM
 * sampai ke view ini, jadi $lowongan di sini selalu berisi data valid
 * & aktif (tidak perlu dicek ulang null di sini).
 */
?>
<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>

<section class="section">
    <div class="container detail-card">
        <span class="job-type"><?= esc($lowongan['tipe_kerja']) ?></span>
        <h1><?= esc($lowongan['judul']) ?></h1>
        <p><strong>Perusahaan:</strong> <?= esc($lowongan['mitra_kerja']) ?></p>
        <p><strong>Lokasi:</strong> <?= esc($lowongan['lokasi']) ?></p>
        <?php if (!empty($lowongan['gaji'])): ?>
            <p><strong>Gaji:</strong> <?= esc($lowongan['gaji']) ?></p>
        <?php endif; ?>

        <h3>Deskripsi Pekerjaan</h3>
        <p><?= nl2br(esc($lowongan['deskripsi'])) ?></p>

        <h3>Kualifikasi</h3>
        <p><?= nl2br(esc($lowongan['kualifikasi'])) ?></p>

        <a href="<?= base_url('apply-lowongan?lowongan_id=' . $lowongan['id']) ?>" class="apply-btn">Lamar Sekarang</a>
    </div>
</section>

<?= $this->endSection() ?>
