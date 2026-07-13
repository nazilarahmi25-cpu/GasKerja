<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\LowonganModel;
use App\Models\LamaranModel;
use App\Models\PerusahaanModel;

/**
 * Controller untuk semua halaman & aksi khusus role admin: dashboard,
 * kelola pengguna, kelola mitra kerja (perusahaan), kelola lowongan, dan
 * kelola lamaran. Setiap method publik di sini WAJIB memanggil
 * cekAdmin() di baris pertama sebagai gerbang otorisasi.
 */
class AdminController extends BaseController
{
    protected $userModel;
    protected $lowonganModel;
    protected $lamaranModel;
    protected $perusahaanModel;

    public function __construct()
    {
        $this->userModel       = new UserModel();
        $this->lowonganModel   = new LowonganModel();
        $this->lamaranModel    = new LamaranModel();
        $this->perusahaanModel = new PerusahaanModel();
    }

    /**
     * Gerbang otorisasi: memastikan yang mengakses adalah user yang
     * sedang login DAN rolenya 'admin'. Dipanggil di baris pertama setiap
     * method publik di controller ini.
     *
     * @return \CodeIgniter\HTTP\RedirectResponse|null Redirect ke /login
     *         kalau akses ditolak, atau null kalau boleh lanjut (caller
     *         harus cek: `if ($redirect) return $redirect;`).
     */
    private function cekAdmin()
    {
        if (!session()->get('logged_in') || session()->get('role') !== 'admin') {
            return redirect()->to('/login')->with('error', 'Akses ditolak');
        }
        return null;
    }

    // ==========================
    // DASHBOARD
    // ==========================

    /**
     * Halaman utama dashboard admin: 3 kartu statistik (total user,
     * jumlah perusahaan, jumlah pelamar) dan 3 tabel preview (5 baris
     * teratas dari masing-masing: lamaran, perusahaan, lowongan) dengan
     * tautan "lihat semua" ke halaman lengkapnya.
     *
     * @return string View admin/dashboard.
     */
    public function dashboard()
    {
        $redirect = $this->cekAdmin();
        if ($redirect) return $redirect;

        $data['activeMenu'] = 'dashboard';

        $data['totalUsers']      = $this->userModel->countAllResults();
        $data['totalPerusahaan'] = $this->userModel->where('role', 'perusahaan')->countAllResults();
        $data['totalPelamar']    = $this->userModel->where('role', 'pencari_kerja')->countAllResults();

        $data['lamaranPreview']    = array_slice($this->lamaranModel->getAllDenganDetail(), 0, 5);
        $data['perusahaanPreview'] = array_slice($this->perusahaanModel->getAllDenganUser(), 0, 5);
        $data['lowonganPreview']   = array_slice($this->lowonganModel->getAllDenganPerusahaan(), 0, 5);

        return view('admin/dashboard', $data);
    }

    // ==========================
    // CRUD PENGGUNA (users)
    // ==========================

    /**
     * Menampilkan daftar semua pengguna dengan role pencari_kerja.
     *
     * @return string View admin/pengguna.
     */
    public function pengguna()
    {
        $redirect = $this->cekAdmin();
        if ($redirect) return $redirect;

        $data['users'] = $this->userModel
            ->where('role', 'pencari_kerja')
            ->findAll();

        return view('admin/pengguna', $data);
    }

    /**
     * Menghapus (soft delete) satu akun pengguna.
     *
     * @param int $userId ID baris di tabel `users`.
     *
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function hapusPengguna($userId)
    {
        $redirect = $this->cekAdmin();
        if ($redirect) return $redirect;

        $this->userModel->delete($userId);
        return redirect()->to('/admin/pengguna')
            ->with('success', 'Pengguna berhasil dihapus');
    }

    // ==========================
    // CRUD PERUSAHAAN
    // ==========================

    /**
     * Menampilkan daftar semua mitra kerja (perusahaan) beserta data
     * pemiliknya (nama, email dari tabel users).
     *
     * @return string View admin/perusahaan.
     */
    public function perusahaan()
    {
        $redirect = $this->cekAdmin();
        if ($redirect) return $redirect;

        // Ambil dari tabel perusahaan (bukan users) supaya dapat data asli
        // perusahaan (nama_perusahaan, alamat), JOIN ke users untuk email/pemilik
        $data['perusahaan'] = $this->perusahaanModel->getAllDenganUser();

        return view('admin/perusahaan', $data);
    }

