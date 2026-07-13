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

    // Data perusahaan gabungan untuk tabel admin: Nama Usaha, Pemilik,
    // Email, Alamat, Status. Tidak ada kolom status di tabel perusahaan —
    // findAll() sudah otomatis menyaring yang soft-deleted, jadi baris yang
    // muncul di sini pasti "aktif".
    public function getAllDenganUser()
    {
        return $this->select("
                perusahaan.id,
                perusahaan.nama_perusahaan,
                users.nama AS pemilik,
                users.email,
                perusahaan.alamat,
                'aktif' AS status
            ", false)
            ->join('users', 'users.id = perusahaan.user_id')
            ->findAll();
    }
}