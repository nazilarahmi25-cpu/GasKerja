<?php

namespace App\Models;

use CodeIgniter\Model;

class PerusahaanModel extends Model
{
    protected $table = 'perusahaan';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = true;
    protected $protectFields = true;

    protected $allowedFields = [
        'user_id',
        'nama_perusahaan',
        'alamat',
    ];

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';

    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';
}