<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePerusahaanTable extends Migration
{
    public function up()
    {
        $this->forge->addfield([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],

            'nama' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],

            'email' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'unique' => true,
            ],

            'password' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],

            'role' => [
                'type' => 'ENUM',
                'constraint' => ['admin', 'perusahaan', 'pencari_kerja'] 
            ],

            'foto' => [
                'type' => 'VARCHAR',
                'constarint' => 255,
                'null' => true,
            ],
            
            'created_at DATETIME DEFAULT CURRENT_TIMESTAMP'
        
        ]);

        $this->forge->addkey('id', true);

        $this->forge->createTable('users');
    
    }

    public function down()
    {
        $this->forge->dropTable('users');
    }
}



