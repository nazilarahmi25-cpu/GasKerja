<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Akun admin untuk testing lokal. Aman dijalankan berulang kali —
     * kalau email sudah ada, tidak akan bikin duplikat.
     */
    public function run()
    {
        $email = 'admin@gaskerja.test';

        $sudahAda = $this->db->table('users')->where('email', $email)->get()->getRow();

        if ($sudahAda) {
            echo "Akun admin ({$email}) sudah ada, dilewati.\n";
            return;
        }

        $this->db->table('users')->insert([
            'nama'       => 'Admin Testing',
            'email'      => $email,
            'password'   => password_hash('admin12345', PASSWORD_DEFAULT),
            'role'       => 'admin',
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        echo "Akun admin dibuat: {$email} / admin12345\n";
    }
}
