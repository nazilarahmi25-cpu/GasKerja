<?php
namespace App\Controllers;

use App\Models\UserModel;
use App\Models\PerusahaanModel;
use App\Models\LowonganModel;
use App\Models\LamaranModel;

class Admin extends BaseController
{
    protected $userModel;
    protected $perusahaanModel;
    protected $lowonganModel;
    protected $lamaranModel;

    public function __construct()
    {
        $this->userModel       = new UserModel();
        $this->perusahaanModel = new PerusahaanModel();
        $this->lowonganModel   = new LowonganModel();
        $this->lamaranModel    = new LamaranModel();
    }

    // =====================
    // CEK AKSES ADMIN
    // =====================

    private function cekAdmin()
    {
        if (!session()->get('logged_in') || session()->get('role') !== 'admin') {
            return redirect()->to('/login');
        }
        return null;
    }

    // =====================
    // DASHBOARD
    // =====================

    public function dashboard()
    {
        $redirect = $this->cekAdmin();
        if ($redirect) return $redirect;

        $data = [
            'totalUser'       => $this->userModel->where('role', 'pencari_kerja')->countAllResults(),
            'totalPerusahaan' => $this->perusahaanModel->countAllResults(),
            'totalLowongan'   => $this->lowonganModel->countAllResults(),
            'totalLamaran'    => $this->lamaranModel->countAllResults(),
            'pending'         => $this->lamaranModel->where('status', 'diproses')->countAllResults(),
            'diterima'        => $this->lamaranModel->where('status', 'diterima')->countAllResults(),
            'ditolak'         => $this->lamaranModel->where('status', 'ditolak')->countAllResults(),
        ];

        return view('dashboard_admin', $data);
    }

    // =====================
    // KELOLA PENGGUNA
    // =====================

    public function kelolaUser()
    {
        $redirect = $this->cekAdmin();
        if ($redirect) return $redirect;

        $data['users'] = $this->userModel
            ->where('role', 'pencari_kerja')
            ->findAll();

        return view('admin/kelola_user', $data);
    }

    public function hapusUser($id)
    {
        $redirect = $this->cekAdmin();
        if ($redirect) return $redirect;

        $this->userModel->delete($id);
        return redirect()->to('/admin/users')
            ->with('success', 'Pengguna berhasil dihapus');
    }

    // =====================
    // KELOLA PERUSAHAAN
    // =====================

    public function kelolaPerusahaan()
    {
        $redirect = $this->cekAdmin();
        if ($redirect) return $redirect;

        $data['perusahaan'] = $this->perusahaanModel->findAll();
        return view('admin/kelola_perusahaan', $data);
    }

    public function hapusPerusahaan($id)
    {
        $redirect = $this->cekAdmin();
        if ($redirect) return $redirect;

        $this->perusahaanModel->delete($id);
        return redirect()->to('/admin/perusahaan')
            ->with('success', 'Perusahaan berhasil dihapus');
    }

    // =====================
    // KELOLA LOWONGAN
    // =====================

    public function kelolaLowongan()
    {
        $redirect = $this->cekAdmin();
        if ($redirect) return $redirect;

        $data['lowongan'] = $this->lowonganModel->findAll();
        return view('admin/kelola_lowongan', $data);
    }

    public function hapusLowongan($id)
    {
        $redirect = $this->cekAdmin();
        if ($redirect) return $redirect;

        $this->lowonganModel->delete($id);
        return redirect()->to('/admin/lowongan')
            ->with('success', 'Lowongan berhasil dihapus');
    }

    // =====================
    // KELOLA LAMARAN
    // =====================

    public function kelolaLamaran()
    {
        $redirect = $this->cekAdmin();
        if ($redirect) return $redirect;

        $data['lamaran'] = $this->lamaranModel->findAll();
        return view('admin/kelola_lamaran', $data);
    }

    public function updateStatusLamaran($id)
    {
        $redirect = $this->cekAdmin();
        if ($redirect) return $redirect;

        $status = $this->request->getPost('status');

        // Validasi status sesuai ENUM di migration
        $validStatus = ['diproses', 'diterima', 'ditolak'];
        if (!in_array($status, $validStatus)) {
            return redirect()->to('/admin/lamaran')
                ->with('error', 'Status tidak valid');
        }

        $this->lamaranModel->update($id, ['status' => $status]);
        return redirect()->to('/admin/lamaran')
            ->with('success', 'Status lamaran diperbarui');
    }
}