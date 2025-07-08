<?php

namespace App\Controllers;

use Jenssegers\Blade\Blade;
use App\Helpers\AuthHelper;

class DatVeTaiQuayController
{

    public function datVeTaiQuay()
    {
        AuthHelper::checkAccess('admin_only');
        $blade = new Blade(
            realpath(__DIR__ . '/../Views'),
            realpath(__DIR__ . '/../../cache')
        );
        echo $blade->render('admin-views.DatVeTaiQuay.DatVeTaiQuay', ['activePage' => 'book-ticket']);
    }

}