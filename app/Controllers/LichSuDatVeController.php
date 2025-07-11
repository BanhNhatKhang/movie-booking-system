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

        // Lấy user id từ session
        $userId = $_SESSION['user_id'];

        // Gọi model để lấy danh sách vé của user
        require_once __DIR__ . '/../Models/Ve.php';
        $veModel = new \App\Models\Ve();

        // Phân trang
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $perPage = 10;
        $offset = ($page - 1) * $perPage;

        $total = $veModel->countTicketsByUser($userId); // Viết hàm này trả về tổng số vé
        $tickets = $veModel->getTicketsByUser($userId, $perPage, $offset); // Sửa hàm này nhận limit, offset

        $totalPages = ceil($total / $perPage);

        echo $blade->render('users-views.LichSuDatVe.LichSuDatVe', [
            'activePage' => 'lichsudatve',
            'tickets' => $tickets,
            'page' => $page,
            'totalPages' => $totalPages
        ]);
    }
}