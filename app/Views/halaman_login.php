<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>

<section class="auth-section">
    <div class="auth-card">
        <h2>Login</h2>
        <form>
            <input type="email" placeholder="Email">
            <input type="password" placeholder="Password">
            <button type="submit">Masuk</button>
        </form>
    </div>
</section>

<?= $this->endSection() ?>