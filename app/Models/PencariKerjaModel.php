<?php

namespace App\Models;

use CodeIgniter\Model;

class PencariKerjaModel extends Model
{
    protected $table = 'pencari_kerja';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = true;
    protected $protectFields = true;

    protected $allowedFields = [
        'user_id',
        'alamat',
        'no_hp',
        'pendidikan',
        'skill',
        'cv'
    ];

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';
}