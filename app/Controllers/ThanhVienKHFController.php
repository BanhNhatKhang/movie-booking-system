<?php

namespace App\Controllers;

use Jenssegers\Blade\Blade;

class ThanhVienKHFController
{
    public function thanhvien()
    {
        $blade = new Blade(
            realpath(__DIR__ . '/../Views'),
            realpath(__DIR__ . '/../../cache')
        );
    
        echo $blade->render('users-views.ThanhVien.ThanhVienKHF', ['activePage' => 'member']);
    }
    public function diemthuong()
    {
        $blade = new Blade(
            realpath(__DIR__ . '/../Views'),
            realpath(__DIR__ . '/../../cache')
        );
    
        echo $blade->render('users-views.ThanhVien.DiemThuong', ['activePage' => 'member']);
    }
    public function capdo()
    {
        $blade = new Blade(
            realpath(__DIR__ . '/../Views'),
            realpath(__DIR__ . '/../../cache')
        );
    
        echo $blade->render('users-views.ThanhVien.CapDo', ['activePage' => 'member']);
    }
    public function quatang()
    {
        $blade = new Blade(
            realpath(__DIR__ . '/../Views'),
            realpath(__DIR__ . '/../../cache')
        );
    
        echo $blade->render('users-views.ThanhVien.QuaTang', ['activePage' => 'member']);
    }
}