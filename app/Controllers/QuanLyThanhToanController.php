<?php

namespace App\Controllers;

use Jenssegers\Blade\Blade;

class QuanLyThanhToanController
{
    public function quanLyThanhToan()
    {
        $blade = new Blade(
            realpath(__DIR__ . '/../Views'),
            realpath(__DIR__ . '/../../cache')
        );
        echo $blade->render('admin-views.ThanhToan.QuanLyThanhToan', ['activePage' => 'pay']);
    }

    public function chiTietThanhToan()
    {
        $blade = new Blade(
            realpath(__DIR__ . '/../Views'),
            realpath(__DIR__ . '/../../cache')
        );
        echo $blade->render('admin-views.ThanhToan.ChiTietThanhToan', ['activePage' => 'pay']);
    }
}