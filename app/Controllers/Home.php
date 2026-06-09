<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\PerusahaanModel;

class Home extends BaseController
{
    // =====================
    // HALAMAN STATIS
    // =====================
<<<<<<< HEAD

=======
    
>>>>>>> ca74a951bdd41c767a276d18f7758cf12a4fcedc
    public function index()
    {
        return view('landing_page');
    }

    public function about_us()
    {
        return view('about_us');
    }

    public function notifikasi()
    {
        return view('notifikasi');
    }

    public function profil()
    {
        return view('profil');
    }

    public function detail_lowongan()
    {
        return view('detail_lowongan');
    }

    public function apply_lowongan()
    {
        return view('apply_lowongan');
    }

    public function dashboard_pencari()
    {
        return view('dashboard_pencari');
    }

    public function dashboard_perusahaan()
    {
        return view('dashboard_perusahaan');
    }

    public function dashboard_admin()
    {
        return view('dashboard_admin');
    }

    // =====================
    // LOGIN
    // =====================

    public function login()
    {
<<<<<<< HEAD
        return view('halaman_login');
=======
        // Jika sudah login, redirect sesuai role
        if (session()->get('logged_in')) {
            return $this->redirectByRole(session()->get('role'));
        }

        return view('auth/login');
>>>>>>> ca74a951bdd41c767a276d18f7758cf12a4fcedc
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

        session()->set([
            'user_id'   => $user['id'],
            'nama'      => $user['nama'],
            'email'     => $user['email'],
            'role'      => $user['role'],
            'logged_in' => true,
        ]);

<<<<<<< HEAD
        if ($user['role'] === 'admin') {
            return redirect()->to('/dashboard-admin');
        } elseif ($user['role'] === 'perusahaan') {
            return redirect()->to('/dashboard-perusahaan');
        } else {
            return redirect()->to('/dashboard-pencari');
        }
=======
        return $this->redirectByRole($user['role']);
>>>>>>> ca74a951bdd41c767a276d18f7758cf12a4fcedc
    }

    public function logout()
    {
        session()->destroy();
<<<<<<< HEAD
        return redirect()->to('/login');
=======
        return redirect()->to('/login')
            ->with('success', 'Kamu berhasil keluar');
>>>>>>> ca74a951bdd41c767a276d18f7758cf12a4fcedc
    }

    // =====================
    // REGISTER PENCARI KERJA
    // =====================

    public function register()
    {
<<<<<<< HEAD
        return view('halaman_register');
=======
        // Jika sudah login, redirect sesuai role
        if (session()->get('logged_in')) {
            return $this->redirectByRole(session()->get('role'));
        }

        return view('auth/register');
>>>>>>> ca74a951bdd41c767a276d18f7758cf12a4fcedc
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
            'password' => password_hash(
<<<<<<< HEAD
                            $this->request->getPost('password'),
                            PASSWORD_DEFAULT
                          ),
=======
                $this->request->getPost('password'),
                PASSWORD_DEFAULT
            ),
>>>>>>> ca74a951bdd41c767a276d18f7758cf12a4fcedc
            'role'     => 'pencari_kerja',
        ]);

        return redirect()->to('/login')
            ->with('success', 'Registrasi berhasil, silakan login');
    }

    // =====================
    // REGISTER PERUSAHAAN
    // =====================

    public function register_perusahaan()
    {
<<<<<<< HEAD
        return view('halaman_register');
=======
        // Jika sudah login, redirect sesuai role
        if (session()->get('logged_in')) {
            return $this->redirectByRole(session()->get('role'));
        }

        return view('auth/register_perusahaan');
>>>>>>> ca74a951bdd41c767a276d18f7758cf12a4fcedc
    }

    public function processRegisterPerusahaan()
    {
        $userModel       = new UserModel();
        $perusahaanModel = new PerusahaanModel();

        $rules = [
<<<<<<< HEAD
            'nama_umkm'    => 'required',
=======
            'nama_umkm'    => 'required|min_length[3]',
>>>>>>> ca74a951bdd41c767a276d18f7758cf12a4fcedc
            'email'        => 'required|valid_email|is_unique[users.email]',
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

        // Simpan ke tabel users
        $userModel->save([
            'nama'     => $this->request->getPost('nama_umkm'),
            'email'    => $this->request->getPost('email'),
            'password' => password_hash(
<<<<<<< HEAD
                            $this->request->getPost('password'),
                            PASSWORD_DEFAULT
                          ),
=======
                $this->request->getPost('password'),
                PASSWORD_DEFAULT
            ),
>>>>>>> ca74a951bdd41c767a276d18f7758cf12a4fcedc
            'role'     => 'perusahaan',
        ]);

        $userId = $userModel->getInsertID();

        // Simpan ke tabel perusahaan
        $perusahaanModel->save([
            'user_id'         => $userId,
            'nama_perusahaan' => $this->request->getPost('nama_umkm'),
            'nama_umkm'       => $this->request->getPost('nama_umkm'),
            'bidang_usaha'    => $this->request->getPost('bidang_usaha'),
            'alamat'          => $this->request->getPost('alamat'),
            'telepon'         => $this->request->getPost('telepon'),
        ]);

        return redirect()->to('/login')
            ->with('success', 'Registrasi perusahaan berhasil, silakan login');
    }

    // =====================
    // APPLY LOWONGAN
    // =====================

    public function processApply()
    {
        // akan diisi nanti
    }

    public function updateProfil()
    {
        // akan diisi nanti
    }
<<<<<<< HEAD
=======

    // =====================
    // HELPER PRIVATE
    // =====================

    private function redirectByRole(string $role)
    {
        switch ($role) {
            case 'admin':
                return redirect()->to('/dashboard-admin');
            case 'perusahaan':
                return redirect()->to('/dashboard-perusahaan');
            default:
                return redirect()->to('/dashboard-pencari');
        }
    }
>>>>>>> ca74a951bdd41c767a276d18f7758cf12a4fcedc
}