<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class AuthFilter implements FilterInterface
{
    /**
     * Dijalankan SEBELUM controller.
     * Cek apakah user sudah login. Jika belum, redirect ke /login.
     *
     * $arguments diisi dari Routes.php, contoh:
     *   ->filter('auth:admin')       → hanya admin
     *   ->filter('auth:perusahaan')  → hanya perusahaan
     *   ->filter('auth')             → semua yang sudah login
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        // 1. Cek apakah sudah login
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        // 2. Kalau ada argument role, cek apakah role cocok
        if (!empty($arguments)) {
            $roleYangDiizinkan = $arguments[0]; // contoh: 'admin', 'perusahaan', 'pencari_kerja'
            $roleUser          = session()->get('role');

            if ($roleUser !== $roleYangDiizinkan) {
                // User login tapi role tidak sesuai → redirect ke dashboard mereka
                return redirect()->to('/login')
                    ->with('error', 'Kamu tidak punya akses ke halaman tersebut.');
            }
        }
    }

    /**
     * Dijalankan SETELAH controller.
     * Tidak dipakai, dikosongkan saja.
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // kosong
    }
}
