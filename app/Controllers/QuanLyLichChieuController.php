<?php

namespace App\Controllers;

use Jenssegers\Blade\Blade;

class QuanLyLichChieuController
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

    public function quanLyLichChieu()
    {
        $this->checkAdminAuth();
        $blade = new Blade(
            realpath(__DIR__ . '/../Views'),
            realpath(__DIR__ . '/../../cache')
        );
        echo $blade->render('admin-views.LichChieu.QuanLyLichChieu', ['activePage' => 'schedule']);
    }

    public function themLichChieu()
    {
        $this->checkAdminAuth();
        $blade = new Blade(
            realpath(__DIR__ . '/../Views'),
            realpath(__DIR__ . '/../../cache')
        );
        echo $blade->render('admin-views.LichChieu.ThemLichChieu', ['activePage' => 'schedule']);
    }
    public function suaLichChieu()
    {
        $this->checkAdminAuth();
        $blade = new Blade(
            realpath(__DIR__ . '/../Views'),
            realpath(__DIR__ . '/../../cache')
        );
        echo $blade->render('admin-views.LichChieu.SuaLichChieu', ['activePage' => 'schedule']);
    }
}