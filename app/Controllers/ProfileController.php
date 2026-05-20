<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class ProfileController extends BaseController
{
    public function index()
    {
        $session = session();

        $data = [
            'username' => $session->get('username'),
            'role' => $session->get('role'),
            'email' => $session->get('email') ?? 'kanaya@mhs.dinus.ac.id',
            'waktu_login' => $session->get('waktu_login') ?? date('Y-m-d H:i:s'),
            'status_login' => $session->get('isLoggedIn') ? 'Aktif' : 'Tidak Aktif',
        ];

        return view('v_profile', $data);
    }
}