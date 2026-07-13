<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\PerusahaanModel;
use App\Models\PencariKerjaModel;
use App\Models\LamaranModel;

class Home extends BaseController
{
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
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        $userModel = new UserModel();

        $data['user'] = $userModel->find(
            session()->get('user_id')
        );

        return view('profil', $data);
    }

    public function detail_lowongan()
    {
        return view('detail_lowongan');
    }

    public function apply_lowongan()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        return view('apply_lowongan');
    }

    public function dashboard_pencari()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        return view('dashboard_pencari');
    }

    public function dashboard_perusahaan()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        return view('dashboard_perusahaan');
    }

    public function dashboard_admin()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        return view('dashboard_admin');
    }

    public function login()
    {
        if (session()->get('logged_in')) {
            return $this->redirectByRole(
                session()->get('role')
            );
        }

        return view('auth/login');
    }

    public function processLogin()
    {
        $userModel = new UserModel();

        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $user = $userModel
            ->where('email', $email)
            ->first();

        if (!$user) {
            return redirect()->back()
                ->with('error', 'Password salah ');
        }

        if (!password_verify($password, $user['password'])) {
            return redirect()->back()
                ->with('error', 'Password salah');
        }

        $sessionData = [
            'user_id'   => $user['id'],
            'nama'      => $user['nama'],
            'email'     => $user['email'],
            'role'      => $user['role'],
            'logged_in' => true
        ];

        // Perusahaan_id / pencari_id dipisah dari user_id karena
        // lowongan.perusahaan_id dan lamaran.pencari_id mengacu ke tabel
        // profil masing-masing (perusahaan / pencari_kerja), bukan users.
        if ($user['role'] === 'perusahaan') {
            $perusahaanModel = new PerusahaanModel();
            $perusahaan = $perusahaanModel->where('user_id', $user['id'])->first();
            $sessionData['perusahaan_id'] = $perusahaan['id'] ?? null;
        } elseif ($user['role'] === 'pencari_kerja') {
            $pencariKerjaModel = new PencariKerjaModel();
            $pencari = $pencariKerjaModel->where('user_id', $user['id'])->first();
            $sessionData['pencari_id'] = $pencari['id'] ?? null;
        }

        session()->set($sessionData);

        return $this->redirectByRole(
            $user['role']
        );
    }

    public function logout()
    {
        session()->destroy();

        return redirect()->to('/login')
            ->with('success', 'Berhasil logout');
    }

    public function register()
    {
        return view('auth/register');
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
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $userModel->save([
            'nama'     => $this->request->getPost('nama'),
            'email'    => $this->request->getPost('email'),
            'password' => password_hash(
                $this->request->getPost('password'),
                PASSWORD_DEFAULT
            ),
            'role'     => 'pencari_kerja'
        ]);

        // Buat juga baris profil di tabel pencari_kerja, terhubung lewat
        // user_id. Field lain (alamat, no_hp, dst) diisi belakangan lewat
        // halaman profil.
        $pencariKerjaModel = new PencariKerjaModel();
        $pencariKerjaModel->save([
            'user_id' => $userModel->getInsertID(),
        ]);

        return redirect()->to('/login')
            ->with('success', 'Registrasi berhasil');
    }

    public function Register_Perusahaan()
    {
    $userModel = new UserModel();

    $rules = [
        'nama_umkm'    => 'required|min_length[3]',
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

    // Baru simpan kalau validasi lolos
    $userModel->save([
        'nama'     => $this->request->getPost('nama_umkm'),
        'email'    => $this->request->getPost('email'),
        'password' => password_hash(
            $this->request->getPost('password'),
            PASSWORD_DEFAULT
        ),
        'role' => 'perusahaan',
    ]);

    return redirect()->to('/login')
        ->with('success', 'Registrasi berhasil! Silakan login.');
    }

    public function processRegisterPerusahaan()
    {
        $userModel = new UserModel();

        $userModel->save([
            'nama'     => $this->request->getPost('nama_umkm'),
            'email'    => $this->request->getPost('email'),
            'password' => password_hash(
                $this->request->getPost('password'),
                PASSWORD_DEFAULT
            ),
            'role'     => 'perusahaan'
        ]);

        // Buat juga baris profil di tabel perusahaan, terhubung lewat user_id
        $perusahaanModel = new PerusahaanModel();
        $perusahaanModel->save([
            'user_id'         => $userModel->getInsertID(),
            'nama_perusahaan' => $this->request->getPost('nama_umkm'),
            'alamat'          => $this->request->getPost('alamat'),
        ]);

        return redirect()->to('/login')
            ->with('success', 'Registrasi perusahaan berhasil');
    }

    public function processApply()
    {
        // Hanya pencari kerja yang boleh melamar
        if (!session()->get('logged_in') || session()->get('role') !== 'pencari_kerja') {
            return redirect()->to('/login')
                ->with('error', 'Hanya pencari kerja yang bisa melamar.');
        }

        $pencariId = session()->get('pencari_id');
        if (!$pencariId) {
            return redirect()->back()
                ->with('error', 'Profil pencari kerja tidak ditemukan.');
        }

        $rules = [
            'lowongan_id' => 'required|numeric',
            'cv_file'     => 'uploaded[cv_file]|max_size[cv_file,2048]|ext_in[cv_file,pdf,doc,docx]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $lowonganId = $this->request->getPost('lowongan_id');

        $lamaranModel = new LamaranModel();

        // Cegah apply dua kali ke lowongan yang sama
        $sudahApply = $lamaranModel
            ->where('pencari_id', $pencariId)
            ->where('lowongan_id', $lowonganId)
            ->first();

        if ($sudahApply) {
            return redirect()->back()
                ->with('error', 'Kamu sudah melamar ke lowongan ini sebelumnya.');
        }

        $file     = $this->request->getFile('cv_file');
        $fileName = $file->getRandomName();
        $file->move(WRITEPATH . 'uploads/cv', $fileName);

        $lamaranModel->save([
            'pencari_id'    => $pencariId,
            'lowongan_id'   => $lowonganId,
            'cv_file'       => $fileName,
            'surat_lamaran' => $this->request->getPost('surat_lamaran'),
            'status'        => 'diproses',
            'tanggal_lamar' => date('Y-m-d H:i:s'),
        ]);

        return redirect()->back()
            ->with('success', 'Lamaran berhasil dikirim');
    }

    public function updateProfil()
    {
        $userModel = new UserModel();

        $userModel->update(
            session()->get('user_id'),
            [
                'nama' => $this->request->getPost('nama')
            ]
        );

        return redirect()->back()
            ->with('success', 'Profil berhasil diperbarui');
    }

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
}
