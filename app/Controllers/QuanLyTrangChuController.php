<?php

namespace App\Controllers;

use Jenssegers\Blade\Blade;

class QuanLyTrangChuController
{
    public function trangChu()
    {
        $blade = new Blade(
            realpath(__DIR__ . '/../Views'),
            cachePath: realpath(__DIR__ . '/../../cache')
        );
        echo $blade->render('admin-views.TrangChu.QuanLyTrangChu', ['activePage' => 'home']);
    }

    public function themPoster()
    {
        $blade = new \Jenssegers\Blade\Blade(
            realpath(__DIR__ . '/../Views'),
            realpath(__DIR__ . '/../../cache')
        );
        echo $blade->render('admin-views.TrangChu.ThemPoster', ['activePage' => 'home']);
    }

    public function suaPoster()
    {
        $blade = new \Jenssegers\Blade\Blade(
            realpath(__DIR__ . '/../Views'),
            realpath(__DIR__ . '/../../cache')
        );
        echo $blade->render('admin-views.TrangChu.SuaPoster', ['activePage' => 'home']);
    }
    public function themUuDai()
    {
        $blade = new \Jenssegers\Blade\Blade(
            realpath(__DIR__ . '/../Views'),
            realpath(__DIR__ . '/../../cache')
        );
        echo $blade->render('admin-views.TrangChu.ThemUuDaiHome', ['activePage' => 'home']);
    }

    public function suauuDai()
    {
        $blade = new \Jenssegers\Blade\Blade(
            realpath(__DIR__ . '/../Views'),
            realpath(__DIR__ . '/../../cache')
        );
        echo $blade->render('admin-views.TrangChu.SuaUuDaiHome', ['activePage' => 'home']);
    }
}