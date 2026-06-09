<?php

namespace App\Models;

use CodeIgniter\Model;

class LamaranModel extends Model
{
    protected $table = 'lamaran';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = true;
    protected $protectFields= true;
    protected $allowedFields = [
        'pencari_id',
        'cv_file',
        'surat_lamaran',
        'lowongan_id',
        'tanggal_lamar',
        'status',
    
    ];

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

}