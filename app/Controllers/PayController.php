<?php

namespace App\Controllers;

use Jenssegers\Blade\Blade;

class PayController
{
    public function thanhToan()
    {
        $seats = $_GET['seats'] ?? '';
        $total = $_GET['total'] ?? 0;
        $blade = new \Jenssegers\Blade\Blade(
            realpath(__DIR__ . '/../Views'),
            realpath(__DIR__ . '/../../cache')
        );
        echo $blade->render('users-views.ThanhToan.ThanhToan', [
            'seats' => $seats,
            'total' => $total
        ]);
    }
}