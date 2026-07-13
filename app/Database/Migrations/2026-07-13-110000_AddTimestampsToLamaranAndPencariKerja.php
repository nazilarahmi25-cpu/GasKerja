<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * KENAPA migration ini dibuat:
 * Setiap Model CodeIgniter yang set `$useTimestamps = true` dan
 * `$useSoftDeletes = true` (lihat LamaranModel & PencariKerjaModel)
 * OTOMATIS mencoba membaca/menulis kolom created_at, updated_at, dan
 * deleted_at setiap kali save()/update()/delete() dipanggil — ini bukan
 * fitur opsional, jadi kolomnya WAJIB ada di tabel.
 *
 * Ternyata tabel live `lamaran` dan `pencari_kerja` tidak punya kolom-kolom
 * itu sama sekali (kemungkinan dibuat manual tanpa lewat migration resmi).
 * Akibatnya setiap kali fitur apply lamaran dipakai, Model akan gagal
 * dengan error SQL "Unknown column 'created_at'". Ini pola yang sama
 * seperti yang sudah diperbaiki di AlignPerusahaanTable — makanya
 * pendekatannya juga sama: tambah kolom yang hilang, bukan ubah Model.
 *
 * Tidak ada foreign key baru di migration ini (cuma tambah kolom biasa),
 * jadi processIndexes() tidak diperlukan di sini.
 */
class AddTimestampsToLamaranAndPencariKerja extends Migration
{
    public function up()
    {
        $timestampColumns = [
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
            'deleted_at' => ['type' => 'DATETIME', 'null' => true],
        ];

        $this->forge->addColumn('lamaran', $timestampColumns);
        $this->forge->addColumn('pencari_kerja', $timestampColumns);
    }

    public function down()
    {
        $this->forge->dropColumn('lamaran', ['created_at', 'updated_at', 'deleted_at']);
        $this->forge->dropColumn('pencari_kerja', ['created_at', 'updated_at', 'deleted_at']);
    }
}
