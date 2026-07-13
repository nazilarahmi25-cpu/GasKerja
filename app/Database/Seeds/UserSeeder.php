<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

/**
 * Seeder untuk membuat akun admin bawaan, supaya tim (atau dosen saat
 * presentasi) selalu punya cara praktis dapat akun admin tanpa perlu
 * insert manual lewat SQL. Jalankan dengan: `php spark db:seed UserSeeder`.
 */
class UserSeeder extends Seeder
{
    /**
     * Membuat satu akun admin uji (email: admin@gaskerja.test, password:
     * admin12345) kalau belum ada. Idempoten — aman dijalankan berkali-kali,
     * tidak akan membuat baris duplikat karena dicek dulu lewat email
     * sebelum insert.
     *
     * @return void
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
            'nama'       => 'Admin',
            'email'      => $email,
            'password'   => password_hash('admin12345', PASSWORD_DEFAULT),
            'role'       => 'admin',
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        echo "Akun admin dibuat: {$email} / admin12345\n";
    }
}
