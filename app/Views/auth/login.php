<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>

<section class="auth-section">
    <div class="auth-card">

        <h2>Login</h2>

        <div class="role-selector">

            <!-- <div class="role-card active">
                <span>👤</span>
                <p>Pencari Kerja</p>
            </div>

            <div class="role-card">
                <span>🏢</span>
                <p>UMKM</p>
            </div> -->

        </div>

        <form>

            <label>Email</label>
            <input type="email" placeholder="nama@email.com">

            <label>Password</label>
            <input type="password" placeholder="Masukkan password">

            <button type="submit">
                Masuk
            </button>

        </form>

    </div>
</section>

<?= $this->endSection() ?>