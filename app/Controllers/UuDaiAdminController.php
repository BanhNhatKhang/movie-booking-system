<?php

namespace App\Controllers;

use Jenssegers\Blade\Blade;

class UuDaiAdminController
{
    public function uuDai()
    {
        $blade = new Blade(
            realpath(__DIR__ . '/../Views'),
            realpath(__DIR__ . '/../../cache')
        );
        echo $blade->render('admin-views.UuDai.QuanLyUuDai', ['activePage' => 'uudai']);
    }

    public function themUuDai()
    {
        $blade = new Blade(
            realpath(__DIR__ . '/../Views'),
            realpath(__DIR__ . '/../../cache')
        );
        echo $blade->render('admin-views.UuDai.ThemUuDai', ['activePage' => 'uudai']);
    }

    public function suaUuDai()
    {
        $blade = new Blade(
            realpath(__DIR__ . '/../Views'),
            realpath(__DIR__ . '/../../cache')
        );
        echo $blade->render('admin-views.UuDai.SuaUuDai', ['activePage' => 'uudai']);
    }
}