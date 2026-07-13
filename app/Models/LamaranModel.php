<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * Model untuk tabel `lamaran` — satu baris = satu lamaran kerja yang
 * dikirim pencari kerja ke sebuah lowongan.
 *
 * PENTING: `pencari_id` merujuk ke `pencari_kerja.id`, BUKAN `users.id`.
 * Ini pola yang sama seperti `perusahaan` (lihat PerusahaanModel) — pencari
 * kerja punya baris profil sendiri di tabel `pencari_kerja` (dibuat saat
 * registrasi lewat Home::processRegister()), dan `lamaran.pencari_id`
 * mengacu ke ID profil itu, bukan langsung ke akun users. Karena itu,
 * untuk tahu pencari_id milik user yang sedang login, harus lihat
 * session('pencari_id') (di-set saat login, lihat Home::processLogin()),
 * bukan session('user_id').
 */
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

    /**
     * Mengambil semua lamaran beserta detail lengkap (nama pelamar, posisi
     * yang dilamar, nama perusahaan pemberi kerja), untuk tabel "Data
     * Pelamar" di dashboard admin.
     *
     * Kenapa JOIN 4 tabel: data yang dibutuhkan tersebar di 4 tabel
     * berbeda dan harus dirangkai lewat 2 tabel penghubung —
     *   lamaran → pencari_kerja → users        (dapat nama pelamar asli)
     *   lamaran → lowongan → perusahaan         (dapat judul + nama perusahaan)
     * Tanpa JOIN ini, tabel `lamaran` sendiri cuma punya angka-angka id
     * (pencari_id, lowongan_id) yang tidak berarti apa-apa buat ditampilkan
     * ke admin.
     *
     * @return array<int, array<string, mixed>> Daftar lamaran diurutkan
     *         dari yang terbaru, tiap elemen array asosiatif dengan key:
     *         id, nama_pelamar, posisi, mitra_kerja, tanggal_lamar, status.
     */
    public function getAllDenganDetail()
    {
        return $this->select('
                lamaran.id,
                users.nama AS nama_pelamar,
                lowongan.judul AS posisi,
                perusahaan.nama_perusahaan AS mitra_kerja,
                lamaran.tanggal_lamar,
                lamaran.status
            ')
            ->join('pencari_kerja', 'pencari_kerja.id = lamaran.pencari_id')
            ->join('users', 'users.id = pencari_kerja.user_id')
            ->join('lowongan', 'lowongan.id = lamaran.lowongan_id')
            ->join('perusahaan', 'perusahaan.id = lowongan.perusahaan_id')
            ->orderBy('lamaran.tanggal_lamar', 'DESC')
            ->findAll();
    }
}