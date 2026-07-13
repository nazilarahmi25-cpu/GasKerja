<?php

namespace App\Controllers;

use App\Models\LowonganModel;
use App\Models\LamaranModel;

class PerusahaanController extends BaseController
{
    protected $lowonganModel;
    protected $lamaranModel;

    public function __construct()
    {
        $this->lowonganModel = new LowonganModel();
        $this->lamaranModel  = new LamaranModel();
    }

    private function cekPerusahaan()
    {
        if (!session()->get('logged_in') || session()->get('role') !== 'perusahaan') {
            return redirect()->to('/login')->with('error', 'Akses ditolak');
        }
        return null;
    }

    // Tampil semua lowongan milik perusahaan ini
    public function lowongan()
    {
        $redirect = $this->cekPerusahaan();
        if ($redirect) return $redirect;

        $data['lowongan'] = $this->lowonganModel->getLowonganByPerusahaan(session()->get('perusahaan_id'));
        return view('perusahaan/kelola_lowongan', $data);
    }

    // Form tambah lowongan
    public function tambah()
    {
        $redirect = $this->cekPerusahaan();
        if ($redirect) return $redirect;

        return view('perusahaan/form_lowongan');
    }

    // Simpan lowongan baru
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

    // Form edit lowongan
    public function edit($id)
    {
        $redirect = $this->cekPerusahaan();
        if ($redirect) return $redirect;

        $lowongan = $this->lowonganModel->find($id);

        // Pastikan lowongan milik perusahaan ini
        if (!$lowongan || $lowongan['perusahaan_id'] != session()->get('perusahaan_id')) {
            return redirect()->to('/perusahaan/lowongan')
                ->with('error', 'Lowongan tidak ditemukan atau bukan milik Anda');
        }

        return view('perusahaan/form_lowongan', ['lowongan' => $lowongan]);
    }

    // Update lowongan
    public function update($id)
    {
        $redirect = $this->cekPerusahaan();
        if ($redirect) return $redirect;

        $lowongan = $this->lowonganModel->find($id);
        if (!$lowongan || $lowongan['perusahaan_id'] != session()->get('perusahaan_id')) {
            return redirect()->to('/perusahaan/lowongan')->with('error', 'Akses ditolak');
        }

        $rules = [
            'judul'      => 'required|min_length[3]',
            'deskripsi'  => 'required',
            'tipe_kerja' => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->to("/perusahaan/edit-lowongan/{$id}")
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
        ]);

        return redirect()->to('/perusahaan/lowongan')
            ->with('success', 'Lowongan berhasil diperbarui!');
    }

    // Hapus lowongan
    public function hapus($id)
    {
        $redirect = $this->cekPerusahaan();
        if ($redirect) return $redirect;

        $lowongan = $this->lowonganModel->find($id);
        if (!$lowongan || $lowongan['perusahaan_id'] != session()->get('perusahaan_id')) {
            return redirect()->to('/perusahaan/lowongan')->with('error', 'Akses ditolak');
        }

        $this->lowonganModel->delete($id);
        return redirect()->to('/perusahaan/lowongan')
            ->with('success', 'Lowongan berhasil dihapus');
    }

    // Lihat pelamar untuk satu lowongan
    public function pelamar($lowongan_id)
    {
        $redirect = $this->cekPerusahaan();
        if ($redirect) return $redirect;

        $lowongan = $this->lowonganModel->find($lowongan_id);
        if (!$lowongan || $lowongan['perusahaan_id'] != session()->get('perusahaan_id')) {
            return redirect()->to('/perusahaan/lowongan')->with('error', 'Akses ditolak');
        }

        $data['lowongan'] = $lowongan;
        $data['pelamar']  = $this->lamaranModel->getLamaranByPencari($lowongan_id);
        return view('perusahaan/pelamar', $data);
    }
}