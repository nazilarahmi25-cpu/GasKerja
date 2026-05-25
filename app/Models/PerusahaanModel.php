<?php

namespace App\Models;

use CodeIgniter\Model;

class PerusahaanModel extends Model
{
    protected $table = 'perusahaan';

    protected $primaryKey = 'perusahaan_id';

    protected $returnType = 'array';

    protected $allowedFields = [
        'perusahaan_id',
        'nama_perusahaan',
        'alamat',
        'deskripsi',
        'logo_path',
        'website'
    ];

    public function getPerusahaanWithUser()
    {
        return $this->select('perusahaan.*, pengguna.nama, pengguna.email')
                    ->join(
                        'pengguna',
                        'pengguna.id = perusahaan.perusahaan_id'
                    )
                    ->findAll();
    }
}