<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateLamaranTable extends Migration
{
    public function up()
    {
        $this->forge->addField([

             'id' => [
                'type' => 'INT',
                'unsigned' => true,
                'auto_increment' => true,
            ],

            'lowongan_id' => [
                'type' => 'INT',
                'unsigned' => true,
            ],

            'pencari_id' => [
                'type' => 'INT',
                'unsigned' => true,
            ],

            'cv_file' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],

             'surat_lamaran' => [
                'type' => 'TEXT',
                'null' => true,
            ],

            'status' => [
                'type' => 'ENUM',
                'constraint' => ['diproses', 'diterima', 'ditolak'],
                'default' => 'diproses'
            ],

             'tanggal_lamar DATETIME DEFAULT CURRENT_TIMESTAMP'
        ]);

        $this->forge->addKey('id', true);

        $this->forge->addForeignKey(
            'lowongan_id',
            'lowongan',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->forge->addForeignKey(
            'pencari_id',
            'pencari_kerja',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->forge->createTable('lamaran');
    }

    public function down()
    {
        $this->forge->dropTable('lamaran');
    }
}