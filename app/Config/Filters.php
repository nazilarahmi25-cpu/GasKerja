<?php

namespace Config;

use CodeIgniter\Config\Filters as BaseFilters;
use CodeIgniter\Filters\Cors;
use CodeIgniter\Filters\CSRF;
use CodeIgniter\Filters\DebugToolbar;
use CodeIgniter\Filters\ForceHTTPS;
use CodeIgniter\Filters\Honeypot;
use CodeIgniter\Filters\InvalidChars;
use CodeIgniter\Filters\PageCache;
use CodeIgniter\Filters\PerformanceMetrics;
use CodeIgniter\Filters\SecureHeaders;

class Filters extends BaseFilters
{
    /**
     * Daftar alias filter.
     * 'auth' adalah alias untuk AuthFilter milik kita.
     */
    public array $aliases = [
        'csrf'          => CSRF::class,
        'toolbar'       => DebugToolbar::class,
        'honeypot'      => Honeypot::class,
        'invalidchars'  => InvalidChars::class,
        'secureheaders' => SecureHeaders::class,
        'cors'          => Cors::class,
        'forcehttps'    => ForceHTTPS::class,
        'pagecache'     => PageCache::class,
        'performance'   => PerformanceMetrics::class,

        // ✅ Filter buatan sendiri untuk proteksi halaman
        'auth' => \App\Filters\AuthFilter::class,
    ];

    /**
     * Filter wajib yang selalu berjalan di setiap request.
     * Jangan ubah bagian ini.
     */
    public array $required = [
        'before' => [
            'forcehttps',
            'pagecache',
        ],
        'after' => [
            'pagecache',
            'performance',
            'toolbar',
        ],
    ];

    /**
     * Filter global — dijalankan di SEMUA route.
     * Kita tidak pasang 'auth' di sini supaya
     * halaman publik (/, /login, /register) tetap bisa diakses.
     */
    public array $globals = [
        'before' => [
            // 'honeypot',
            // 'csrf',
        ],
        'after' => [
            // 'secureheaders',
        ],
    ];

    /**
     * Filter per HTTP method.
     * Tidak dipakai di project ini.
     */
    public array $methods = [];

    /**
     * ✅ Filter per URI pattern.
     *
     * Semua halaman dashboard dan kelola wajib login.
     * Format: 'alias' => ['before' => ['uri/pattern/*']]
     *
     * Penjelasan pattern:
     *  - 'lowongan*'      → cocok dengan /lowongan, /lowongan/create, /lowongan/edit/1, dll
     *  - 'profil'         → halaman profil user
     *  - 'notifikasi'     → halaman notifikasi
     *
     * Route dashboard-pencari / dashboard-perusahaan / dashboard-admin TIDAK
     * didaftarkan di sini lagi — masing-masing sudah pakai filter
     * 'auth:<role>' langsung di Routes.php supaya role-nya juga dicek,
     * bukan cuma status login.
     */
    public array $filters = [
        'auth' => [
            'before' => [
                'lowongan',
                'lowongan/*',
                'profil',
                'notifikasi',
                'logout',
            ],
        ],
    ];
}
