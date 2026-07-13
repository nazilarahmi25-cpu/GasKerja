<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * LowonganModel & NotifikasiModel sama-sama declare useTimestamps=true dan
 * useSoftDeletes=true, tapi tabel live tidak punya kolom created_at/
 * updated_at/deleted_at secara lengkap — pola yang sama seperti perusahaan,
 * lamaran, dan pencari_kerja sebelumnya.
 *
 * lowongan: sudah punya `tanggal_post` (diisi manual di controller),
 * TIDAK dihapus/diganti — cuma ditambah 3 kolom yang hilang.
 * notifikasi: sudah punya `created_at`, tinggal tambah updated_at/deleted_at.
 *
 * Tidak ada FK baru di migration ini, jadi processIndexes() tidak diperlukan.
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
