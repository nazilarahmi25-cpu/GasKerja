<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\RegisterPerusahaanModel;

class AuthController extends BaseController
{
    // ==============================
    // HALAMAN LOGIN
    // ==============================

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

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login')
            ->with('success', 'Kamu berhasil keluar.');
    }

    // ==============================
    // HELPER: redirect berdasarkan role
    // ==============================

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
