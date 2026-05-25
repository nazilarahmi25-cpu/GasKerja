<?php

namespace App\Models;

use CodeIgniter\Model;

class LowonganKerjaModel extends Model
{
    protected $table = 'lowongan_kerja';

    protected $primaryKey = 'lowongan_id';

    protected $returnType = 'array';

    protected $useAutoIncrement = true;

    protected $allowedFields = [
        'perusahaan_id',
        'judul',
        'deskripsi',
        'lokasi',
        'gaji_min',
        'gaji_max',
        'tipe_pekerjaan',
        'status',
        'deadline'
    ];

    protected $useTimestamps = true;

    protected $createdField = 'created_at';

    protected $updatedField = 'updated_at';

    public function getLowonganPerusahaan()
    {
        return $this->select(
                        'lowongan_kerja.*, perusahaan.nama_perusahaan'
                    )
                    ->join(
                        'perusahaan',
                        'perusahaan.perusahaan_id = lowongan_kerja.perusahaan_id'
                    )
                    ->findAll();
    }
}