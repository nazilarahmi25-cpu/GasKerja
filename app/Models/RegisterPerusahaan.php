<?php

namespace App\Models;

use CodeIgniter\Model;

class RegisterPerusahaanModel extends Model
{
    protected $table      = 'RegisterPerusahaan';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = true;
    protected $protectFields = true;

    protected $allowedFields = [
        'nama_umkm',
        'email',
        'bidang_usaha',
        'alamat',
        'telepon',
        'password'
    ];

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';
}