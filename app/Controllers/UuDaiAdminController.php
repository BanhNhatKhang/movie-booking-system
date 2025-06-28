<?php

namespace App\Controllers;

use Jenssegers\Blade\Blade;

class UuDaiAdminController
{
    private function checkAdminAuth()
    {
        // Kiểm tra đăng nhập
        if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
            $_SESSION['error_message'] = 'Vui lòng đăng nhập để truy cập trang admin!';
            header('Location: /dang-nhap');
            exit;
        }
        
        // Kiểm tra role admin
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            $_SESSION['error_message'] = 'Bạn không có quyền truy cập trang admin!';
            header('Location: /'); // Chuyển về trang chủ user
            exit;
        }
    }

    public function uuDai()
    {
        $this->checkAdminAuth(); // Kiểm tra quyền truy cập admin
        $blade = new Blade(
            realpath(__DIR__ . '/../Views'),
            realpath(__DIR__ . '/../../cache')
        );
        echo $blade->render('admin-views.UuDai.QuanLyUuDai', ['activePage' => 'uudai']);
    }

    public function themUuDai()
    {
        $this->checkAdminAuth(); // Kiểm tra quyền truy cập admin
        $blade = new Blade(
            realpath(__DIR__ . '/../Views'),
            realpath(__DIR__ . '/../../cache')
        );
        echo $blade->render('admin-views.UuDai.ThemUuDai', ['activePage' => 'uudai']);
    }

    public function suaUuDai()
    {
        $this->checkAdminAuth(); // Kiểm tra quyền truy cập admin
        $blade = new Blade(
            realpath(__DIR__ . '/../Views'),
            realpath(__DIR__ . '/../../cache')
        );
        echo $blade->render('admin-views.UuDai.SuaUuDai', ['activePage' => 'uudai']);
    }
}