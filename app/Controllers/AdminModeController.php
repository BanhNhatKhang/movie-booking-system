<?php
// filepath: c:\mysites\ct27501-project-BanhNhatKhang-1\app\Controllers\AdminModeController.php

namespace App\Controllers;

use Jenssegers\Blade\Blade;

class AdminModeController
{
    private function checkAdminAuth()
    {
        if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
            $_SESSION['error_message'] = 'Vui lòng đăng nhập!';
            header('Location: /dang-nhap');
            exit;
        }
        
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            $_SESSION['error_message'] = 'Chỉ admin mới có thể chuyển đổi!';
            header('Location: /');
            exit;
        }
    }

    public function switchToUser()
    {
        $this->checkAdminAuth();
        
        // Backup role gốc
        $_SESSION['original_role'] = $_SESSION['user_role'];
        
        // Chuyển thành user
        $_SESSION['user_role'] = 'user';
        
        $_SESSION['success_message'] = 'Đã chuyển sang tài khoản User. Bạn có thể sử dụng như khách hàng bình thường!';
        
        // Chuyển về trang chủ user
        header('Location: /');
        exit;
    }

    public function switchToAdmin()
    {
        // Kiểm tra có original_role không
        if (!isset($_SESSION['original_role']) || $_SESSION['original_role'] !== 'admin') {
            $_SESSION['error_message'] = 'Không thể chuyển về admin!';
            header('Location: /');
            exit;
        }
        
        // Khôi phục role admin
        $_SESSION['user_role'] = $_SESSION['original_role'];
        unset($_SESSION['original_role']);
        
        $_SESSION['success_message'] = 'Đã quay lại quyền Admin!';
        
        // Chuyển về dashboard admin
        header('Location: /dashboard');
        exit;
    }
}