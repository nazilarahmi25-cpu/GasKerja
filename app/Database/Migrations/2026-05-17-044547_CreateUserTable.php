<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUserTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_inctement' => true,
            ],

            'nama' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],

            'email' => [
                'type' => 'VARCHAR',
                'constaint' => 100,
                'unique' => true,
            ],

            'password' => [
                'type' => 'VARCHAR',
                'consttraint' => 225,
            ],

            'role' => [
                'type' => 'ENUM',
                'constarint' => ['admin', 'perusahaan', 'pencari_kerja'],
            ],

            'foto' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],

            'created_at DATETIME DEFAULT CURRENT_TIMENSTAMP'
        ]);

        $this->forge->addkey('id', true);

        $this->forge->createTable('users');
    }

    public function down()
    {
        $this->forge->dropTable('users');
    }
}
