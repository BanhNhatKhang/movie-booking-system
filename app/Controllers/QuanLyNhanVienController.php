<?php

namespace App\Controllers;

use Jenssegers\Blade\Blade;

class QuanLyNhanVienController
{
    public function quanLyNhanVien()
    {
        $blade = new Blade(
            realpath(__DIR__ . '/../Views'),
            realpath(__DIR__ . '/../../cache')
        );
        echo $blade->render('admin-views.QuanLyNhanVien.QuanLyNhanVien', ['activePage' => 'NhanVien']);
    }

}