<?php

namespace App\Controllers;

use Jenssegers\Blade\Blade;

class QuanLyPhongGheController
{
    public function quanLyPhongGhe()
    {
        $blade = new Blade(
            realpath(__DIR__ . '/../Views'),
            realpath(__DIR__ . '/../../cache')
        );
        echo $blade->render('admin-views.QuanLyPhongGhe.QuanLyPhongGhe', ['activePage' => 'PhongGhe']);
    }

}