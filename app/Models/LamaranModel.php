<?php

namespace App\Models;

use CodeIgniter\Model;

class LamaranModel extends Model
{
    protected $table = 'lamaran';
    protected $primaryKey = 'lamaran_id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = true;
    protected $protectFields= true;
    protected $allowedFields = [
        'pencari_kerja_id',
        'lowongan_id',
        'tanggal_lamaran',
        'status',
        'catatan'
    ];

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

}