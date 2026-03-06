<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string
    {
        $data = [
            'title'      => 'Dashboard',
            'activeMenu' => 'dashboard',
        ];

        return view('dashboard', $data);
    }

    public function unauthorized(): string
    {
        return view('errors/html/error_403', [
            'message' => 'Anda tidak memiliki hak akses untuk halaman ini.'
        ]);
    }
}
