<?php

namespace App\Models;

use CodeIgniter\Model;

class NotifikasiModel extends Model
{
    protected $table = 'notifikasi';

    protected $primaryKey = 'notifikasi_id';

    protected $returnType = 'array';

    protected $useAutoIncrement = true;

    protected $allowedFields = [
        'user_id',
        'judul',
        'pesan',
        'is_read',
        'referensi_tipe',
        'referensi_id'
    ];

    protected $useTimestamps = true;

    protected $createdField = 'created_at';

    public function getNotifikasiUser($userId)
    {
        return $this->where('user_id', $userId)
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }
}