<?php

namespace App\Controllers;

use Jenssegers\Blade\Blade;

class DashboardController
{
    public function dashboard()
    {
        $blade = new Blade(
            realpath(__DIR__ . '/../Views'),
            realpath(__DIR__ . '/../../cache')
        );
        echo $blade->render('admin-views.Dashboard.index', ['activePage' => 'dashboard']);
    }

}