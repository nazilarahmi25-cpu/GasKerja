<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

/**
 * Filter otorisasi berbasis session. Didaftarkan sebagai alias 'auth' di
 * app/Config/Filters.php, dipasang di route lewat 2 cara:
 * - Per-URI-pattern lewat array $filters di Filters.php (untuk halaman
 *   yang cukup "asal login", tanpa cek role tertentu).
 * - Per-route langsung di Routes.php dengan argumen role, contoh
 *   ['filter' => 'auth:admin'] (untuk halaman yang harus role tertentu,
 *   seperti dashboard-admin/dashboard-perusahaan/dashboard-pencari).
 */
class AuthFilter implements FilterInterface
{
    /**
     * Dijalankan SEBELUM controller. Mengecek status login, dan kalau ada
     * argumen role, mengecek juga apakah role user cocok.
     *
     * @param RequestInterface $request   Request yang masuk (tidak dipakai
     *        langsung di sini, tapi wajib ada sesuai FilterInterface).
     * @param array|null       $arguments Argumen dari Routes.php, contoh:
     *        ->filter('auth:admin') → $arguments = ['admin'] (hanya admin
     *        yang boleh lewat); ->filter('auth') → $arguments = null
     *        (semua yang sudah login boleh lewat, tanpa cek role).
     *
     * @return \CodeIgniter\HTTP\RedirectResponse|void Redirect ke /login
     *         kalau belum login atau role tidak cocok; tidak me-return
     *         apa pun (request lanjut ke controller) kalau lolos semua cek.
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        // 1. Cek apakah sudah login
        if (!session()->get('logged_in')) {
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
     * Dijalankan SETELAH controller selesai. Tidak dipakai (dikosongkan)
     * — filter ini murni untuk pengecekan SEBELUM controller jalan.
     *
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param array|null        $arguments
     *
     * @return void
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // kosong
    }
}
