<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * KENAPA migration ini dibuat:
 * Sama seperti AddTimestampsToLamaranAndPencariKerja — LowonganModel dan
 * NotifikasiModel declare `$useTimestamps = true` dan `$useSoftDeletes =
 * true`, yang mewajibkan kolom created_at/updated_at/deleted_at ada di
 * tabel. Tabel live `lowongan` dan `notifikasi` tidak punya kolom itu
 * lengkap, jadi setiap simpan/hapus lowongan lewat Model akan gagal
 * "Unknown column" — ini sudah TERBUKTI nyata: sebelum migration ini
 * dijalankan, PerusahaanController::simpan() (tambah lowongan) betulan
 * gagal HTTP 500 saat dicoba.
 *
 * lowongan: sudah punya `tanggal_post` (diisi manual di controller,
 * dipakai sebagai "kapan lowongan diposting" versi tampilan) — kolom itu
 * TIDAK dihapus/diganti, cuma ditambah 3 kolom audit yang hilang di
 * sampingnya.
 * notifikasi: sudah punya `created_at`, tinggal tambah updated_at/deleted_at.
 *
 * Tidak ada foreign key baru di migration ini, jadi processIndexes() tidak
 * diperlukan.
 */
class AddTimestampsToLowonganAndNotifikasi extends Migration
{
    public function up()
    {
        $this->forge->addColumn('lowongan', [
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
            'deleted_at' => ['type' => 'DATETIME', 'null' => true],
        ]);

        $this->forge->addColumn('notifikasi', [
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
            'deleted_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('lowongan', ['created_at', 'updated_at', 'deleted_at']);
        $this->forge->dropColumn('notifikasi', ['updated_at', 'deleted_at']);
    }
}
