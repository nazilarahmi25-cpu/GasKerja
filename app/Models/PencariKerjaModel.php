<?php

namespace App\Models;

use CodeIgniter\Model;

class PencariKerjaModel extends Model
{
    protected $table = 'pencari_kerja';

    protected $primaryKey = 'pencari_kerja_id';

    protected $returnType = 'array';

    protected $allowedFields = [
        'pencari_kerja_id',
        'no_hp',
        'alamat',
        'cv_path',
        'portofolio_path',
        'bio'
    ];

    public function getPencariWithUser()
    {
        return $this->select(
                        'pencari_kerja.*, pengguna.nama, pengguna.email'
                    )
                    ->join(
                        'pengguna',
                        'pengguna.id = pencari_kerja.pencari_kerja_id'
                    )
                    ->findAll();
    }
}