<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>

<section class="hero">
    <div class="container hero-grid">

        <div class="hero-text">

            <h1>
                Selamat Datang,
                <?= session()->get('nama_perusahaan') ?? 'Perusahaan Anda'; ?>
            </h1>

            <p>
                Kelola lowongan pekerjaan dan temukan kandidat terbaik
                untuk perusahaan Anda melalui GasKerja.
            </p>

            <div class="hero-buttons">
                <a href="<?= base_url('/perusahaan/tambah-lowongan') ?>" class="btn-primary">
                    + Tambah Lowongan
                </a>

                <a href="<?= base_url('/perusahaan/lowongan') ?>" class="btn-secondary">
                    Kelola Lowongan
                </a>
            </div>

        </div>

        <div class="hero-card">

            <h3>Statistik</h3>

            <div class="job-mini">
                <strong>12</strong><br>
                <span>Total Lowongan</span>
            </div>

            <div class="job-mini">
                <strong>54</strong><br>
                <span>Total Pelamar</span>
            </div>

            <div class="job-mini">
                <strong>7</strong><br>
                <span>Lowongan Aktif</span>
            </div>

        </div>

    </div>
</section>


<section class="section">

    <div class="container">

        <h2>Lowongan yang Anda Buat</h2>

        <div class="job-grid">

            <div class="job-card">

                <span class="job-type">Full Time</span>

                <h3>Frontend Developer</h3>

                <p>Deadline : 20 Juli 2026</p>

                <p>Pelamar : 12 Orang</p>

                <a href="#" class="btn-outline">
                    Lihat Pelamar
                </a>

            </div>


            <div class="job-card">

                <span class="job-type">Part Time</span>

                <h3>Admin Sosial Media</h3>

                <p>Deadline : 30 Juli 2026</p>

                <p>Pelamar : 8 Orang</p>

                <a href="#" class="btn-outline">
                    Lihat Pelamar
                </a>

            </div>


            <div class="job-card">

                <span class="job-type">Freelance</span>

                <h3>Graphic Designer</h3>

                <p>Deadline : 10 Agustus 2026</p>

                <p>Pelamar : 20 Orang</p>

                <a href="#" class="btn-outline">
                    Lihat Pelamar
                </a>

            </div>

        </div>

    </div>

</section>

<section class="section">

    <div class="container">

        <h2>Menu Cepat</h2>

        <div class="job-grid">

            <div class="job-card">
                <h3>➕ Tambah Lowongan</h3>
                <p>Buat lowongan pekerjaan baru.</p>
            </div>

            <div class="job-card">
                <h3>👥 Data Pelamar</h3>
                <p>Lihat semua pelamar yang masuk.</p>
            </div>

            <div class="job-card">
                <h3>🏢 Profil Perusahaan</h3>
                <p>Edit informasi perusahaan Anda.</p>
            </div>

        </div>

    </div>

</section>

<?= $this->endSection() ?>