<?php

namespace App\Models;

use CodeIgniter\Model;

class LowonganModel extends Model
{
    protected $table = 'lowongan_kerja';
    protected $primaryKey = 'lowongan_id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = true;
    protected $protectFields= true;
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
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';
    

}