<?php

namespace App\Controllers;

use Jenssegers\Blade\Blade;

class DangKyController
{
    public function dangky()
    {
        $blade = new Blade(
            realpath(__DIR__ . '/../Views'),
            realpath(__DIR__ . '/../../cache')
        );

        echo $blade->render('users-views.Login.DangKy', ['activePage' => 'dangky']);
    }
}