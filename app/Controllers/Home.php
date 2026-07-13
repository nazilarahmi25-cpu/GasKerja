<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\PerusahaanModel;
use App\Models\PencariKerjaModel;
use App\Models\LamaranModel;
use App\Models\LowonganModel;

/**
 * Controller untuk halaman-halaman publik & alur akun pengguna (auth,
 * registrasi, apply lamaran). Halaman khusus admin ada di AdminController,
 * halaman khusus perusahaan ada di PerusahaanController.
 */
class Home extends BaseController
{
    /**
     * Halaman beranda. Menyiapkan 2 daftar lowongan untuk ditampilkan:
     * "Lowongan Populer" (urut jumlah pelamar terbanyak) dan "Lowongan
     * Terbaru" (urut tanggal posting terbaru). Keduanya cuma menampilkan
     * lowongan berstatus 'aktif' karena ini halaman publik.
     *
     * @return string View halaman beranda (landing_page).
     */
    public function index()
    {
        $lowonganModel = new LowonganModel();

        // Populer: urut jumlah pelamar terbanyak. Terbaru: urut tanggal.
        // Keduanya cuma tampilkan status 'aktif' ke publik.
        $data['lowonganPopuler'] = array_slice($lowonganModel->getPopulerDenganPerusahaan(true), 0, 3);
        $data['lowonganTerbaru'] = array_slice($lowonganModel->getAllDenganPerusahaan(true), 0, 6);

        return view('landing_page', $data);
    }

    /**
     * Halaman statis "Tentang Kami".
     *
     * @return string
     */
    public function about_us()
    {
        return view('about_us');
    }

    /**
     * Halaman notifikasi. Saat ini masih menampilkan view statis — belum
     * mengambil data dari tabel `notifikasi` (fitur ini belum dibangun).
     *
     * @return string
     */
    public function notifikasi()
    {
        return view('notifikasi');
    }

    /**
     * Halaman profil akun yang sedang login. Cuma menampilkan data dari
     * tabel `users` (nama, email, dst) — belum menampilkan data profil
     * tambahan dari `pencari_kerja`/`perusahaan`.
     *
     * @return string|\CodeIgniter\HTTP\RedirectResponse Redirect ke /login
     *         kalau belum login, atau view halaman profil.
     */
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

    /**
     * DEAD CODE — tergantikan oleh detailLowongan($id) di bawah (route
     * lowongan/(:num)), yang menampilkan data lowongan asli sesuai ID.
     * Method & route lama ini ('detail-lowongan') dibiarkan tetap ada
     * (tidak dihapus) tapi sudah tidak ditautkan dari halaman mana pun —
     * cuma menampilkan view dengan data hardcode/kosong.
     *
     * @return string
     */
    public function detail_lowongan()
    {
        return view('detail_lowongan');
    }

    /**
     * Halaman detail satu lowongan (publik), diakses lewat route
     * `lowongan/(:num)`.
     *
     * @param int $lowonganId ID lowongan dari segment URL.
     *
     * @return string View halaman detail lowongan.
     *
     * @throws \CodeIgniter\Exceptions\PageNotFoundException Kalau
     *         lowongan tidak ditemukan, sudah dihapus (soft delete), ATAU
     *         statusnya bukan 'aktif' (pending/nonaktif). Ketiga kasus ini
     *         sengaja diperlakukan SAMA (404) supaya publik tidak bisa
     *         membedakan "lowongan tidak ada" dari "lowongan ada tapi
     *         belum disetujui admin" — datanya tidak boleh bocor.
     */
    public function detailLowongan($lowonganId)
    {
        $lowonganModel = new LowonganModel();
        $lowongan = $lowonganModel->findDenganPerusahaan($lowonganId);

        // Lowongan yang statusnya bukan 'aktif' (pending/nonaktif) diperlakukan
        // sama seperti tidak ditemukan — jangan bocorkan datanya ke publik.
        if (!$lowongan || $lowongan['status'] !== 'aktif') {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        return view('detail_lowongan', ['lowongan' => $lowongan]);
    }

    /**
     * Halaman form untuk melamar sebuah lowongan (belum dibangun UI-nya —
     * masih view kosong). Proses simpan lamaran yang sesungguhnya ada di
     * processApply() di bawah.
     *
     * @return string|\CodeIgniter\HTTP\RedirectResponse
     */
    public function apply_lowongan()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        return view('apply_lowongan');
    }

