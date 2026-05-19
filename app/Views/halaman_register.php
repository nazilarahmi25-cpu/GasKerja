<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>

<section class="auth-section">
    <div class="auth-card">
        <h2>Daftar Akun</h2>

        <form action="#" method="post">
            <input type="text" name="nama" placeholder="Nama Lengkap" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>

            <button type="submit">Daftar</button>
        </form>

        <p>
            Sudah punya akun?
            <a href="<?= base_url('/login') ?>">Login</a>
        </p>
    </div>
</section>

<?= $this->endSection() ?>