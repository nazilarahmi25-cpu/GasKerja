<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\PerusahaanModel;

class Home extends BaseController
{
    public function index()
    {
        return view('pages/home');
    }

    public function login()
    {
        return view('pages/auth/login');
    }

    public function processLogin()
    {
        $userModel = new UserModel();
        $email     = $this->request->getPost('email');
        $password  = $this->request->getPost('password');

        $user = $userModel->where('email', $email)->first();

        if (!$user || !password_verify($password, $user['password'])) {
            return redirect()->to('/login')
                ->with('error', 'Email atau password salah');
        }

        // Simpan session
        session()->set([
            'user_id'   => $user['id'],
            'nama'      => $user['nama'],
            'email'     => $user['email'],
            'role'      => $user['role'],
            'logged_in' => true,
        ]);

        // Arahkan sesuai role
        if ($user['role'] === 'admin') {
            return redirect()->to('/dashboard-admin');
        } elseif ($user['role'] === 'perusahaan') {
            return redirect()->to('/dashboard-perusahaan');
        } else {
            return redirect()->to('/dashboard-pencari');
        }
    }

    public function register()
    {
        return view('pages/auth/register');
    }

    public function processRegister()
    {
        $userModel = new UserModel();

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

        $userModel->save([
            'nama'     => $this->request->getPost('nama'),
            'email'    => $this->request->getPost('email'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role'     => 'pencari_kerja',
        ]);

        return redirect()->to('/login')
            ->with('success', 'Registrasi berhasil, silakan login');
    }

    public function register_perusahaan()
    {
        return view('pages/auth/register_perusahaan');
    }

    public function processRegisterPerusahaan()
    {
        $userModel      = new UserModel();
        $perusahaanModel = new PerusahaanModel();

        $rules = [
            'nama_umkm'   => 'required',
            'email'       => 'required|valid_email|is_unique[users.email]',
            'bidang_usaha'=> 'required',
            'alamat'      => 'required',
            'telepon'     => 'required',
            'password'    => 'required|min_length[6]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->to('/register-perusahaan')
                ->with('errors', $this->validator->getErrors())
                ->withInput();
        }

        // 1. Simpan ke tabel users dulu
        $userModel->save([
            'nama'     => $this->request->getPost('nama_umkm'),
            'email'    => $this->request->getPost('email'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role'     => 'perusahaan',
        ]);

        $userId = $userModel->getInsertID(); // ambil ID user yang baru dibuat

        // 2. Simpan ke tabel perusahaan
        $perusahaanModel->save([
            'user_id'        => $userId,
            'nama_perusahaan'=> $this->request->getPost('nama_umkm'),
            'nama_umkm'      => $this->request->getPost('nama_umkm'),
            'bidang_usaha'   => $this->request->getPost('bidang_usaha'),
            'alamat'         => $this->request->getPost('alamat'),
            'telepon'        => $this->request->getPost('telepon'),
        ]);

        return redirect()->to('/login')
            ->with('success', 'Registrasi perusahaan berhasil, silakan login');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }

    // Halaman-halaman view biasa
    public function dashboard_pencari()  { return view('pages/dashboard_pencari'); }
    public function dashboard_perusahaan() { return view('pages/dashboard_perusahaan'); }
    public function dashboard_admin()    { return view('pages/dashboard_admin'); }
    public function detail_lowongan()    { return view('pages/detail_lowongan'); }
    public function apply_lowongan()     { return view('pages/apply_lowongan'); }
    public function profil()             { return view('pages/profil'); }
    public function notifikasi()         { return view('pages/notifikasi'); }
    public function about_us()           { return view('pages/about_us'); }

    public function processApply()   { /* nanti */ }
    public function updateProfil()   { /* nanti */ }
}