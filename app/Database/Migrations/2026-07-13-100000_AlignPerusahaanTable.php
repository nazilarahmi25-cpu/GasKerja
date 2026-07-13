<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Migration baru untuk menyelaraskan tabel `perusahaan` yang live dengan
 * arsitektur baru: `perusahaan` jadi tabel profil (mengikuti pola
 * `pencari_kerja`), terhubung ke `users` lewat `user_id`. Login/kredensial
 * tetap sepenuhnya di tabel `users` — tidak diduplikasi lagi di sini.
 *
 * Migration lama `CreatePerusahaanTable` TIDAK diedit karena sudah pernah
 * dijalankan (tercatat di tabel migrations, batch 2).
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
