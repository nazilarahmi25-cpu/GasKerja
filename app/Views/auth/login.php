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
        max-width: 420px;
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
        margin-bottom: 1.75rem;
    }
 
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
 
    .alert-success {
        background: #f0fdf4;
        color: #15803d;
        border: 1px solid #bbf7d0;
    }
 
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
 
        <h2>Selamat Datang 👋</h2>
        <p class="subtitle">Masuk ke akun kamu untuk melanjutkan</p>
 
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger">
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif ?>
 
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success">
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif ?>
 
        <form method="POST" action="<?= base_url('login') ?>">
            <?= csrf_field() ?>
 
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
                    placeholder="Masukkan password"
                    required>
            </div>
 
            <button type="submit" class="btn-primary">
                Masuk
            </button>
 
        </form>
 
        <p class="auth-footer">
            Belum punya akun?
            <a href="<?= base_url('register') ?>">Daftar sekarang</a>
        </p>
 
    </div>
</section>
 
<?= $this->endSection() ?>