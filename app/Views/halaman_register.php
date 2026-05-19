<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>

<section class="auth-section">
    <div class="auth-card">
        <h2>Daftar Akun</h2>
        <form>
            <input type="text" placeholder="Nama Lengkap">
            <input type="email" placeholder="Email">
            <input type="password" placeholder="Password">
            <button type="submit">Daftar</button>
        </form>
    </div>
</section>

<?= $this->endSection() ?>