<?php

namespace App\Models;

use CodeIgniter\Model;

class LowonganModel extends Model
{
    protected $table = 'lowongan';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = true;
    protected $protectFields= true;
    protected $allowedFields = [
        'perusahaan_id',
        'judul',
        'deskripsi',
        'kualifikasi',
        'lokasi',
        'gaji',
        'tipe_kerja',
        'status',
        'tanggal_post',
    ];

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    // Data lowongan gabungan untuk tabel admin: Posisi Pekerjaan, Jam Kerja,
    // Lokasi, Mitra Kerja (pola sama seperti LamaranModel::getAllDenganDetail)
    public function getAllDenganPerusahaan()
    {
        return $this->select('
                lowongan.id,
                lowongan.judul AS posisi,
                lowongan.tipe_kerja AS jam_kerja,
                lowongan.lokasi,
                perusahaan.nama_perusahaan AS mitra_kerja,
                lowongan.status
            ')
            ->join('perusahaan', 'perusahaan.id = lowongan.perusahaan_id')
            ->orderBy('lowongan.tanggal_post', 'DESC')
            ->findAll();
    }
}