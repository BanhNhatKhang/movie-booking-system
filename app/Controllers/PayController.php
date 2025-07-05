<?php

namespace App\Controllers;

use Jenssegers\Blade\Blade;

class PayController
{
    public function thanhToan()
    {
        // Kiểm tra đăng nhập
        if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
            // Lưu thông tin ghế vào session để giữ lại sau khi đăng nhập
            if (isset($_GET['seats']) && isset($_GET['total'])) {
                $_SESSION['pending_payment'] = [
                    'seats' => $_GET['seats'],
                    'total' => $_GET['total'],
                    'movie_name' => $_GET['movie'] ?? 'Tên phim demo',
                    'show_time' => $_GET['time'] ?? '08:30 - 25/06/2024',
                    'room' => $_GET['room'] ?? 'Phòng 2'
                ];
            }
            
            $_SESSION['error_message'] = 'Vui lòng đăng nhập để thanh toán vé!';
            header('Location: /dang-nhap');
            exit;
        }
        
        // Lấy thông tin ghế từ URL hoặc session
        $seats = $_GET['seats'] ?? $_SESSION['pending_payment']['seats'] ?? '';
        $total = $_GET['total'] ?? $_SESSION['pending_payment']['total'] ?? 0;
        $movieName = $_GET['movie'] ?? $_SESSION['pending_payment']['movie_name'] ?? 'Tên phim demo';
        $showTime = $_GET['time'] ?? $_SESSION['pending_payment']['show_time'] ?? '08:30 - 25/06/2024';
        $room = $_GET['room'] ?? $_SESSION['pending_payment']['room'] ?? 'Phòng 2';
        
        // Xóa pending payment sau khi lấy dữ liệu
        if (isset($_SESSION['pending_payment'])) {
            unset($_SESSION['pending_payment']);
        }
        
        // Kiểm tra có ghế được chọn không
        if (empty($seats)) {
            $_SESSION['error_message'] = 'Vui lòng chọn ghế trước khi thanh toán!';
            header('Location: /chon-ghe');
            exit;
        }
        
        $blade = new \Jenssegers\Blade\Blade(
            realpath(__DIR__ . '/../Views'),
            realpath(__DIR__ . '/../../cache')
        );
        echo $blade->render('users-views.ThanhToan.ThanhToan', [
            'seats' => $seats,
            'total' => $total,
            'movieName' => $movieName,
            'showTime' => $showTime,
            'room' => $room,
            'user' => $_SESSION['user_name'] ?? 'User'
        ]);
    }
    
    public function xuLyThanhToan()
    {
        // Kiểm tra đăng nhập
        if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
            $_SESSION['error_message'] = 'Vui lòng đăng nhập để thực hiện thanh toán!';
            header('Location: /dang-nhap');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Xử lý logic thanh toán ở đây
            // Lưu thông tin đặt vé vào database
            
            $_SESSION['success_message'] = 'Thanh toán thành công! Cảm ơn bạn đã đặt vé tại KHF Cinema.';
            header('Location: /lich-su-dat-ve');
            exit;
        }
        
        header('Location: /thanh-toan');
        exit;
    }
}