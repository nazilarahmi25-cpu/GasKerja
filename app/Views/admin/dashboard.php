<?php
/**
 * Dashboard utama admin. Data disiapkan oleh AdminController::dashboard():
 * - $totalUsers, $totalPerusahaan, $totalPelamar: angka statistik
 * - $lamaranPreview, $perusahaanPreview, $lowonganPreview: masing-masing
 *   5 baris teratas untuk preview (data lengkapnya ada di halaman
 *   "lihat semua" masing-masing: admin/pelamar, admin/perusahaan,
 *   admin/lowongan).
 */
?>
<?= $this->extend('admin/layout/template') ?>
<?= $this->section('content') ?>

<h1 class="fw-bold mb-4">HAI ADMIN</h1>

<!-- Kartu Statistik: Total pengguna, jumlah mitra kerja, jumlah pelamar -->
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card admin-card p-4 text-center">
            <div class="admin-stat-number"><?= $totalUsers ?></div>
            <div class="text-muted">Total</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card admin-card p-4 text-center">
            <div class="admin-stat-number"><?= $totalPerusahaan ?></div>
            <div class="text-muted">Perusahaan</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card admin-card p-4 text-center">
            <div class="admin-stat-number"><?= $totalPelamar ?></div>
            <div class="text-muted">Pelamar</div>
        </div>
    </div>
</div>

<!-- Data Pelamar: preview 5 lamaran terbaru (LamaranModel::getAllDenganDetail) -->
<div class="card admin-card mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">Data Pelamar</h5>
            <a href="<?= base_url('admin/pelamar') ?>" class="btn btn-admin-primary btn-sm">LIHAT SEMUA</a>
        </div>
        <div class="table-responsive">
            <table class="table table-admin align-middle mb-0">
                <thead>
                    <tr>
                        <th>NO</th><th>Nama</th><th>Posisi</th><th>Mitra kerja</th><th>Tanggal</th><th>Status</th><th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($lamaranPreview)): ?>
                        <tr><td colspan="7" class="text-center text-muted py-3">Belum ada data</td></tr>
                    <?php else: ?>
                        <?php foreach ($lamaranPreview as $i => $pelamar): ?>
                        <tr>
                            <td><?= $i + 1 ?></td>
                            <td><?= esc($pelamar['nama_pelamar']) ?></td>
                            <td><?= esc($pelamar['posisi']) ?></td>
                            <td><?= esc($pelamar['mitra_kerja']) ?></td>
                            <td><?= esc($pelamar['tanggal_lamar']) ?></td>
                            <td><span class="badge text-bg-light border"><?= esc($pelamar['status']) ?></span></td>
                            <td><a href="<?= base_url('admin/pelamar') ?>" class="text-decoration-none"><i class="bi bi-eye"></i></a></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Kelola Mitra Kerja: preview 5 perusahaan terbaru (PerusahaanModel::getAllDenganUser) -->
<div class="card admin-card mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">Kelola Mitra Kerja</h5>
            <a href="<?= base_url('admin/perusahaan') ?>" class="btn btn-admin-primary btn-sm">LIHAT SEMUA</a>
        </div>
        <div class="table-responsive">
            <table class="table table-admin align-middle mb-0">
                <thead>
                    <tr><th>NO</th><th>Nama Usaha</th><th>Pemilik</th><th>Email</th><th>Alamat</th><th>Status</th><th>Aksi</th></tr>
                </thead>
                <tbody>
                    <?php if (empty($perusahaanPreview)): ?>
                        <tr><td colspan="7" class="text-center text-muted py-3">Belum ada data</td></tr>
                    <?php else: ?>
                        <?php foreach ($perusahaanPreview as $i => $mitra): ?>
                        <tr>
                            <td><?= $i + 1 ?></td>
                            <td><?= esc($mitra['nama_perusahaan']) ?></td>
                            <td><?= esc($mitra['pemilik']) ?></td>
                            <td><?= esc($mitra['email']) ?></td>
                            <td><?= esc($mitra['alamat']) ?></td>
                            <!-- Status selalu "Aktif": tabel perusahaan tidak punya kolom
                                 status sungguhan, lihat PerusahaanModel::getAllDenganUser() -->
                            <td><span class="badge text-bg-light border">Aktif</span></td>
                            <td>
                                <a href="<?= base_url('admin/perusahaan') ?>" class="text-decoration-none me-2"><i class="bi bi-eye"></i></a>
                                <a href="<?= base_url('admin/perusahaan/hapus/' . $mitra['id']) ?>"
                                   class="text-decoration-none text-danger"
                                   onclick="return confirm('Hapus mitra kerja ini?')"><i class="bi bi-trash"></i></a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Daftar Lowongan: preview 5 lowongan terbaru (LowonganModel::getAllDenganPerusahaan) -->
<div class="card admin-card mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">Daftar Lowongan</h5>
            <a href="<?= base_url('admin/lowongan') ?>" class="btn btn-admin-primary btn-sm">LIHAT SEMUA</a>
        </div>
        <div class="table-responsive">
            <table class="table table-admin align-middle mb-0">
                <thead>
                    <tr><th>NO</th><th>Posisi Pekerjaan</th><th>Jam Kerja</th><th>Lokasi</th><th>Mitra Kerja</th><th>Aksi</th></tr>
                </thead>
                <tbody>
                    <?php if (empty($lowonganPreview)): ?>
                        <tr><td colspan="6" class="text-center text-muted py-3">Belum ada data</td></tr>
                    <?php else: ?>
                        <?php foreach ($lowonganPreview as $i => $lowongan): ?>
                        <tr>
                            <td><?= $i + 1 ?></td>
                            <td><?= esc($lowongan['posisi']) ?></td>
                            <td><?= esc($lowongan['jam_kerja']) ?></td>
                            <td><?= esc($lowongan['lokasi']) ?></td>
                            <td><?= esc($lowongan['mitra_kerja']) ?></td>
                            <td>
                                <a href="<?= base_url('admin/lowongan/edit/' . $lowongan['id']) ?>" class="text-decoration-none me-2"><i class="bi bi-eye"></i></a>
                                <a href="<?= base_url('admin/lowongan/hapus/' . $lowongan['id']) ?>"
                                   class="text-decoration-none text-danger"
                                   onclick="return confirm('Hapus lowongan ini?')"><i class="bi bi-trash"></i></a>
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
