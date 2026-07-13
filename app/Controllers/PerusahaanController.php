<?php

namespace App\Controllers;

use App\Models\LowonganModel;
use App\Models\LamaranModel;

/**
 * Controller untuk halaman & aksi khusus role perusahaan: kelola lowongan
 * milik sendiri (tambah/edit/hapus) dan lihat pelamar. Setiap method
 * publik di sini WAJIB memanggil cekPerusahaan() di baris pertama.
 */
class PerusahaanController extends BaseController
{
    protected $lowonganModel;
    protected $lamaranModel;

    public function __construct()
    {
        $this->lowonganModel = new LowonganModel();
        $this->lamaranModel  = new LamaranModel();
    }

    /**
     * Gerbang otorisasi: memastikan yang mengakses adalah user yang
     * sedang login DAN rolenya 'perusahaan'.
     *
     * @return \CodeIgniter\HTTP\RedirectResponse|null Redirect ke /login
     *         kalau akses ditolak, atau null kalau boleh lanjut.
     */
    private function cekPerusahaan()
    {
        if (!session()->get('logged_in') || session()->get('role') !== 'perusahaan') {
            return redirect()->to('/login')->with('error', 'Akses ditolak');
        }
        return null;
    }

    /**
     * Menampilkan semua lowongan milik perusahaan yang sedang login.
     *
     * PERHATIAN — BUG DITEMUKAN SAAT REVIEW DOKUMENTASI (belum diperbaiki,
     * lihat catatan chat/laporan): method ini memanggil
     * `LowonganModel::getLowonganByPerusahaan()`, tapi method itu TIDAK
     * ADA di LowonganModel — jadi memanggil route ini akan menghasilkan
     * fatal error "Call to undefined method". Kemungkinan besar
     * dimaksudkan memanggil pola serupa
     * `LowonganModel::getAllDenganPerusahaan()` yang sudah ada dan sudah
     * teruji, tapi dengan tambahan filter WHERE perusahaan_id.
     *
     * @return string View perusahaan/kelola_lowongan.
     */
    public function lowongan()
    {
        $redirect = $this->cekPerusahaan();
        if ($redirect) return $redirect;

        $data['lowongan'] = $this->lowonganModel->getLowonganByPerusahaan(session()->get('perusahaan_id'));
        return view('perusahaan/kelola_lowongan', $data);
    }

    /**
     * Menampilkan form kosong untuk menambah lowongan baru.
     *
     * @return string View perusahaan/form_lowongan.
     */
    public function tambah()
    {
        $redirect = $this->cekPerusahaan();
        if ($redirect) return $redirect;

        return view('perusahaan/form_lowongan');
    }

    /**
     * Memproses submit form tambah lowongan. Lowongan baru selalu
     * berstatus 'pending' — harus disetujui admin dulu (lihat
     * AdminController::updateLowongan/simpanLowongan) sebelum tampil di
     * beranda publik (yang cuma menampilkan status 'aktif').
     *
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function simpan()
    {
        $redirect = $this->cekPerusahaan();
        if ($redirect) return $redirect;

        $rules = [
            'judul'       => 'required|min_length[3]',
            'deskripsi'   => 'required',
            'kualifikasi' => 'required',
            'lokasi'      => 'required',
            'tipe_kerja'  => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->to('/perusahaan/tambah-lowongan')
                ->with('errors', $this->validator->getErrors())
                ->withInput();
        }

        $this->lowonganModel->save([
            'perusahaan_id' => session()->get('perusahaan_id'),
            'judul'         => $this->request->getPost('judul'),
            'deskripsi'     => $this->request->getPost('deskripsi'),
            'kualifikasi'   => $this->request->getPost('kualifikasi'),
            'lokasi'        => $this->request->getPost('lokasi'),
            'gaji'          => $this->request->getPost('gaji'),
            'tipe_kerja'    => $this->request->getPost('tipe_kerja'),
            'status'        => 'pending',   // Menunggu persetujuan admin
            'tanggal_post'  => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('/perusahaan/lowongan')
            ->with('success', 'Lowongan berhasil ditambahkan! Menunggu persetujuan admin.');
    }

    /**
     * Menampilkan form edit untuk satu lowongan milik perusahaan sendiri.
     * Kepemilikan dicek manual (bandingkan perusahaan_id lowongan dengan
     * session) karena tidak ada mekanisme otorisasi baris-per-baris di
     * level framework — pengecekan ini WAJIB ada di setiap method yang
     * menerima ID lowongan dari URL, supaya perusahaan A tidak bisa
     * edit/hapus lowongan milik perusahaan B hanya dengan menebak angka id
     * di URL.
     *
     * @param int $lowonganId ID lowongan dari segment URL.
     *
     * @return string|\CodeIgniter\HTTP\RedirectResponse View
     *         perusahaan/form_lowongan, atau redirect kalau lowongan tidak
     *         ditemukan/bukan milik perusahaan ini.
     */
    public function edit($lowonganId)
    {
        $redirect = $this->cekPerusahaan();
        if ($redirect) return $redirect;

        $lowongan = $this->lowonganModel->find($lowonganId);

        // Pastikan lowongan milik perusahaan ini
        if (!$lowongan || $lowongan['perusahaan_id'] != session()->get('perusahaan_id')) {
            return redirect()->to('/perusahaan/lowongan')
                ->with('error', 'Lowongan tidak ditemukan atau bukan milik Anda');
        }

        return view('perusahaan/form_lowongan', ['lowongan' => $lowongan]);
    }

