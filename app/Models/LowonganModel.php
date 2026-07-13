<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * Model untuk tabel `lowongan` — data lowongan pekerjaan yang diposting
 * oleh perusahaan.
 */
class LowonganModel extends Model
{
    protected $table = 'lowongan';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = true;
    // $protectFields=true berarti field yang TIDAK ada di $allowedFields di
    // bawah akan DIBUANG DIAM-DIAM (tanpa error) setiap kali save()/update()
    // dipanggil — ini fitur keamanan CodeIgniter (mass assignment
    // protection). Konsekuensinya: kalau sebuah kolom sengaja diisi dari
    // controller tapi lupa didaftarkan di sini, nilainya tidak akan pernah
    // tersimpan, TANPA pesan error apa pun (silent bug). Ini pernah
    // benar-benar terjadi pada kolom 'tanggal_post' di bawah — controller
    // sudah mengirim nilainya, tapi karena belum terdaftar di
    // $allowedFields, nilainya selalu dibuang dan MySQL diam-diam memakai
    // DEFAULT CURRENT_TIMESTAMP() bawaan kolom (yang ikut timezone SERVER,
    // bukan timezone aplikasi) — makanya jamnya kelihatan beda 8 jam dari
    // 'created_at'. Jadi: setiap kali menambah kolom baru yang diisi lewat
    // controller, WAJIB ditambahkan juga ke $allowedFields di sini.
    protected $allowedFields = [
        'perusahaan_id',
        'judul',
        'deskripsi',
        'kualifikasi',
        'lokasi',
        'gaji',
        'tipe_kerja',
        'status',
        'tanggal_post',
    ];

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    /**
     * Mengambil semua lowongan beserta nama perusahaan terkait (JOIN ke
     * `perusahaan`), diurutkan dari tanggal posting terbaru. Dipakai untuk
     * tabel "Daftar Lowongan" di dashboard admin dan section "Lowongan
     * Terbaru" di beranda publik.
     *
     * @param bool $onlyAktif Kalau true, cuma lowongan berstatus 'aktif'
     *        yang diikutkan. Dipakai beranda publik supaya lowongan yang
     *        masih 'pending' (belum disetujui admin) atau 'nonaktif' tidak
     *        bocor ke pengunjung biasa. Admin memanggil method ini TANPA
     *        argumen (default false) supaya tetap bisa melihat & mengelola
     *        semua status.
     *
     * @return array<int, array<string, mixed>> Daftar lowongan, tiap
     *         elemen array asosiatif dengan key: id, posisi (judul),
     *         jam_kerja (tipe_kerja), lokasi, mitra_kerja (nama
     *         perusahaan), status.
     */
    public function getAllDenganPerusahaan($onlyAktif = false)
    {
        $builder = $this->select('
                lowongan.id,
                lowongan.judul AS posisi,
                lowongan.tipe_kerja AS jam_kerja,
                lowongan.lokasi,
                perusahaan.nama_perusahaan AS mitra_kerja,
                lowongan.status
            ')
            ->join('perusahaan', 'perusahaan.id = lowongan.perusahaan_id');

        if ($onlyAktif) {
            $builder->where('lowongan.status', 'aktif');
        }

        return $builder
            ->orderBy('lowongan.tanggal_post', 'DESC')
            ->findAll();
    }

    /**
     * Mengambil lowongan diurutkan berdasarkan JUMLAH PELAMAR terbanyak
     * (bukan tanggal), untuk section "Lowongan Populer" di beranda.
     *
     * Kenapa pakai LEFT JOIN ke `lamaran` (bukan JOIN biasa): kalau pakai
     * JOIN biasa (INNER JOIN), lowongan yang belum punya pelamar sama
     * sekali (0 lamaran) akan HILANG dari hasil query, karena INNER JOIN
     * cuma menyertakan baris yang punya pasangan di kedua tabel. LEFT JOIN
     * membuat lowongan tanpa pelamar tetap ikut, dengan jumlah_pelamar
     * dihitung 0 lewat COUNT().
     *
     * Kenapa ada 2 ORDER BY (fallback): kalau dua lowongan sama-sama
     * punya jumlah pelamar yang identik (termasuk kasus sama-sama 0 saat
     * baru mulai), ORDER BY jumlah_pelamar saja tidak cukup menentukan
     * urutan yang konsisten — SQL boleh mengembalikan urutan apa saja di
     * antara baris yang "seri". Ditambahkannya `ORDER BY tanggal_post DESC`
     * sebagai kriteria kedua memastikan urutan selalu sama di setiap
     * request (lowongan yang lebih baru menang kalau jumlah pelamarnya
     * sama).
     *
     * @param bool $onlyAktif Sama seperti di getAllDenganPerusahaan() —
     *        true untuk beranda publik (cuma status 'aktif').
     *
     * @return array<int, array<string, mixed>> Sama seperti
     *         getAllDenganPerusahaan(), ditambah key jumlah_pelamar.
     */
    public function getPopulerDenganPerusahaan($onlyAktif = false)
    {
        $builder = $this->select('
                lowongan.id,
                lowongan.judul AS posisi,
                lowongan.tipe_kerja AS jam_kerja,
                lowongan.lokasi,
                perusahaan.nama_perusahaan AS mitra_kerja,
                lowongan.status,
                COUNT(lamaran.id) AS jumlah_pelamar
            ')
            ->join('perusahaan', 'perusahaan.id = lowongan.perusahaan_id')
            ->join('lamaran', 'lamaran.lowongan_id = lowongan.id AND lamaran.deleted_at IS NULL', 'left');

        if ($onlyAktif) {
            $builder->where('lowongan.status', 'aktif');
        }

        return $builder
            ->groupBy('lowongan.id')
            ->orderBy('jumlah_pelamar', 'DESC')
            ->orderBy('lowongan.tanggal_post', 'DESC')
            ->findAll();
    }

    /**
     * Mengambil satu lowongan lengkap (semua kolom yang dibutuhkan halaman
     * detail) beserta nama perusahaan, untuk halaman detail lowongan
     * publik (route `lowongan/(:num)` → Home::detailLowongan()).
     *
     * Kenapa cukup where()+first() tanpa filter status/deleted_at manual:
     * Model ini punya $useSoftDeletes=true, jadi CodeIgniter OTOMATIS
     * menambahkan `WHERE deleted_at IS NULL` ke query — lowongan yang
     * sudah dihapus (soft delete) otomatis dianggap "tidak ditemukan"
     * (null) tanpa perlu ditulis manual di sini. Filter status='aktif'
     * (supaya lowongan pending/nonaktif tidak bocor ke publik) sengaja
     * TIDAK dilakukan di Model ini, tapi di controller
     * (Home::detailLowongan()) — supaya Model ini tetap netral dan bisa
     * dipakai ulang oleh fitur lain yang butuh melihat semua status
     * (misal kalau nanti admin butuh preview lowongan pending).
     *
     * @param int $lowonganId ID lowongan (primary key tabel `lowongan`).
     *
     * @return array<string, mixed>|null Data lowongan (id, judul,
     *         deskripsi, kualifikasi, lokasi, gaji, tipe_kerja, status,
     *         tanggal_post, mitra_kerja), atau null kalau id tidak
     *         ditemukan/sudah dihapus.
     */
    public function findDenganPerusahaan($lowonganId)
    {
        return $this->select('
                lowongan.id,
                lowongan.judul,
                lowongan.deskripsi,
                lowongan.kualifikasi,
                lowongan.lokasi,
                lowongan.gaji,
                lowongan.tipe_kerja,
                lowongan.status,
                lowongan.tanggal_post,
                perusahaan.nama_perusahaan AS mitra_kerja
            ')
            ->join('perusahaan', 'perusahaan.id = lowongan.perusahaan_id')
            ->where('lowongan.id', $lowonganId)
            ->first();
    }
}