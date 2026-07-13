<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * Model untuk tabel `users` — satu-satunya sumber kebenaran untuk akun
 * login (email, password, role) di seluruh aplikasi, untuk ketiga role:
 * admin, perusahaan, dan pencari_kerja.
 *
 * PENTING: kolom `role` menentukan apakah user ini juga punya baris profil
 * tambahan di tabel lain:
 *   - role='perusahaan'    → harus punya baris terkait di `perusahaan`
 *                             (lihat PerusahaanModel), dibuat otomatis di
 *                             Home::processRegisterPerusahaan().
 *   - role='pencari_kerja'  → harus punya baris terkait di `pencari_kerja`,
 *                             dibuat otomatis di Home::processRegister().
 *   - role='admin'          → tidak butuh tabel profil tambahan.
 * Nama file ini `UserModel.php` (huruf besar) sengaja disamakan persis
 * dengan nama class `UserModel` di bawah — PHP mensyaratkan ini (PSR-4
 * autoloading) supaya class bisa ditemukan otomatis tanpa perlu di-import
 * manual satu-satu.
 */
class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = true;
    protected $protectFields= true;
    protected $allowedFields = [
        'nama',
        'email',
        'password',
        'role',
        'foto'
    ];

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';
    
}