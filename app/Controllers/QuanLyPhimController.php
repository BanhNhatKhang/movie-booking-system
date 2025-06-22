<?php

namespace App\Controllers;

use Jenssegers\Blade\Blade;

class QuanLyPhimController
{
    public function quanLyPhim()
    {
        $blade = new Blade(
            realpath(__DIR__ . '/../Views'),
            cachePath: realpath(__DIR__ . '/../../cache')
        );
        echo $blade->render('admin-views.QuanLyPhim.QuanLyPhim', ['activePage' => 'admin-movies']);
    }

    public function themPhim()
    {
        $blade = new Blade(
            realpath(__DIR__ . '/../Views'),
            cachePath: realpath(__DIR__ . '/../../cache')
        );
        echo $blade->render('admin-views.QuanLyPhim.ThemPhim', ['activePage' => 'admin-movies']);
    }
    public function suaPhim()
    {
        $blade = new Blade(
            realpath(__DIR__ . '/../Views'),
            cachePath: realpath(__DIR__ . '/../../cache')
        );
        echo $blade->render('admin-views.QuanLyPhim.SuaPhim', ['activePage' => 'admin-movies']);
    }

    public function doiTrangThaiPhim()
    {
        header('Location: /quan-ly-phim?msg=status_success');
        exit;
    }
}