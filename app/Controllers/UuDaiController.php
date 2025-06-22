<?php

namespace App\Controllers;

use Jenssegers\Blade\Blade;

class UuDaiController
{
    public function uudai()
    {
        $blade = new Blade(
            realpath(__DIR__ . '/../Views'),
            realpath(__DIR__ . '/../../cache')
        );

        echo $blade->render('users-views.UuDai.UuDai', ['activePage' => 'offers']);
    }
}