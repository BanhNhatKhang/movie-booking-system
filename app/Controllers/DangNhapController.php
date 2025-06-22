<?php

namespace App\Controllers;

use Jenssegers\Blade\Blade;

class DangNhapController
{
    public function dangnhap()
    {
        $blade = new Blade(
            realpath(__DIR__ . '/../Views'),
            realpath(__DIR__ . '/../../cache')
        );

        echo $blade->render('users-views.Login.DangNhap', ['activePage' => 'dangnhap']);
    }
}