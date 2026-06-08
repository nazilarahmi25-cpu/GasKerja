<?php

namespace App\Controllers;

use App\Models\LowonganKerjaModel;

class LowonganController extends BaseController
{
    protected $lowonganModel;

    public function __construct()
    {
        $this->lowonganModel = new LowonganKerjaModel();
    }

    // READ - Tampilkan semua lowongan
    public function index()
    {
        $data['lowongan'] = $this->lowonganModel->findAll();
        return view('pages/lowongan/index', $data);
    }

    // CREATE - Form tambah (return JSON untuk modal)
    public function create()
    {
        return $this->response->setJSON(['success' => true]);
    }

    // STORE - Simpan data baru ke database
    public function store()
    {
        $rules = [
            'judul'          => 'required',
            'deskripsi'      => 'required',
            'tipe_pekerjaan' => 'required',
            'status'         => 'required',
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false,
                'errors'  => $this->validator->getErrors()
            ]);
        }

        // Ambil perusahaan_id dari session (jika user adalah perusahaan)
        // Untuk sementara hardcode dulu jika belum ada session
        $this->lowonganModel->save([
            'perusahaan_id'  => $this->request->getPost('perusahaan_id') ?? 1,
            'judul'          => $this->request->getPost('judul'),
            'deskripsi'      => $this->request->getPost('deskripsi'),
            'lokasi'         => $this->request->getPost('lokasi'),
            'gaji_min'       => $this->request->getPost('gaji_min'),
            'gaji_max'       => $this->request->getPost('gaji_max'),
            'tipe_pekerjaan' => $this->request->getPost('tipe_pekerjaan'),
            'status'         => $this->request->getPost('status'),
            'deadline'       => $this->request->getPost('deadline'),
        ]);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Lowongan berhasil ditambahkan'
        ]);
    }

    // EDIT - Ambil data untuk form edit
    public function edit($id)
    {
        $data = $this->lowonganModel->find($id);
        return $this->response->setJSON($data);
    }

    // UPDATE - Simpan perubahan data
    public function update($id)
    {
        $rules = [
            'judul'          => 'required',
            'deskripsi'      => 'required',
            'tipe_pekerjaan' => 'required',
            'status'         => 'required',
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false,
                'errors'  => $this->validator->getErrors()
            ]);
        }

        $this->lowonganModel->update($id, [
            'judul'          => $this->request->getPost('judul'),
            'deskripsi'      => $this->request->getPost('deskripsi'),
            'lokasi'         => $this->request->getPost('lokasi'),
            'gaji_min'       => $this->request->getPost('gaji_min'),
            'gaji_max'       => $this->request->getPost('gaji_max'),
            'tipe_pekerjaan' => $this->request->getPost('tipe_pekerjaan'),
            'status'         => $this->request->getPost('status'),
            'deadline'       => $this->request->getPost('deadline'),
        ]);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Lowongan berhasil diupdate'
        ]);
    }

    // DELETE - Hapus data (soft delete)
    public function delete($id)
    {
        $this->lowonganModel->delete($id);
        return $this->response->setJSON([
            'success' => true,
            'message' => 'Lowongan berhasil dihapus'
        ]);
    }
}