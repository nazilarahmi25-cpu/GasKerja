<?php
/**
 * Halaman 2 dashboard admin — "Kelola Pelamar". Menampilkan tabel LENGKAP
 * (bukan preview seperti di dashboard) semua lamaran, disiapkan oleh
 * AdminController::lamaran() lewat LamaranModel::getAllDenganDetail()
 * (JOIN lamaran → pencari_kerja → users, dan lamaran → lowongan →
 * perusahaan — lihat komentar di Model untuk penjelasan lengkap).
 */
?>
<?= $this->extend('admin/layout/template') ?>
<?= $this->section('content') ?>

<h1 class="fw-bold mb-4">Kelola Pelamar</h1>

<!-- Tabel Data Pelamar: semua baris (bukan preview 5 seperti di dashboard) -->
<div class="card admin-card mb-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-admin align-middle mb-0">
                <thead>
                    <tr>
                        <th>NO</th><th>Nama</th><th>Posisi</th><th>Mitra kerja</th><th>Tanggal</th><th>Status</th><th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($lamaran)): ?>
                        <tr><td colspan="7" class="text-center text-muted py-3">Belum ada data</td></tr>
                    <?php else: ?>
                        <?php foreach ($lamaran as $i => $pelamar): ?>
                        <tr>
                            <td><?= $i + 1 ?></td>
                            <td><?= esc($pelamar['nama_pelamar']) ?></td>
                            <td><?= esc($pelamar['posisi']) ?></td>
                            <td><?= esc($pelamar['mitra_kerja']) ?></td>
                            <td><?= esc($pelamar['tanggal_lamar']) ?></td>
                            <td><span class="badge text-bg-light border"><?= esc($pelamar['status']) ?></span></td>
                            <td>
                                <!-- Ikon mata: placeholder, belum ada halaman detail pelamar
                                     sungguhan (belum diminta dibangun penuh) -->
                                <a href="<?= base_url('admin/pelamar') ?>" class="text-decoration-none"><i class="bi bi-eye"></i></a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
