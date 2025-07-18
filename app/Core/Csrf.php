<?php
namespace App\Core;

class Csrf
{
    public static function generateToken()
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    public static function checkToken($token)
    {
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }
    
    // Thêm Method để refresh token sau khi sử dụng
    public static function refreshToken()
    {
        unset($_SESSION['csrf_token']);
        return self::generateToken();
    }
    
    // Thêm Method để tạo HTML input hidden
    public static function getTokenInput()
    {
        $token = self::generateToken();
        return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($token) . '">';
    }
}