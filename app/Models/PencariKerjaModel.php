<?php

namespace App\Models;

use CodeIgniter\Model;

class PencariKerjaModel extends Model
{
    protected $table = 'pencari_kerja';
    protected $primaryKey = 'pencari_kerja_id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = true;
    protected $protectFields= true;
    protected $allowedFields = [
        'pencari_kerja_id',
        'no_hp',
        'alamat',
        'cv_path',
        'portofolio_path',
        'bio'
    ];

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

}