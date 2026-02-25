<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        $data = [
            'title' => 'Dashboard Utama'
        ];
        return view('dashboard', $data);
    }
}
