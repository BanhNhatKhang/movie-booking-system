<?php

namespace App\Controllers;

use Jenssegers\Blade\Blade;

class ThongTinCaNhanController
{
    public function thongtincanhan()
    {
        $blade = new Blade(
            realpath(__DIR__ . '/../Views'),
            realpath(__DIR__ . '/../../cache')
        );

        echo $blade->render('users-views.ThongTinCaNhan.ThongTinCaNhan', ['activePage' => 'dangky']);
    }
}