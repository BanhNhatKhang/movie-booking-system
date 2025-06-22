<?php

namespace App\Controllers;

use Jenssegers\Blade\Blade;

class QuanLyDonDatVeController
{
    public function quanLyDonDatVe()
    {
        $blade = new Blade(
            realpath(__DIR__ . '/../Views'),
            realpath(__DIR__ . '/../../cache')
        );
        echo $blade->render('admin-views.QuanLyDonDatVe.QuanLyDonDatVe', ['activePage' => 'admin-orders']);
    }
    public function chiTietDonDatVe()
    {
        $blade = new Blade(
            realpath(__DIR__ . '/../Views'),
            realpath(__DIR__ . '/../../cache')
        );
        echo $blade->render('admin-views.QuanLyDonDatVe.ChiTietDon', ['activePage' => 'admin-orders']);
    }

}