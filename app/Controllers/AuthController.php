<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\RegisterPerusahaanModel;

// DEAD CODE: controller ini tidak pernah di-routing di app/Config/Routes.php.
// Flow login/register aktif ada di Home.php. Jangan dipakai sebagai acuan
// session key ('isLoggedIn' di sini beda dari 'logged_in' yang dipakai Home.php).
// Akan dibahas/dibersihkan di langkah cleanup terpisah.
class AuthController extends BaseController
{
    // ==============================
    // HALAMAN LOGIN
    // ==============================

    /**
     * [DEAD CODE — lihat catatan class di atas] Versi lama halaman login,
     * tidak pernah di-routing. Versi aktif: Home::login().
     *
     * @return string|\CodeIgniter\HTTP\RedirectResponse
     */
    public function login()
    {
        // Kalau sudah login, langsung redirect ke dashboard sesuai role
        if (session()->get('isLoggedIn')) {
            return $this->redirectByRole(session()->get('role'));
        }

        return view('auth/login');
    }

    // ==============================
    // PROSES LOGIN
    // ==============================

    /**
     * [DEAD CODE] Versi lama proses login. Set session key 'isLoggedIn'
     * (bukan 'logged_in' yang dipakai flow aktif) — kalau ini pernah
     * di-routing kembali di masa depan, ingat untuk menyamakan session
     * key-nya dulu, atau AuthFilter tidak akan mengenali user sebagai
     * login. Versi aktif: Home::processLogin().
     *
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function processLogin()
    {
        $model = new UserModel();

        $email    = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        // Cari user berdasarkan email di tabel users
        $user = $model->where('email', $email)->first();

        // Cek apakah user ada dan password cocok
        if ($user && password_verify($password, $user['password'])) {

            // Simpan data penting ke session
            session()->set([
                'user_id'    => $user['id'],
                'nama'       => $user['nama'],    
                'email'      => $user['email'],
                'role'       => $user['role'],    // 'admin' | 'perusahaan' | 'pencari_kerja'
                'isLoggedIn' => true,
            ]);

            // Redirect ke dashboard sesuai role
            return $this->redirectByRole($user['role']);
        }

        // Login gagal
        return redirect()->to('/login')
            ->with('error', 'password salah');
    }

    // ==============================
    // HALAMAN REGISTER PENCARI KERJA
    // ==============================

    /**
     * [DEAD CODE] Versi lama halaman register. Versi aktif:
     * Home::register().
     *
     * @return string|\CodeIgniter\HTTP\RedirectResponse
     */
    public function register()
    {
        if (session()->get('isLoggedIn')) {
            return $this->redirectByRole(session()->get('role'));
        }

        return view('auth/register');
    }

    // ==============================
    // PROSES REGISTER PENCARI KERJA
    // ==============================

    /**
     * [DEAD CODE] Versi lama proses registrasi pencari kerja. Beda dari
     * versi aktif (Home::processRegister()): method ini TIDAK membuat
     * baris profil di tabel `pencari_kerja` — kalau sampai dipakai, fitur
     * apply lamaran akan gagal karena session('pencari_id') tidak akan
     * pernah ter-set. Versi aktif: Home::processRegister().
     *
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function processRegister()
    {
        $model = new UserModel();

        // Validasi input
        $rules = [
            'nama'     => 'required|min_length[3]',
            'email'    => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[6]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->to('/register')
                ->with('errors', $this->validator->getErrors())
                ->withInput();
        }

        // Simpan ke tabel users — field sesuai migration users: nama, email, password, role
        $model->insert([
            'nama'     => $this->request->getPost('nama'),
            'email'    => $this->request->getPost('email'),
            'password' => password_hash(
                $this->request->getPost('password'),
                PASSWORD_DEFAULT
            ),
            'role'     => 'pencari_kerja',
        ]);

        return redirect()->to('/login')
            ->with('success', 'Registrasi berhasil! Silakan login.');
    }

    // ==============================
    // HALAMAN REGISTER PERUSAHAAN
    // ==============================

    /**
     * [DEAD CODE] Versi lama halaman register perusahaan. Versi aktif:
     * Home::register_perusahaan().
     *
     * @return string|\CodeIgniter\HTTP\RedirectResponse
     */
    public function registerPerusahaan()
    {
        if (session()->get('isLoggedIn')) {
            return $this->redirectByRole(session()->get('role'));
        }

        return view('auth/register_perusahaan');
    }

    // ==============================
    // PROSES REGISTER PERUSAHAAN
    // ==============================

    /**
     * [DEAD CODE] Versi lama proses registrasi perusahaan — arsitekturnya
     * beda total dari versi aktif: method ini simpan ke tabel terpisah
     * `RegisterPerusahaan` (lewat RegisterPerusahaanModel) alih-alih ke
     * `users` + `perusahaan`. RegisterPerusahaanModel sendiri punya bug
     * mismatch nama file/class (file `RegisterPerusahaan.php` isinya class
     * `RegisterPerusahaanModel`) yang bikin class ini gagal di-autoload —
     * jadi method ini akan fatal error "Class not found" kalau sampai
     * benar-benar dipanggil. Sengaja tidak diperbaiki (di luar scope saat
     * ini). Versi aktif: Home::processRegisterPerusahaan().
     *
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function processRegisterPerusahaan()
    {
        // Validasi input
        $rules = [
            'nama_umkm'    => 'required|min_length[3]',
            'email'        => 'required|valid_email|is_unique[RegisterPerusahaan.email]',
            'bidang_usaha' => 'required',
            'alamat'       => 'required',
            'telepon'      => 'required',
            'password'     => 'required|min_length[6]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->to('/register-perusahaan')
                ->with('errors', $this->validator->getErrors())
                ->withInput();
        }

        // Simpan ke tabel RegisterPerusahaan
        // Field sesuai migration: nama_umkm, email, bidang_usaha, alamat, telepon, password
        $perusahaanModel = new RegisterPerusahaanModel();
        $perusahaanModel->insert([
            'nama_umkm'    => $this->request->getPost('nama_umkm'),
            'email'        => $this->request->getPost('email'),
            'bidang_usaha' => $this->request->getPost('bidang_usaha'),
            'alamat'       => $this->request->getPost('alamat'),
            'telepon'      => $this->request->getPost('telepon'),
            'password'     => password_hash(
                $this->request->getPost('password'),
                PASSWORD_DEFAULT
            ),
        ]);

        return redirect()->to('/login')
            ->with('success', 'Registrasi UMKM berhasil! Silakan login.');
    }

    // ==============================
    // LOGOUT
    // ==============================

    /**
     * [DEAD CODE] Versi lama logout. Versi aktif: Home::logout().
     *
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login')
            ->with('success', 'Kamu berhasil keluar.');
    }

    // ==============================
    // HELPER: redirect berdasarkan role
    // ==============================

    /**
     * Menentukan URL dashboard tujuan berdasarkan role user. Fungsinya
     * sama seperti Home::redirectByRole(), tapi ini salinan terpisah
     * khusus dipakai method-method dead code di controller ini.
     *
     * @param string $role 'admin'|'perusahaan'|'pencari_kerja'.
     *
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    private function redirectByRole(string $role): \CodeIgniter\HTTP\RedirectResponse
    {
        switch ($role) {
            case 'admin':
                return redirect()->to('/dashboard-admin');
            case 'perusahaan':
                return redirect()->to('/dashboard-perusahaan');
            default: // pencari_kerja
                return redirect()->to('/dashboard-pencari');
        }
    }
}
