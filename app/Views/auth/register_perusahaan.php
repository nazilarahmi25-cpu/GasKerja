<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<section class="auth-section">
    <div class="auth-card">

        <h2>Daftar Akun UMKM</h2>

        <label>Pilih Role</label>

        <div class="role-selector">

            <div class="role-card"
                onclick="window.location.href='<?= base_url('register') ?>'">
                <span>👤</span>
                <p>Pencari Kerja</p>
            </div>

            <div class="role-card active"
                onclick="window.location.href='<?= base_url('register-perusahaan') ?>'">
                <span>🏢</span>
                <p>UMKM</p>
            </div>

        </div>

        <form method="POST" action="<?= base_url('register-perusahaan') ?>">

            <input type="text" name="nama_umkm" placeholder="Nama UMKM" required>

            <input type="email" name="email" placeholder="Email UMKM" required>

            <input type="text" name="bidang_usaha" placeholder="Bidang Usaha" required>

            <input type="text" name="alamat" placeholder="Alamat UMKM" required>

            <input type="text" name="telepon" placeholder="Nomor Telepon" required>

            <input type="password" name="password" placeholder="Password" required>

            <input type="password" name="konfirmasi_password" placeholder="Konfirmasi Password" required>

            <button type="submit" class="btn-auth">
                Daftar Sebagai UMKM
            </button>

        </form>

        <p class="switch-auth">
            Sudah punya akun?
            <a href="<?= base_url('login') ?>">
                Login UMKM
            </a>
        </p>

    </div>
</section>

<?= $this->endSection() ?>