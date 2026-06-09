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

        <div class="hero-card">
            <h3>Lowongan Populer</h3>

            <div class="job-mini">
                <strong>Frontend Developer</strong><br>
                <span>PT Teknologi Nusantara</span>
            </div>

            <div class="job-mini">
                <strong>UI/UX Designer</strong><br>
                <span>Creative Studio</span>
            </div>

            <div class="job-mini">
                <strong>Digital Marketing</strong><br>
                <span>Media Inspirasi</span>
            </div>
        </div>
    </div>
</section>

<!-- LOWONGAN TERBARU -->
<section class="section">
    <div class="container">
        <h2>Lowongan Terbaru</h2>
        <p>
            Temukan peluang kerja terbaru yang sesuai
            dengan minat dan keahlian Anda.
        </p>

        <div class="job-grid">
            <div class="job-card">
                <span class="job-type">Full Time</span>
                <h3>Frontend Developer</h3>
                <p>PT Teknologi Nusantara</p>
                <p>Palu, Sulawesi Tengah</p>
                <a href="<?= base_url('/detail-lowongan') ?>" class="btn-outline">
                    Lihat Detail
                </a>
            </div>

            <div class="job-card">
                <span class="job-type">Part Time</span>
                <h3>Admin Media Sosial</h3>
                <p>Creative Agency</p>
                <p>Remote</p>
                <a href="<?= base_url('/detail-lowongan') ?>" class="btn-outline">
                    Lihat Detail
                </a>
            </div>

            <div class="job-card">
                <span class="job-type">Freelance</span>
                <h3>Graphic Designer</h3>
                <p>Studio Kreatif</p>
                <p>Makassar</p>
                <a href="<?= base_url('/detail-lowongan') ?>" class="btn-outline">
                    Lihat Detail
                </a>
            </div>
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