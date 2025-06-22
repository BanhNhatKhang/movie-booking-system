<?php

namespace App\Controllers;

use Jenssegers\Blade\Blade;

class DatVeTaiQuayController
{
    public function datVeTaiQuay()
    {
        $blade = new Blade(
            realpath(__DIR__ . '/../Views'),
            realpath(__DIR__ . '/../../cache')
        );
        echo $blade->render('admin-views.DatVeTaiQuay.DatVeTaiQuay', ['activePage' => 'book-ticket']);
    }

}