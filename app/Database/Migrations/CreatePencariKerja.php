<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePencariKerjaTable extends Migration
{
    public function up()
    {
        $this->forge->addfield([
            
            'id' => [
                'type' => 'INT',
                'unsigned' => true,
                'auto_increment' => true,
            ],

            'user_id' => [
                'type' => 'INT',
                'unsigned' => true,
            ],

            'alamat' => [
                'type' => 'TEXT',
                'null' => true,
            ],

            'no_hp' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => true,
            ],

            'pendidikan' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],

            'skill' => [
                'type' => 'TEXT',
                'null' => true,
            ],
 
            'cv' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
        ]);

        $this->forge->addkey('id', true);

        $this->forge->addForeignKey(
            'user_id',
            'users',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->forge->createTable('pencari_kerja');

    }

    public function down()
    {
        $this->forge->dropTable('pencari_kerja');
    }
}

        

           