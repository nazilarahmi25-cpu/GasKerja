<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>

<!-- =========================
     HERO DASHBOARD ADMIN
========================= -->
<section class="admin-hero">

    <div class="container">

        <div class="admin-hero-content">

            <div class="admin-welcome">

                <!-- <span class="admin-badge">
                    Dashboard Administrator
                </span> -->

                <h1>
                    Selamat Datang,
                    Administrator
                </h1>

                <p>
                    Pantau aktivitas sistem GasKerja, kelola pengguna,
                    perusahaan, dan lowongan pekerjaan dalam satu tempat.
                </p>

            </div>

            <div class="admin-stats-grid">

                <div class="admin-stat-card">

                    <h2>150</h2>

                    <p>Total Pencari Kerja</p>

                </div>

                <div class="admin-stat-card">

                    <h2>35</h2>

                    <p>Total Perusahaan</p>

                </div>

                <div class="admin-stat-card">

                    <h2>82</h2>

                    <p>Total Lowongan</p>

                </div>

            </div>

        </div>

    </div>

</section>


<!-- =========================
     AKTIVITAS TERBARU
========================= -->
<section class="admin-section">

    <div class="container">

        <div class="admin-title">
            <h2>Aktivitas Terbaru</h2>
        </div>

        <div class="admin-grid">

            <div class="admin-card">

                <span class="admin-label">
                    Perusahaan Baru
                </span>

                <h3>PT Maju Bersama</h3>

                <p>Terdaftar : 07 Juni 2026</p>

                <a href="#" class="admin-link-btn">
                    Lihat Detail
                </a>

            </div>

            <div class="admin-card">

                <span class="admin-label">
                    Lowongan Baru
                </span>

                <h3>Backend Developer</h3>

                <p>PT Teknologi Indonesia</p>

                <a href="#" class="admin-link-btn">
                    Lihat Detail
                </a>

            </div>

            <div class="admin-card">

                <span class="admin-label">
                    Pengguna Baru
                </span>

                <h3>Andi Saputra</h3>

                <p>Pencari Kerja</p>

                <a href="#" class="admin-link-btn">
                    Lihat Profil
                </a>

            </div>

        </div>

    </div>

</section>


<!-- =========================
     MENU ADMIN
========================= -->
<section class="admin-section">

    <div class="container">

        <div class="admin-title">
            <h2>Menu Admin</h2>
        </div>

        <div class="admin-grid">

            <div class="admin-card">
                <h3>🏢 Kelola Perusahaan</h3>
                <p>
                    Melihat, mengubah dan menghapus data perusahaan.
                </p>
            </div>

            <div class="admin-card">
                <h3>💼 Kelola Lowongan</h3>
                <p>
                    Mengelola seluruh lowongan pekerjaan.
                </p>
            </div>

            <div class="admin-card">
                <h3>👥 Kelola Pengguna</h3>
                <p>
                    Mengatur akun pencari kerja dan admin.
                </p>
            </div>

            <div class="admin-card">
                <h3>📊 Statistik</h3>
                <p>
                    Melihat perkembangan pengguna dan lowongan.
                </p>
            </div>

            <div class="admin-card">
                <h3>📄 Laporan</h3>
                <p>
                    Menghasilkan laporan sistem GasKerja.
                </p>
            </div>

            <div class="admin-card">
                <h3>⚙ Pengaturan</h3>
                <p>
                    Mengatur konfigurasi website.
                </p>
            </div>

        </div>

    </div>

</section>

<?= $this->endSection() ?>