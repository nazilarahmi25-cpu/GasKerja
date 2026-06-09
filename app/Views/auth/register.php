
<?= $this->extend('layout/template') ?>
 
<?= $this->section('content') ?>
 
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap');
 
    .auth-section {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f0f4ff;
        font-family: 'Plus Jakarta Sans', sans-serif;
        padding: 2rem 1rem;
    }
 
    .auth-card {
        background: #ffffff;
        border-radius: 20px;
        padding: 2.5rem 2rem;
        width: 100%;
        max-width: 440px;
        box-shadow: 0 8px 40px rgba(0, 0, 0, 0.08);
    }
 
    .auth-card h2 {
        font-size: 1.6rem;
        font-weight: 700;
        color: #111827;
        margin-bottom: 0.25rem;
    }
 
    .auth-card .subtitle {
        font-size: 0.875rem;
        color: #6b7280;
        margin-bottom: 1.5rem;
    }
 
    /* Role Selector */
    .role-label {
        font-size: 0.875rem;
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.6rem;
        display: block;
    }
 
    .role-selector {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0.75rem;
        margin-bottom: 1.5rem;
    }
 
    .role-card {
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        padding: 0.9rem 0.75rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s;
        background: #fafafa;
    }
 
    .role-card:hover {
        border-color: #a5b4fc;
        background: #f5f3ff;
    }
 
    .role-card.active {
        border-color: #4f46e5;
        background: #eef2ff;
    }
 
    .role-card span {
        font-size: 1.5rem;
        display: block;
        margin-bottom: 0.3rem;
    }
 
    .role-card p {
        font-size: 0.8rem;
        font-weight: 600;
        color: #374151;
        margin: 0;
    }
 
    .role-card.active p {
        color: #4f46e5;
    }
 
    /* Alert */
    .alert {
        border-radius: 10px;
        padding: 0.75rem 1rem;
        margin-bottom: 1.25rem;
        font-size: 0.875rem;
    }
 
    .alert-danger {
        background: #fef2f2;
        color: #b91c1c;
        border: 1px solid #fecaca;
    }
 
    .alert-danger p {
        margin: 0.2rem 0;
    }
 
    .alert-success {
        background: #f0fdf4;
        color: #15803d;
        border: 1px solid #bbf7d0;
    }
 
    /* Form */
    .form-group {
        margin-bottom: 1.1rem;
    }
 
    .form-group label {
        display: block;
        font-size: 0.875rem;
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.4rem;
    }
 
    .form-group input {
        width: 100%;
        padding: 0.7rem 1rem;
        border: 1.5px solid #e5e7eb;
        border-radius: 10px;
        font-size: 0.9rem;
        font-family: 'Plus Jakarta Sans', sans-serif;
        color: #111827;
        background: #fafafa;
        transition: border-color 0.2s, box-shadow 0.2s;
        box-sizing: border-box;
    }
 
    .form-group input:focus {
        outline: none;
        border-color: #4f46e5;
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        background: #fff;
    }
 
    .btn-primary {
        width: 100%;
        padding: 0.8rem;
        background: #4f46e5;
        color: white;
        border: none;
        border-radius: 10px;
        font-size: 0.95rem;
        font-weight: 600;
        font-family: 'Plus Jakarta Sans', sans-serif;
        cursor: pointer;
        margin-top: 0.5rem;
        transition: background 0.2s, transform 0.1s;
    }
 
    .btn-primary:hover {
        background: #4338ca;
    }
 
    .btn-primary:active {
        transform: scale(0.98);
    }
 
    .auth-footer {
        text-align: center;
        margin-top: 1.25rem;
        font-size: 0.875rem;
        color: #6b7280;
    }
 
    .auth-footer a {
        color: #4f46e5;
        font-weight: 600;
        text-decoration: none;
    }
 
    .auth-footer a:hover {
        text-decoration: underline;
    }
</style>
 
<section class="auth-section">
    <div class="auth-card">
 
        <h2>Buat Akun Baru ✨</h2>
        <p class="subtitle">Pilih tipe akun kamu terlebih dahulu</p>
 
        <span class="role-label">Daftar sebagai</span>
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
 
        <?php if (session()->getFlashdata('errors')): ?>
            <div class="alert alert-danger">
                <?php foreach (session()->getFlashdata('errors') as $error): ?>
                    <p><?= esc($error) ?></p>
                <?php endforeach ?>
            </div>
        <?php endif ?>
 
        <form method="POST" action="<?= base_url('register') ?>">
            <?= csrf_field() ?>
 
            <div class="form-group">
                <label for="nama">Nama Lengkap</label>
                <input
                    type="text"
                    id="nama"
                    name="nama"
                    placeholder="John Doe"
                    value="<?= old('nama') ?>"
                    required>
            </div>
 
            <div class="form-group">
                <label for="email">Email</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    placeholder="nama@email.com"
                    value="<?= old('email') ?>"
                    required>
            </div>
 
            <div class="form-group">
                <label for="password">Password</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Minimal 6 karakter"
                    required>
            </div>
 
            <button type="submit" class="btn-primary">
                Daftar Sekarang
            </button>
 
        </form>
 
        <p class="auth-footer">
            Sudah punya akun?
            <a href="<?= base_url('login') ?>">Masuk di sini</a>
        </p>
 
    </div>
</section>
 
<?= $this->endSection() ?>