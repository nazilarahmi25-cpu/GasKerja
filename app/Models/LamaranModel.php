<?php

namespace App\Models;

use CodeIgniter\Model;

class LamaranModel extends Model
{
    protected $table = 'lamaran';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = true;
    protected $protectFields= true;
    protected $allowedFields = [
        'pencari_id',
        'cv_file',
        'surat_lamaran',
        'lowongan_id',
        'tanggal_lamar',
        'status',
    
    ];

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    // Data lamaran gabungan untuk tabel "Data Pelamar" di dashboard admin:
    // Nama (pelamar), Posisi (lowongan), Mitra kerja (perusahaan), Tanggal, Status
    public function getAllDenganDetail()
    {
        return $this->select('
                lamaran.id,
                users.nama AS nama_pelamar,
                lowongan.judul AS posisi,
                perusahaan.nama_perusahaan AS mitra_kerja,
                lamaran.tanggal_lamar,
                lamaran.status
            ')
            ->join('pencari_kerja', 'pencari_kerja.id = lamaran.pencari_id')
            ->join('users', 'users.id = pencari_kerja.user_id')
            ->join('lowongan', 'lowongan.id = lamaran.lowongan_id')
            ->join('perusahaan', 'perusahaan.id = lowongan.perusahaan_id')
            ->orderBy('lamaran.tanggal_lamar', 'DESC')
            ->findAll();
    }
}