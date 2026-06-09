<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>

<section class="auth-section">
    <div class="auth-card">

        <h2>Daftar Akun</h2>

        <label>Pilih Role</label>

        <div class="role-selector">
            <div class="role-card active">
                <span>👤</span>
                <p>Pencari Kerja</p>
            </div>

        <div class="role-card"
            onclick="window.location.href='<?= base_url('dashboard_perusahaan') ?>'">
            <span>🏢</span>
            <p>UMKM</p>
        </div>
    </div> <!-- penutup role-selector -->

    <form action="#" method="post">
            <label>Nama Lengkap</label>
            <input type="text" placeholder="John Doe">

            <label>Email</label>
            <input type="email" placeholder="nama@email.com">

            <label>Password</label>
            <div class="password-box">
                <input type="passwo<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<section class="auth-section">
    <div class="auth-card">

        <h2>Daftar Akun</h2>

        <label>Pilih Role</label>

        <div class="role-selector">
            <div class="role-card active"
                onclick="window.location.href='<?= base_url('register') ?>'">
                <span>👤</span>
                <p>Pencari Kerja</p>
            </div>

            <div class="role-card"
                onclick="window.location.href='<?= base_url('register-perusahaan') ?>'">
                <span>🏢</span>
                <p>UMKM</p>
            </div>
        </div>

        <form method="POST" action="<?= base_url('register') ?>">

            <label>Nama Lengkap</label>
            <input type="text" name="nama" placeholder="John Doe" required>

            <label>Email</label>
            <input type="email" name="email" placeholder="nama@email.com" required>

            <label>Password</label>
            <input type="password" name="password" placeholder="Minimal 6 karakter" required>

            <button type="submit" class="btn-primary">
                Daftar
            </button>

        </form>

    </div>
</section>

<?= $this->endSection() ?>rd" placeholder="Minimal 8 karakter">
                <span></span>
            </div>

            <label>Konfirmasi Password</label>
            <div class="password-box">
                <input type="password" placeholder="Masukkan password lagi">
                <span></span>
            </div>

            <div class="checkbox-group">
                <input type="checkbox" id="setuju">
                <label for="setuju">
                    Saya menyetujui
                    <a href="#">syarat</a>
                    dan
                    <a href="#">ketentuan</a>
                    yang berlaku
                </label>
            </div>

            <button type="submit" class="btn-primary">
                Daftar
            </button>

        </form>

    </div>
</section>

<?= $this->endSection() ?>