    /**
     * Dashboard untuk role pencari_kerja. Diakses lewat route
     * dashboard-pencari (sudah dijaga filter auth:pencari_kerja di
     * Routes.php, jadi pengecekan role tidak perlu diulang di sini).
     *
     * @return string|\CodeIgniter\HTTP\RedirectResponse
     */
    public function dashboard_pencari()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        return view('dashboard_pencari');
    }

    /**
     * Dashboard untuk role perusahaan. Diakses lewat route
     * dashboard-perusahaan (sudah dijaga filter auth:perusahaan).
     *
     * @return string|\CodeIgniter\HTTP\RedirectResponse
     */
    public function dashboard_perusahaan()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        return view('dashboard_perusahaan');
    }

    /**
     * DEAD CODE — tergantikan oleh AdminController::dashboard() (route
     * dashboard-admin sekarang diarahkan ke sana, lihat Routes.php).
     * Method ini masih menampilkan view statis lama dengan angka hardcode,
     * dibiarkan ada tapi sudah tidak ditautkan.
     *
     * @return string|\CodeIgniter\HTTP\RedirectResponse
     */
    public function dashboard_admin()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        return view('dashboard_admin');
    }

    /**
     * Halaman form login. Kalau sudah login, langsung redirect ke
     * dashboard sesuai role (tidak perlu login ulang).
     *
     * @return string|\CodeIgniter\HTTP\RedirectResponse
     */
    public function login()
    {
        if (session()->get('logged_in')) {
            return $this->redirectByRole(
                session()->get('role')
            );
        }

        return view('auth/login');
    }

    /**
     * Memproses submit form login. Satu form yang sama dipakai untuk
     * ketiga role (admin, perusahaan, pencari_kerja) — dibedakan lewat
     * kolom `role` di tabel `users`, bukan form terpisah.
     *
     * Selain user_id/nama/email/role/logged_in, session juga diisi
     * perusahaan_id atau pencari_id (tergantung role), karena
     * lowongan.perusahaan_id dan lamaran.pencari_id merujuk ke ID tabel
     * profil masing-masing (perusahaan/pencari_kerja), BUKAN ke users.id
     * secara langsung. Tanpa ini, fitur seperti "tambah lowongan" atau
     * "apply lamaran" tidak akan tahu ID mana yang harus dipakai.
     *
     * @return \CodeIgniter\HTTP\RedirectResponse Redirect balik ke form
     *         login dengan pesan error kalau email/password salah, atau
     *         redirect ke dashboard sesuai role kalau berhasil.
     */
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

    /**
     * Logout — menghapus seluruh data session, lalu redirect ke halaman
     * login.
     *
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function logout()
    {
        session()->destroy();

        return redirect()->to('/login')
            ->with('success', 'Berhasil logout');
    }

    /**
     * Halaman form registrasi akun pencari kerja.
     *
     * @return string
     */
    public function register()
    {
        return view('auth/register');
    }

    /**
     * Memproses submit form registrasi pencari kerja. Membuat 2 baris
     * sekaligus: akun login di `users` (role='pencari_kerja') dan profil
     * kosong di `pencari_kerja` yang terhubung lewat user_id — profil ini
     * WAJIB dibuat di sini karena lamaran.pencari_id nanti akan merujuk ke
     * baris ini, bukan ke users.id (lihat catatan di LamaranModel).
     *
     * @return \CodeIgniter\HTTP\RedirectResponse Redirect balik dengan
     *         pesan error validasi, atau ke /login kalau berhasil.
     */
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

    /**
     * DEAD CODE — duplikat lama dari processRegisterPerusahaan() di bawah,
     * tidak pernah di-routing (cek Routes.php: route register-perusahaan
     * mengarah ke processRegisterPerusahaan, bukan ke method ini). Method
     * ini juga cuma menyimpan ke tabel `users`, TIDAK membuat baris profil
     * di `perusahaan` seperti versi yang aktif — kalau sampai dipakai,
     * perusahaan_id-nya akan salah acu (lihat bug #1 di riwayat
     * perbaikan). Dibiarkan ada untuk referensi, jangan dipakai.
     *
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
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

    /**
     * Memproses submit form registrasi perusahaan. Sama seperti
     * processRegister() untuk pencari kerja: membuat baris akun login di
     * `users` (role='perusahaan') SEKALIGUS baris profil di `perusahaan`
     * yang terhubung lewat user_id. Kedua baris ini wajib dibuat bersamaan
     * karena lowongan.perusahaan_id nanti merujuk ke perusahaan.id, bukan
     * users.id (lihat catatan arsitektur di PerusahaanModel).
     *
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
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

    /**
     * Memproses submit lamaran kerja (tombol "Lamar Sekarang"). Menyimpan
     * baris baru ke tabel `lamaran` kalau semua validasi & pengecekan
     * lolos.
     *
     * Urutan pengecekan:
     * 1. Harus login sebagai role pencari_kerja (perusahaan/admin tidak
     *    boleh melamar).
     * 2. Harus punya profil pencari_kerja yang valid (session pencari_id).
     * 3. Field lowongan_id wajib angka, cv_file wajib file pdf/doc/docx
     *    maks 2MB.
     * 4. Cegah melamar 2x ke lowongan yang sama (cek data yang sudah ada
     *    lebih dulu sebelum insert).
     *
     * @return \CodeIgniter\HTTP\RedirectResponse Redirect balik ke
     *         halaman sebelumnya dengan pesan sukses/error.
     */
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

    /**
     * Memperbarui data profil user yang sedang login. Saat ini cuma
     * mendukung field `nama` — fitur edit profil lengkap (alamat, no_hp,
     * skill, dst di tabel pencari_kerja/perusahaan) belum dibangun.
     *
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
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

    /**
     * Menentukan URL dashboard tujuan berdasarkan role user, dipakai
     * setelah login berhasil (atau saat user yang sudah login membuka
     * /login lagi).
     *
     * @param string $role Nilai kolom `role` dari tabel users
     *        ('admin'|'perusahaan'|'pencari_kerja').
     *
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
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