    /**
     * Menghapus satu mitra kerja. Menghapus (soft delete) DUA baris
     * sekaligus: profil di `perusahaan` dan akun login terkait di `users`
     * — supaya akun itu juga tidak bisa login lagi setelah dihapus.
     *
     * Kenapa dua-duanya dihapus manual (bukan mengandalkan foreign key
     * CASCADE): soft delete cuma mengisi kolom deleted_at, bukan benar-benar
     * menghapus baris dari database — jadi FK "ON DELETE CASCADE" tidak
     * pernah terpicu (itu cuma jalan untuk penghapusan permanen/hard
     * delete). Makanya kedua tabel harus di-soft-delete satu per satu di
     * sini.
     *
     * @param int $perusahaanId ID baris di tabel `perusahaan` (BUKAN
     *        users.id — lihat catatan arsitektur di PerusahaanModel).
     *
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function hapusPerusahaan($perusahaanId)
    {
        $redirect = $this->cekAdmin();
        if ($redirect) return $redirect;

        $perusahaan = $this->perusahaanModel->find($perusahaanId);
        if ($perusahaan) {
            $this->userModel->delete($perusahaan['user_id']);
            $this->perusahaanModel->delete($perusahaanId);
        }

        return redirect()->to('/admin/perusahaan')
            ->with('success', 'Perusahaan berhasil dihapus');
    }

    // ==========================
    // CRUD LOWONGAN
    // ==========================

    /**
     * Menampilkan daftar semua lowongan (semua status — admin perlu lihat
     * yang pending untuk disetujui, bukan cuma yang aktif).
     *
     * @return string View admin/lowongan.
     */
    public function lowongan()
    {
        $redirect = $this->cekAdmin();
        if ($redirect) return $redirect;

        $data['lowongan'] = $this->lowonganModel->getAllDenganPerusahaan();
        return view('admin/lowongan', $data);
    }

    /**
     * Menampilkan form kosong untuk menambah lowongan baru (dropdown
     * daftar perusahaan diambil dari users berrole perusahaan).
     *
     * @return string View admin/form_lowongan.
     */
    public function tambahLowongan()
    {
        $redirect = $this->cekAdmin();
        if ($redirect) return $redirect;

        // Ambil daftar perusahaan untuk dropdown
        $data['perusahaan'] = $this->userModel->where('role', 'perusahaan')->findAll();
        return view('admin/form_lowongan', $data);
    }

    /**
     * Memproses submit form tambah lowongan (dari tambahLowongan()).
     *
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function simpanLowongan()
    {
        $redirect = $this->cekAdmin();
        if ($redirect) return $redirect;

        $rules = [
            'judul'        => 'required|min_length[3]',
            'deskripsi'    => 'required',
            'kualifikasi'  => 'required',
            'lokasi'       => 'required',
            'tipe_kerja'   => 'required',
            'status'       => 'required',
            'perusahaan_id'=> 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->to('/admin/lowongan/tambah')
                ->with('errors', $this->validator->getErrors())
                ->withInput();
        }

        $this->lowonganModel->save([
            'perusahaan_id' => $this->request->getPost('perusahaan_id'),
            'judul'         => $this->request->getPost('judul'),
            'deskripsi'     => $this->request->getPost('deskripsi'),
            'kualifikasi'   => $this->request->getPost('kualifikasi'),
            'lokasi'        => $this->request->getPost('lokasi'),
            'gaji'          => $this->request->getPost('gaji'),
            'tipe_kerja'    => $this->request->getPost('tipe_kerja'),
            'status'        => $this->request->getPost('status'),
            'tanggal_post'  => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('/admin/lowongan')
            ->with('success', 'Lowongan berhasil ditambahkan!');
    }

    /**
     * Menampilkan form edit untuk satu lowongan, sudah terisi data
     * sebelumnya.
     *
     * @param int $lowonganId ID baris di tabel `lowongan`.
     *
     * @return string|\CodeIgniter\HTTP\RedirectResponse View
     *         admin/form_lowongan, atau redirect dengan pesan error kalau
     *         id tidak ditemukan.
     */
    public function editLowongan($lowonganId)
    {
        $redirect = $this->cekAdmin();
        if ($redirect) return $redirect;

        $data['lowongan']   = $this->lowonganModel->find($lowonganId);
        $data['perusahaan'] = $this->userModel->where('role', 'perusahaan')->findAll();

        if (!$data['lowongan']) {
            return redirect()->to('/admin/lowongan')->with('error', 'Lowongan tidak ditemukan');
        }

        return view('admin/form_lowongan', $data);
    }

