<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>

<section class="about-us">

    <div class="container about-grid">

        <!-- TEXT -->
        <div class="about-text">

            <span class="about-badge">
                Tentang GasKerja
            </span>

            <h2>
                Platform Lowongan Kerja Modern
            </h2>

            <p>
                GasKerja adalah platform lowongan kerja modern
                yang membantu pengguna menemukan pekerjaan
                dengan cepat, mudah, dan terpercaya.
            </p>

            <p>
                Website ini menyediakan fitur pencarian lowongan,
                filter kategori pekerjaan, detail perusahaan,
                serta informasi lowongan terbaru.
            </p>

            <!-- FITUR -->
            <div class="about-feature">

                <div class="feature-box">
                    <h3>100+</h3>
                    <span>Lowongan Aktif</span>
                </div>

                <div class="feature-box">
                    <h3>50+</h3>
                    <span>Perusahaan</span>
                </div>

                <div class="feature-box">
                    <h3>24/7</h3>
                    <span>Akses Online</span>
                </div>

            </div>

            <!-- VISI -->
            <div class="about-card">

                <h3>Visi GasKerja</h3>

                <p>
                    Menjadi platform lowongan kerja terbaik
                    yang membantu masyarakat mendapatkan
                    pekerjaan secara cepat dan mudah.
                </p>

            </div>

            <!-- MISI -->
            <div class="about-card">

                <h3>Misi GasKerja</h3>

                <p>
                    Memberikan akses lowongan kerja terpercaya,
                    mempermudah pencarian pekerjaan,
                    dan membantu perusahaan menemukan kandidat terbaik.
                </p>

            </div>

        </div>

        <!-- GAMBAR -->
        <div class="about-image">

            <img src="<?= base_url('assets/image/logo.png') ?>" alt="About GasKerja">

        </div>

    </div>

</section>

<?= $this->endSection() ?>