    /**
     * Memproses submit form edit lowongan. Sama seperti edit(), kepemilikan
     * dicek manual sebelum update diizinkan.
     *
     * @param int $lowonganId ID lowongan yang diedit.
     *
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function update($lowonganId)
    {
        $redirect = $this->cekPerusahaan();
        if ($redirect) return $redirect;

        $lowongan = $this->lowonganModel->find($lowonganId);
        if (!$lowongan || $lowongan['perusahaan_id'] != session()->get('perusahaan_id')) {
            return redirect()->to('/perusahaan/lowongan')->with('error', 'Akses ditolak');
        }

        $rules = [
            'judul'      => 'required|min_length[3]',
            'deskripsi'  => 'required',
            'tipe_kerja' => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->to("/perusahaan/edit-lowongan/{$lowonganId}")
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
        ]);

        return redirect()->to('/perusahaan/lowongan')
            ->with('success', 'Lowongan berhasil diperbarui!');
    }

    /**
     * Menghapus (soft delete) satu lowongan milik perusahaan sendiri.
     * Kepemilikan dicek manual sebelum hapus diizinkan (lihat catatan di
     * edit()).
     *
     * @param int $lowonganId ID lowongan yang dihapus.
     *
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function hapus($lowonganId)
    {
        $redirect = $this->cekPerusahaan();
        if ($redirect) return $redirect;

        $lowongan = $this->lowonganModel->find($lowonganId);
        if (!$lowongan || $lowongan['perusahaan_id'] != session()->get('perusahaan_id')) {
            return redirect()->to('/perusahaan/lowongan')->with('error', 'Akses ditolak');
        }

        $this->lowonganModel->delete($lowonganId);
        return redirect()->to('/perusahaan/lowongan')
            ->with('success', 'Lowongan berhasil dihapus');
    }

    /**
     * Menampilkan daftar pelamar untuk satu lowongan milik perusahaan
     * sendiri.
     *
     * PERHATIAN — BUG DITEMUKAN SAAT REVIEW DOKUMENTASI (belum diperbaiki,
     * lihat catatan chat/laporan): method ini memanggil
     * `LamaranModel::getLamaranByPencari()`, tapi method itu TIDAK ADA di
     * LamaranModel — jadi memanggil route ini akan menghasilkan fatal
     * error "Call to undefined method". Nama method ini juga membingungkan
     * ("ByPencari" tapi parameternya lowongan_id) — kemungkinan besar
     * dimaksudkan mengambil semua lamaran UNTUK SATU lowongan (by
     * lowongan_id), bukan "by pencari".
     *
     * @param int $lowonganId ID lowongan yang ingin dilihat pelamarnya.
     *
     * @return string|\CodeIgniter\HTTP\RedirectResponse
     */
    public function pelamar($lowonganId)
    {
        $redirect = $this->cekPerusahaan();
        if ($redirect) return $redirect;

        $lowongan = $this->lowonganModel->find($lowonganId);
        if (!$lowongan || $lowongan['perusahaan_id'] != session()->get('perusahaan_id')) {
            return redirect()->to('/perusahaan/lowongan')->with('error', 'Akses ditolak');
        }

        $data['lowongan'] = $lowongan;
        $data['pelamar']  = $this->lamaranModel->getLamaranByPencari($lowonganId);
        return view('perusahaan/pelamar', $data);
    }
}