    /**
     * Memproses submit form edit lowongan (dari editLowongan()).
     *
     * @param int $lowonganId ID lowongan yang diedit.
     *
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function updateLowongan($lowonganId)
    {
        $redirect = $this->cekAdmin();
        if ($redirect) return $redirect;

        $rules = [
            'judul'      => 'required|min_length[3]',
            'deskripsi'  => 'required',
            'tipe_kerja' => 'required',
            'status'     => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->to("/admin/lowongan/edit/{$lowonganId}")
                ->with('errors', $this->validator->getErrors())
                ->withInput();
        }

        $this->lowonganModel->update($lowonganId, [
            'judul'       => $this->request->getPost('judul'),
            'deskripsi'   => $this->request->getPost('deskripsi'),
            'kualifikasi' => $this->request->getPost('kualifikasi'),
            'lokasi'      => $this->request->getPost('lokasi'),
            'gaji'        => $this->request->getPost('gaji'),
            'tipe_kerja'  => $this->request->getPost('tipe_kerja'),
            'status'      => $this->request->getPost('status'),
        ]);

        return redirect()->to('/admin/lowongan')
            ->with('success', 'Lowongan berhasil diperbarui!');
    }

    /**
     * Menghapus (soft delete) satu lowongan.
     *
     * @param int $lowonganId ID lowongan yang dihapus.
     *
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function hapusLowongan($lowonganId)
    {
        $redirect = $this->cekAdmin();
        if ($redirect) return $redirect;

        $this->lowonganModel->delete($lowonganId);
        return redirect()->to('/admin/lowongan')
            ->with('success', 'Lowongan berhasil dihapus');
    }

    // ==========================
    // CRUD LAMARAN
    // ==========================

    /**
     * Menampilkan daftar semua lamaran beserta detail lengkap (nama
     * pelamar, posisi, mitra kerja) lewat LamaranModel::getAllDenganDetail().
     *
     * @return string View admin/lamaran.
     */
    public function lamaran()
    {
        $redirect = $this->cekAdmin();
        if ($redirect) return $redirect;

        $data['lamaran'] = $this->lamaranModel->getAllDenganDetail();
        return view('admin/lamaran', $data);
    }

    /**
     * Mengubah status satu lamaran (misal dari 'diproses' menjadi
     * 'diterima'/'ditolak').
     *
     * @param int    $lamaranId ID baris di tabel `lamaran`.
     * @param string $status    Status baru — cuma 'diproses', 'diterima',
     *        atau 'ditolak' yang diterima (lihat enum kolom status di
     *        migration CreateLamaranTable).
     *
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function updateStatusLamaran($lamaranId, $status)
    {
        $redirect = $this->cekAdmin();
        if ($redirect) return $redirect;

        // Hanya boleh diterima atau ditolak
        if (!in_array($status, ['diterima', 'ditolak', 'diproses'])) {
            return redirect()->to('/admin/lamaran')->with('error', 'Status tidak valid');
        }

        $this->lamaranModel->update($lamaranId, ['status' => $status]);
        return redirect()->to('/admin/lamaran')
            ->with('success', 'Status lamaran diperbarui');
    }

    /**
     * Menghapus (soft delete) satu lamaran.
     *
     * @param int $lamaranId ID lamaran yang dihapus.
     *
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function hapusLamaran($lamaranId)
    {
        $redirect = $this->cekAdmin();
        if ($redirect) return $redirect;

        $this->lamaranModel->delete($lamaranId);
        return redirect()->to('/admin/lamaran')
            ->with('success', 'Lamaran berhasil dihapus');
    }
}
