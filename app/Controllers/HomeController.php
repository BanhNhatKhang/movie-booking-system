<?php

namespace App\Controllers;

use Jenssegers\Blade\Blade;

class HomeController
{
    public function index()
    {
        $blade = new Blade(
            realpath(__DIR__ . '/../Views'),
            realpath(__DIR__ . '/../../cache')
        );
        echo $blade->render('index', ['activePage' => 'home']);
    }

}