<?php
// filepath: c:\mysites\ct27501-project-BanhNhatKhang-1\app\Helpers\AuthHelper.php

namespace App\Helpers;

class AuthHelper
{
    /**
     * Kiểm tra quyền truy cập
     * @param string $pageType admin_only|user_only|both|public
     */
    public static function checkAccess($pageType = 'public')
    {
        // Kiểm tra đăng nhập cho tất cả trang không public
        if ($pageType !== 'public') {
            if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
                $_SESSION['error_message'] = 'Vui lòng đăng nhập!';
                header('Location: /dang-nhap');
                exit;
            }
        }
        
        $userRole = $_SESSION['user_role'] ?? '';
        
        switch ($pageType) {
            case 'admin_only':
                // Chỉ admin
                if ($userRole !== 'admin') {
                    $_SESSION['error_message'] = 'Bạn không có quyền admin!';
                    header('Location: /');
                    exit;
                }
                break;
                
            case 'user_only':
                // Chỉ user
                if ($userRole !== 'user') {
                    $_SESSION['error_message'] = 'Trang này chỉ dành cho user!';
                    if (isset($_SESSION['original_role']) && $_SESSION['original_role'] === 'admin') {
                        $_SESSION['error_message'] = 'Bạn cần chuyển sang quyền user để truy cập trang này!';
                        header('Location: /dashboard');
                    } else {
                        header('Location: /');
                    }
                    exit;
                }
                break;
                
            case 'both':
                // Cả admin và user đều được
                if (!in_array($userRole, ['user', 'admin'])) {
                    $_SESSION['error_message'] = 'Bạn không có quyền truy cập!';
                    header('Location: /');
                    exit;
                }
                break;
                
            case 'public':
                // Ai cũng truy cập được
                break;
        }
        
        return true;
    }
    
   
    public static function isAdmin()
    {
        return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
    }
    
    
    public static function isUser()
    {
        return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'user';
    }
    
    
    public static function getCurrentRole()
    {
        return $_SESSION['user_role'] ?? 'guest';
    }
    
   
    public static function isAdminSwitchedToUser()
    {
        return isset($_SESSION['original_role']) && $_SESSION['original_role'] === 'admin' && $_SESSION['user_role'] === 'user';
    }
}