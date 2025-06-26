<?php

namespace App\Controllers;

use Jenssegers\Blade\Blade;

class QuanLyNguoiDungController
{
    public function quanLyNguoiDung()
    {
        $blade = new Blade(
            realpath(__DIR__ . '/../Views'),
            realpath(__DIR__ . '/../../cache')
        );
        echo $blade->render('admin-views.QuanLyNguoiDung.QuanLyNguoiDung', ['activePage' => 'user']);
    }

    public function chiTietNguoiDung()
    {
        $blade = new Blade(
            realpath(__DIR__ . '/../Views'),
            realpath(__DIR__ . '/../../cache')
        );
        echo $blade->render('admin-views.QuanLyNguoiDung.ChiTietNguoiDung', ['activePage' => 'user']);
    }
    public function khoaNguoiDung()
    {
        $blade = new Blade(
            realpath(__DIR__ . '/../Views'),
            realpath(__DIR__ . '/../../cache')
        );
        
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;

        
        echo $blade->render('admin-views.QuanLyNguoiDung.KhoaNguoiDung', [
            'id' => $id
        ]);
    }
}