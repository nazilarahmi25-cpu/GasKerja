<?php
/**
 * Halaman beranda publik. Data lowongan disiapkan oleh Home::index():
 * - $lowonganPopuler: diurutkan berdasarkan JUMLAH PELAMAR terbanyak
 *   (LowonganModel::getPopulerDenganPerusahaan), untuk section
 *   "Lowongan Populer" di kartu hero.
 * - $lowonganTerbaru: diurutkan berdasarkan tanggal posting terbaru
 *   (LowonganModel::getAllDenganPerusahaan), untuk section
 *   "Lowongan Terbaru" di bawahnya.
 * Keduanya cuma berisi lowongan berstatus 'aktif' (lowongan pending/
 * nonaktif sengaja tidak dikirim ke view ini sama sekali).
 */
?>
<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>

<!-- HERO SECTION -->
<section class="hero">
    <div class="container hero-grid">
        <div class="hero-text">
            <!-- <span class="badge">Temukan Lowongan Terbaik</span> -->

            <h1>
              Temukan Peluang Kerja dan Pekerja Terbaik di Kota Palu

            </h1>

            <p>
                Platform andalan anak muda Palu untuk cari kerja part-time, dan solusi tercepat buat UMKM lokal yang butuh tenaga kerja siap aksi.
            </p>

            <!-- <div class="hero-buttons">
                <a href="<?= base_url('/lowongan') ?>" class="btn-primary"> Cari Lowongan </a>
                <a href="<?= base_url('/register') ?>" class="btn-secondary"> Pasang Lowonga </a>
            </div> -->
            
            <form class="search-box">
                <input type="text" placeholder="Cari lowongan kerja...">

                <select>
                    <option>Semua Tipe</option>
                    <option>Full Time</option>
                    <option>Part Time</option>
                    <option>Freelance</option>
                </select>

                <button type="submit">Cari</button>
            </form>
            
        </div>

        <!-- Lowongan Populer: urut jumlah pelamar terbanyak (lihat docblock di atas) -->
        <div class="hero-card">
            <h3>Lowongan Populer</h3>

            <?php if (empty($lowonganPopuler)): ?>
                <p>Belum ada lowongan tersedia.</p>
            <?php else: ?>
                <?php foreach ($lowonganPopuler as $lowongan): ?>
                    <a href="<?= base_url('lowongan/' . $lowongan['id']) ?>" class="job-mini-link">
                        <div class="job-mini">
                            <strong><?= esc($lowongan['posisi']) ?></strong><br>
                            <span><?= esc($lowongan['mitra_kerja']) ?></span>
                        </div>
                    </a>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- LOWONGAN TERBARU: urut tanggal_post terbaru (lihat docblock di atas) -->
<section class="section">
    <div class="container">
        <h2>Lowongan Terbaru</h2>
        <p>
            Temukan peluang kerja terbaru yang sesuai
            dengan minat dan keahlian Anda.
        </p>

        <div class="job-grid">
            <?php if (empty($lowonganTerbaru)): ?>
                <p>Belum ada lowongan tersedia.</p>
            <?php else: ?>
                <?php foreach ($lowonganTerbaru as $lowongan): ?>
                    <div class="job-card">
                        <span class="job-type"><?= esc($lowongan['jam_kerja']) ?></span>
                        <h3><?= esc($lowongan['posisi']) ?></h3>
                        <p><?= esc($lowongan['mitra_kerja']) ?></p>
                        <p><?= esc($lowongan['lokasi']) ?></p>
                        <a href="<?= base_url('lowongan/' . $lowongan['id']) ?>" class="btn-outline">
                            Lihat Detail
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- KEUNGGULAN -->
<section class="section">
    <div class="container">
        <h2>Mengapa Memilih GasKerja?</h2>

        <div class="job-grid">
            <div class="job-card">
                <h3>Mudah Digunakan</h3>
                <p>
                    Cari lowongan dan lamar pekerjaan
                    dengan cepat dan sederhana.
                </p>
            </div>

            <div class="job-card">
                <h3>Banyak Lowongan</h3>
                <p>
                    Tersedia berbagai jenis pekerjaan dari
                    perusahaan terpercaya.
                </p>
            </div>

            <div class="job-card">
                <h3>Gratis</h3>
                <p>
                    Semua fitur dapat digunakan secara
                    gratis oleh pencari kerja.
                </p>
            </div>
        
        </div>
    </div>
</section>

<?= $this->endSection() ?>

<!--
    CATATAN: markup di bawah ini (role-selector) berada DI LUAR
    $this->section('content')/endSection() di atas, sehingga TIDAK PERNAH
    ikut dirender ke halaman (CI4 cuma mengambil isi di antara section()
    dan endSection() saat extend ke layout/template). Kode mati/sisa,
    dibiarkan apa adanya karena di luar scope perubahan saat ini.
-->
<label>Pilih Role</label>

    <div class="role-selector">

        <input type="radio" id="pencari" name="role" value="pencari" checked hidden>
        <label for="pencari" class="role-card">
            <span>👤</span>
            <p>Pencari Kerja</p>
        </label>

        <input type="radio" id="perusahaan" name="role" value="perusahaan" hidden>
    <label for="perusahaan" class="role-card">
            <span>🏢</span>
            <p>Perusahaan</p>
        </label>

</div>