<?php

namespace App\Controllers;

use Jenssegers\Blade\Blade;

class LichSuDatVeController
{
    public function lichsudatve()
    {
        $blade = new Blade(
            realpath(__DIR__ . '/../Views'),
            realpath(__DIR__ . '/../../cache')
        );

        echo $blade->render('users-views.LichSuDatVe.LichSuDatVe', ['activePage' => 'lichsudatve']);
    }
}