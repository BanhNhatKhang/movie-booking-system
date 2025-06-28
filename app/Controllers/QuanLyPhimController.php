<?php

namespace App\Controllers;

use Jenssegers\Blade\Blade;

class QuanLyPhimController
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

    public function quanLyPhim()
    {
        $this->checkAdminAuth();
        $blade = new Blade(
            realpath(__DIR__ . '/../Views'),
            cachePath: realpath(__DIR__ . '/../../cache')
        );
        echo $blade->render('admin-views.QuanLyPhim.QuanLyPhim', ['activePage' => 'admin-movies']);
    }

    public function themPhim()
    {
        $this->checkAdminAuth();
        $blade = new Blade(
            realpath(__DIR__ . '/../Views'),
            cachePath: realpath(__DIR__ . '/../../cache')
        );
        echo $blade->render('admin-views.QuanLyPhim.ThemPhim', ['activePage' => 'admin-movies']);
    }
    public function suaPhim()
    {
        $this->checkAdminAuth();
        $blade = new Blade(
            realpath(__DIR__ . '/../Views'),
            cachePath: realpath(__DIR__ . '/../../cache')
        );
        echo $blade->render('admin-views.QuanLyPhim.SuaPhim', ['activePage' => 'admin-movies']);
    }

    public function doiTrangThaiPhim()
    {
        $this->checkAdminAuth();
        header('Location: /quan-ly-phim?msg=status_success');
        exit;
    }
    
    public function xoaPhim()
    {
        $this->checkAdminAuth();
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;

        header('Location: /quan-ly-phim?msg=delete_success');
        exit;
    }
}