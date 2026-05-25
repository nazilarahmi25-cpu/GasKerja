<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateLowonganTable extends Migration
{
    public function up()
    {
       $this->forge->addField([

            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
],

            'perusahaan_id' => [
                'type' => 'INT',
                'unsigned' => true,
            ],
                
           'judul' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],

           'deskripsi' => [
                'type' => 'TEXT',
            ],

           'kualifikasi' => [
                'type' => 'TEXT',
            ],

            'lokasi' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],

            'gaji' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
            ],

            'tipe_kerja' => [
                'type' => 'ENUM',
                'constraint' => ['Full Time', 'Part Time', 'Freelance'],
                'default' => 'Full Time'
            ],

            'status' => [
                'type' => 'ENUM',
                'constraint' => ['aktif', 'nonaktif', 'pending'],
                'default' => 'pending'
            ],

            'tanggal_post DATETIME DEFAULT CURRENT_TIMESTAMP'
        ]);

        $this->forge->addKey('id', true);

        $this->forge->addForeignKey(
            'perusahaan_id',
            'perusahaan',
            'id',
            'CASCADE',
            'CASCADE'
        );

         $this->forge->createTable('lowongan');
    }

    public function down()
    {
         $this->forge->dropTable('lowongan');
    }
}