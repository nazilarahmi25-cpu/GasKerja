<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\LowonganModel;
use App\Models\LamaranModel;
use App\Models\PerusahaanModel;

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

    private function cekAdmin()
    {
        if (!session()->get('logged_in') || session()->get('role') !== 'admin') {
            return redirect()->to('/login')->with('error', 'Akses ditolak');
        }
        return null;
    }

    // ==========================
    // CRUD PENGGUNA (users)
    // ==========================

    public function pengguna()
    {
        $redirect = $this->cekAdmin();
        if ($redirect) return $redirect;

        $data['users'] = $this->userModel
            ->where('role', 'pencari_kerja')
            ->findAll();

        return view('admin/pengguna', $data);
    }

    public function hapusPengguna($id)
    {
        $redirect = $this->cekAdmin();
        if ($redirect) return $redirect;

        $this->userModel->delete($id);
        return redirect()->to('/admin/pengguna')
            ->with('success', 'Pengguna berhasil dihapus');
    }

    // ==========================
    // CRUD PERUSAHAAN
    // ==========================

    public function perusahaan()
    {
        $redirect = $this->cekAdmin();
        if ($redirect) return $redirect;

        // Ambil dari tabel perusahaan (bukan users) supaya dapat data asli
        // perusahaan (nama_perusahaan, alamat), JOIN ke users untuk email/pemilik
        $data['perusahaan'] = $this->perusahaanModel->getAllDenganUser();

        return view('admin/perusahaan', $data);
    }

    public function hapusPerusahaan($id)
    {
        $redirect = $this->cekAdmin();
        if ($redirect) return $redirect;

        // $id di sini adalah perusahaan.id. Hapus baris perusahaan DAN
        // akun users terkait (soft delete keduanya) supaya akun tidak bisa
        // login lagi — soft delete tidak memicu FK CASCADE, jadi harus
        // dihapus manual dari kedua tabel.
        $perusahaan = $this->perusahaanModel->find($id);
        if ($perusahaan) {
            $this->userModel->delete($perusahaan['user_id']);
            $this->perusahaanModel->delete($id);
        }

        return redirect()->to('/admin/perusahaan')
            ->with('success', 'Perusahaan berhasil dihapus');
    }

    // ==========================
    // CRUD LOWONGAN
    // ==========================

    public function lowongan()
    {
        $redirect = $this->cekAdmin();
        if ($redirect) return $redirect;

        $data['lowongan'] = $this->lowonganModel->getAllDenganPerusahaan();
        return view('admin/lowongan', $data);
    }

    public function tambahLowongan()
    {
        $redirect = $this->cekAdmin();
        if ($redirect) return $redirect;

        // Ambil daftar perusahaan untuk dropdown
        $data['perusahaan'] = $this->userModel->where('role', 'perusahaan')->findAll();
        return view('admin/form_lowongan', $data);
    }

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

    public function editLowongan($id)
    {
        $redirect = $this->cekAdmin();
        if ($redirect) return $redirect;

        $data['lowongan']   = $this->lowonganModel->find($id);
        $data['perusahaan'] = $this->userModel->where('role', 'perusahaan')->findAll();

        if (!$data['lowongan']) {
            return redirect()->to('/admin/lowongan')->with('error', 'Lowongan tidak ditemukan');
        }

        return view('admin/form_lowongan', $data);
    }

    public function updateLowongan($id)
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
            return redirect()->to("/admin/lowongan/edit/{$id}")
                ->with('errors', $this->validator->getErrors())
                ->withInput();
        }

        $this->lowonganModel->update($id, [
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

    public function hapusLowongan($id)
    {
        $redirect = $this->cekAdmin();
        if ($redirect) return $redirect;

        $this->lowonganModel->delete($id);
        return redirect()->to('/admin/lowongan')
            ->with('success', 'Lowongan berhasil dihapus');
    }

    // ==========================
    // CRUD LAMARAN
    // ==========================

    public function lamaran()
    {
        $redirect = $this->cekAdmin();
        if ($redirect) return $redirect;

        $data['lamaran'] = $this->lamaranModel->getAllDenganDetail();
        return view('admin/lamaran', $data);
    }

    public function updateStatusLamaran($id, $status)
    {
        $redirect = $this->cekAdmin();
        if ($redirect) return $redirect;

        // Hanya boleh diterima atau ditolak
        if (!in_array($status, ['diterima', 'ditolak', 'diproses'])) {
            return redirect()->to('/admin/lamaran')->with('error', 'Status tidak valid');
        }

        $this->lamaranModel->update($id, ['status' => $status]);
        return redirect()->to('/admin/lamaran')
            ->with('success', 'Status lamaran diperbarui');
    }

    public function hapusLamaran($id)
    {
        $redirect = $this->cekAdmin();
        if ($redirect) return $redirect;

        $this->lamaranModel->delete($id);
        return redirect()->to('/admin/lamaran')
            ->with('success', 'Lamaran berhasil dihapus');
    }
}
