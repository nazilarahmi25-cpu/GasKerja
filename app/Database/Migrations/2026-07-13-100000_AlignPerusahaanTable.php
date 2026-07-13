<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * KENAPA migration ini dibuat:
 * Sebelumnya, akun perusahaan didaftarkan sebagai baris di tabel `users`
 * (kolom role='perusahaan'), tapi `lowongan.perusahaan_id` punya foreign
 * key ke tabel `perusahaan` yang TERPISAH. Akibatnya id yang dipakai saat
 * menyimpan lowongan (id dari `users`) tidak pernah cocok dengan id di
 * `perusahaan` — insert lowongan gagal foreign key constraint, atau (lebih
 * bahaya) nyasar ke perusahaan lain yang kebetulan id-nya sama.
 *
 * Perbaikannya: `perusahaan` diubah jadi tabel PROFIL (mengikuti pola yang
 * sama seperti `pencari_kerja`), terhubung ke `users` lewat kolom `user_id`
 * baru. Kredensial login (email/password/role) tetap satu-satunya sumber
 * kebenaran di tabel `users` — makanya kolom itu dihapus dari `perusahaan`
 * di migration ini (poin 4 di up()), supaya tidak ada data ganda yang bisa
 * beda sendiri-sendiri.
 *
 * Migration lama `CreatePerusahaanTable` TIDAK diedit karena sudah pernah
 * dijalankan di database (tercatat di tabel `migrations`, batch 2) — CI4
 * tidak akan menjalankan ulang migration yang sudah tercatat, jadi
 * mengedit file lama tidak akan mengubah skema yang sudah live.
 */
class AlignPerusahaanTable extends Migration
{
    public function up()
    {
        // 1. Tambah user_id (FK ke users.id) — pola sama seperti pencari_kerja
        $this->forge->addColumn('perusahaan', [
            'user_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
                'after'      => 'id',
            ],
        ]);

        // addForeignKey() cuma mendaftarkan FK-nya; processIndexes() yang
        // benar-benar menerapkan ke tabel yang sudah ada (bukan tabel baru).
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->processIndexes('perusahaan');

        // 2. Samakan nama kolom dengan yang dipakai Model (nama_perusahaan)
        $this->forge->modifyColumn('perusahaan', [
            'nama' => [
                'name'       => 'nama_perusahaan',
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => false,
            ],
        ]);

        // 3. Tambah kolom yang dibutuhkan Model tapi belum ada di tabel live
        $this->forge->addColumn('perusahaan', [
            'alamat' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'nama_perusahaan',
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        // 4. Hapus kolom kredensial yang terduplikasi dari users
        //    (perusahaan sekarang murni tabel profil, bukan tabel akun)
        $this->forge->dropColumn('perusahaan', ['email', 'password', 'role', 'foto']);
    }

    public function down()
    {
        $this->forge->addColumn('perusahaan', [
            'email' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => false,
            ],
            'password' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => false,
            ],
            'role' => [
                'type'       => 'ENUM',
                'constraint' => ['admin', 'perusahaan', 'pencari_kerja'],
                'null'       => false,
            ],
            'foto' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
        ]);

        $this->forge->dropColumn('perusahaan', ['updated_at', 'deleted_at', 'alamat']);

        $this->forge->modifyColumn('perusahaan', [
            'nama_perusahaan' => [
                'name'       => 'nama',
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => false,
            ],
        ]);

        $this->db->query('ALTER TABLE perusahaan DROP FOREIGN KEY perusahaan_user_id_foreign');
        $this->forge->dropColumn('perusahaan', 'user_id');
    }
}
