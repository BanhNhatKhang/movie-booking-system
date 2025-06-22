<?php

namespace App\Controllers;

use Jenssegers\Blade\Blade;

class MovieController
{
    public function phimDangChieu()
    {
        $blade = new Blade(
            realpath(__DIR__ . '/../Views'),
            realpath(__DIR__ . '/../../cache')
        );
        echo $blade->render('users-views.Phim.PhimDangChieu', [
            'activePage' => 'movies'
        ]);
    }

    public function phimSapChieu()
    {
        $blade = new Blade(
            realpath(__DIR__ . '/../Views'),
            realpath(__DIR__ . '/../../cache')
        );
        echo $blade->render('users-views.Phim.PhimSapChieu', [
            'activePage' => 'movies'
        ]);
    }

    public function chiTietPhim()
    {
        $blade = new Blade(
            realpath(__DIR__ . '/../Views'),
            realpath(__DIR__ . '/../../cache')
        );
        echo $blade->render('user-view.Phim.ChiTietPhim', [
            'activePage' => 'movies'
        ]);
    }

    public function chonGhe()
    {
        $blade = new Blade(
            realpath(__DIR__ . '/../Views'),
            realpath(__DIR__ . '/../../cache')
        );
        echo $blade->render('user-view.Phim.ChonGhe', [
            'activePage' => 'movies'
        ]);
    }
}