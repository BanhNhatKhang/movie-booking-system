<?php

namespace App\Controllers;

use Jenssegers\Blade\Blade;

class LichSuDatVeController
{
    private function checkUserAuth()
    {
        // Kiểm tra đăng nhập
        if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
            $_SESSION['error_message'] = 'Vui lòng đăng nhập để xem lịch sử đặt vé!';
            header('Location: /dang-nhap');
            exit;
        }
        
        // Kiểm tra role user (không phải admin)
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'user') {
            $_SESSION['error_message'] = 'Trang này chỉ dành cho người dùng!';
            header('Location: /dashboard'); // Admin chuyển về dashboard
            exit;
        }
    }

    public function lichsudatve()
    {
        $this->checkUserAuth(); // Kiểm tra quyền truy cập user
        $blade = new Blade(
            realpath(__DIR__ . '/../Views'),
            realpath(__DIR__ . '/../../cache')
        );

        echo $blade->render('users-views.LichSuDatVe.LichSuDatVe', ['activePage' => 'lichsudatve']);
    }
}