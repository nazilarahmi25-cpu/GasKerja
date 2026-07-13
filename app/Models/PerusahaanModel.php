<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * Model untuk tabel `perusahaan` — profil akun perusahaan/UMKM.
 *
 * Tabel ini SENGAJA tidak menyimpan email/password/role sendiri (kolom itu
 * dihapus lewat migration AlignPerusahaanTable). Kredensial login akun
 * perusahaan tetap satu-satunya sumber kebenaran di tabel `users`; di sini
 * cuma menyimpan data profil bisnis (nama_perusahaan, alamat) dan `user_id`
 * yang menghubungkan ke baris users terkait.
 *
 * PENTING: `perusahaan.id` (primary key tabel ini) BUKAN sama dengan
 * `users.id`. `lowongan.perusahaan_id` merujuk ke `perusahaan.id`, sehingga
 * kalau butuh perusahaan_id dari sebuah user yang sedang login, harus query
 * tabel ini dulu lewat `user_id` (lihat Home::processLogin()) — bukan
 * langsung pakai session('user_id').
 */
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

    /**
     * Mengambil semua perusahaan beserta data pemilik akunnya (JOIN ke
     * `users` lewat user_id), untuk tabel "Kelola Mitra Kerja" di dashboard
     * admin.
     *
     * Kolom yang dihasilkan: id, nama_perusahaan, pemilik (nama di users),
     * email, alamat, status (selalu string 'aktif').
     *
     * Kenapa status di-hardcode 'aktif': tabel `perusahaan` tidak punya
     * kolom status sungguhan. Karena Model ini punya $useSoftDeletes=true,
     * findAll()/JOIN di bawah otomatis menyaring baris yang sudah
     * soft-delete (WHERE deleted_at IS NULL) — jadi baris yang benar-benar
     * muncul di hasil query ini pasti belum dihapus, sehingga aman
     * dianggap "aktif" tanpa perlu kolom status terpisah.
     *
     * @return array<int, array<string, mixed>> Daftar perusahaan, tiap
     *         elemen array asosiatif dengan key: id, nama_perusahaan,
     *         pemilik, email, alamat, status.
     */
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