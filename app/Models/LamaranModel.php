<?php

namespace App\Models;

use CodeIgniter\Model;

class LamaranModel extends Model
{
    protected $table = 'lamaran';

    protected $primaryKey = 'lamaran_id';

    protected $returnType = 'array';

    protected $useAutoIncrement = true;

    protected $allowedFields = [
        'pencari_kerja_id',
        'lowongan_id',
        'tanggal_lamaran',
        'status',
        'catatan'
    ];

    public function getLamaranLengkap()
    {
        return $this->select('
                lamaran.*,
                pengguna.nama,
                lowongan_kerja.judul
            ')
            ->join(
                'pencari_kerja',
                'pencari_kerja.pencari_kerja_id = lamaran.pencari_kerja_id'
            )
            ->join(
                'pengguna',
                'pengguna.id = pencari_kerja.pencari_kerja_id'
            )
            ->join(
                'lowongan_kerja',
                'lowongan_kerja.lowongan_id = lamaran.lowongan_id'
            )
            ->findAll();
    }
}