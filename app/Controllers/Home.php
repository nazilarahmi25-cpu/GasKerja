<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string
    {
        return view('landing_page');
    }
    public function about_us(): string
    {
        return view('about_us');
    }
    public function login(): string
    {
        return view('halaman_login');
    }
    public function register(): string
    {
        return view('halaman_register');
    }
    public function dashboard_pencari(): string
    {
        return view('dashboard_pencari');
    }
    public function dashboard_perusahaan(): string
    {
        return view('dashboard_perusahaan');
    }
    public function dashboard_admin(): string
    {
        return view('dashboard_admin');
    }
    public function detail_lowongan(): string
    {
        return view('detail_lowongan');
    }
    public function apply_lowongan(): string
    {
        return view('apply_lowongan');
    }
    public function profil(): string
    {
        return view('profil');
    }
    public function notifikasi(): string
    {
        return view('notifikasi');
    }
}
