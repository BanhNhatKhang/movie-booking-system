<?php

namespace App\Controllers;

use Jenssegers\Blade\Blade;

class QuanLyLichChieuController
{
    public function quanLyLichChieu()
    {
        $blade = new Blade(
            realpath(__DIR__ . '/../Views'),
            realpath(__DIR__ . '/../../cache')
        );
        echo $blade->render('admin-views.LichChieu.QuanLyLichChieu', ['activePage' => 'schedule']);
    }

    public function themLichChieu()
    {
        $blade = new Blade(
            realpath(__DIR__ . '/../Views'),
            realpath(__DIR__ . '/../../cache')
        );
        echo $blade->render('admin-views.LichChieu.ThemLichChieu', ['activePage' => 'schedule']);
    }
    public function suaLichChieu()
    {
        $blade = new Blade(
            realpath(__DIR__ . '/../Views'),
            realpath(__DIR__ . '/../../cache')
        );
        echo $blade->render('admin-views.LichChieu.SuaLichChieu', ['activePage' => 'schedule']);
    }
}