<?php
// filepath: c:\mysites\ct27501-project-BanhNhatKhang-1\app\Controllers\QuanLyNguoiDungController.php

namespace App\Controllers;

use Jenssegers\Blade\Blade;
use App\Helpers\AuthHelper;
use Exception;

class QuanLyNguoiDungController
{
    public function quanLyNguoiDung()
    {
        AuthHelper::checkAccess('admin_only');
        
        $nguoiDungModel = new \App\Models\NguoiDung();
        
        $search = $_GET['q'] ?? '';
        $status = $_GET['status'] ?? '';
        $role = 'user';
        $page = (int)($_GET['page'] ?? 1);
        $limit = 15;
        
        $users = $nguoiDungModel->getAllWithFilterPaginated($search, $role, $status, $page, $limit);
        $totalUsers = $nguoiDungModel->getTotalCount($search, $role, $status);
        $totalPages = ceil($totalUsers / $limit);
                
        $blade = new Blade(
            realpath(__DIR__ . '/../Views'),
            realpath(__DIR__ . '/../../cache')
        );
        
        echo $blade->render('admin-views.QuanLyNguoiDung.QuanLyNguoiDung', [
            'activePage' => 'user',
            'users' => $users,
            'search' => $search,
            'status' => $status,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalUsers' => $totalUsers
        ]);
    }

    public function chiTietNguoiDung()
    {
        AuthHelper::checkAccess('admin_only');
        
        $id = $_GET['id'] ?? '';
        if (empty($id)) {
            $_SESSION['error_message'] = 'ID người dùng không hợp lệ!';
            header('Location: /quan-ly-nguoi-dung');
            exit;
        }
        
        $nguoiDungModel = new \App\Models\NguoiDung();
        $user = $nguoiDungModel->getById($id);
        
        if (!$user) {
            $_SESSION['error_message'] = 'Không tìm thấy người dùng!';
            header('Location: /quan-ly-nguoi-dung');
            exit;
        }
        
        $bookingHistory = $this->getUserBookingHistory($id);
        $userStats = $this->getUserStats($id);
        
        $blade = new Blade(
            realpath(__DIR__ . '/../Views'),
            realpath(__DIR__ . '/../../cache')
        );
        
        echo $blade->render('admin-views.QuanLyNguoiDung.ChiTietNguoiDung', [
            'activePage' => 'user',
            'user' => $user,
            'bookingHistory' => $bookingHistory,
            'userStats' => $userStats
        ]);
    }

    public function suaNguoiDung()
    {
        AuthHelper::checkAccess('admin_only');
        
        $id = $_GET['id'] ?? '';
        if (empty($id)) {
            $_SESSION['error_message'] = 'ID người dùng không hợp lệ!';
            header('Location: /quan-ly-nguoi-dung');
            exit;
        }
        
        $nguoiDungModel = new \App\Models\NguoiDung();
        $user = $nguoiDungModel->getById($id);
        
        if (!$user) {
            $_SESSION['error_message'] = 'Không tìm thấy người dùng!';
            header('Location: /quan-ly-nguoi-dung');
            exit;
        }
        
        // Xử lý form submit
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            return $this->xuLySuaNguoiDung($id, $user);
        }
        
        $blade = new Blade(
            realpath(__DIR__ . '/../Views'),
            realpath(__DIR__ . '/../../cache')
        );
        
        echo $blade->render('admin-views.QuanLyNguoiDung.SuaNguoiDung', [
            'activePage' => 'user',
            'user' => $user
        ]);
    }

    private function xuLySuaNguoiDung($id, $user)
    {
        $nguoiDungModel = new \App\Models\NguoiDung();
        
        $hoten = trim($_POST['nd_hoten'] ?? '');
        $email = trim($_POST['nd_email'] ?? '');
        $sdt = trim($_POST['nd_sdt'] ?? '');
        $gioitinh = $_POST['nd_gioitinh'] ?? null;  
        $ngaysinh = $_POST['nd_ngaysinh'] ?? null;  
        $cccd = trim($_POST['nd_cccd'] ?? '');     
        $role = $_POST['nd_role'] ?? 'user';
        $trangthai = $_POST['nd_trangthai'] ?? 'active';
        $errors = [];
        
        if (empty($hoten)) {
            $errors[] = 'Họ tên không được để trống';
        }
        
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Email không hợp lệ';
        }
        
        if (!empty($sdt) && !preg_match('/^[0-9]{10,11}$/', $sdt)) {
            $errors[] = 'Số điện thoại phải có 10-11 chữ số';
        }
        
        if (!empty($cccd) && !preg_match('/^[0-9]{9,12}$/', $cccd)) {
            $errors[] = 'CCCD/CMND phải có 9-12 chữ số';
        }
        
        if (!empty($ngaysinh)) {
            $birthDate = new \DateTime($ngaysinh);
            $today = new \DateTime();
            $age = $today->diff($birthDate)->y;
            
            if ($age < 13 || $age > 100) {
                $errors[] = 'Tuổi phải từ 13 đến 100';
            }
        }
        
        if ($email !== $user['nd_email']) {
            $existingUser = $nguoiDungModel->findByEmail($email);
            if ($existingUser) {
                $errors[] = 'Email đã được sử dụng bởi người dùng khác';
            }
        }

        if ($id == $_SESSION['user_id'] && $role !== $user['nd_role']) {
            $errors[] = 'Bạn không thể thay đổi role của chính mình';
        }
        
        if ($id == $_SESSION['user_id'] && $trangthai === 'locked') {
            $errors[] = 'Bạn không thể khóa tài khoản của chính mình';
        }
        
        if (!empty($errors)) {
            $_SESSION['error_message'] = implode('<br>', $errors);
            header('Location: /sua-nguoi-dung?id=' . $id);
            exit;
        }

        $updateData = [
            'nd_hoten' => $hoten,
            'nd_email' => $email,
            'nd_sdt' => $sdt,
            'nd_gioitinh' => $gioitinh,    // ✅ THÊM
            'nd_ngaysinh' => $ngaysinh,    // ✅ THÊM
            'nd_cccd' => $cccd,            // ✅ THÊM
            'nd_role' => $role,
            'nd_trangthai' => $trangthai
        ];
        
        if ($nguoiDungModel->updatePartial($id, $updateData)) {
            $_SESSION['success_message'] = 'Cập nhật thông tin người dùng thành công!';
        } else {
            $_SESSION['error_message'] = 'Có lỗi xảy ra khi cập nhật thông tin!';
        }
        
        header('Location: /quan-ly-nguoi-dung');
        exit;
    }
    
    public function khoaNguoiDung()
    {
        AuthHelper::checkAccess('admin_only');
        
        $id = $_GET['id'] ?? $_POST['id'] ?? '';
        if (empty($id)) {
            $_SESSION['error_message'] = 'ID người dùng không hợp lệ!';
            header('Location: /quan-ly-nguoi-dung');
            exit;
        }
        
        $nguoiDungModel = new \App\Models\NguoiDung();
        $user = $nguoiDungModel->getById($id);
        
        if (!$user) {
            $_SESSION['error_message'] = 'Không tìm thấy người dùng!';
            header('Location: /quan-ly-nguoi-dung');
            exit;
        }

        if ($id == $_SESSION['user_id']) {
            $_SESSION['error_message'] = 'Bạn không thể khóa tài khoản của chính mình!';
            header('Location: /quan-ly-nguoi-dung');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Xử lý khóa/mở khóa
            $currentStatus = $user['nd_trangthai'] ?? 'active';
            $newStatus = $currentStatus === 'locked' ? 'active' : 'locked';
            $action = $newStatus === 'locked' ? 'khóa' : 'mở khóa';
            
            if ($nguoiDungModel->updateStatus($id, $newStatus)) {
                $_SESSION['success_message'] = "Đã {$action} tài khoản '{$user['nd_hoten']}' thành công!";
            } else {
                $_SESSION['error_message'] = "Có lỗi xảy ra khi {$action} tài khoản!";
            }
            
            header('Location: /quan-ly-nguoi-dung');
            exit;
        }
        
        // Hiển thị form xác nhận
        $blade = new Blade(
            realpath(__DIR__ . '/../Views'),
            realpath(__DIR__ . '/../../cache')
        );
        
        echo $blade->render('admin-views.QuanLyNguoiDung.KhoaNguoiDung', [
            'activePage' => 'user',
            'nguoidung' => $user
        ]);
    }

    public function resetMatKhau()
    {
        AuthHelper::checkAccess('admin_only');
        
        $id = $_POST['id'] ?? '';
        if (empty($id)) {
            $_SESSION['error_message'] = 'ID người dùng không hợp lệ!';
            header('Location: /quan-ly-nguoi-dung');
            exit;
        }
        
        $nguoiDungModel = new \App\Models\NguoiDung();
        $user = $nguoiDungModel->getById($id);
        
        if (!$user) {
            $_SESSION['error_message'] = 'Không tìm thấy người dùng!';
            header('Location: /quan-ly-nguoi-dung');
            exit;
        }
        
        if ($id == $_SESSION['user_id']) {
            $_SESSION['error_message'] = 'Bạn không thể reset mật khẩu của chính mình!';
            header('Location: /quan-ly-nguoi-dung');
            exit;
        }
        
        // Tạo mật khẩu mới (123456 mặc định)
        $newPassword = '123456';
        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
        
        if ($nguoiDungModel->updatePassword($id, $hashedPassword)) {
            $_SESSION['success_message'] = "Đã reset mật khẩu cho '{$user['nd_hoten']}' thành: {$newPassword}";
        } else {
            $_SESSION['error_message'] = 'Có lỗi xảy ra khi reset mật khẩu!';
        }
        
        header('Location: /quan-ly-nguoi-dung');
        exit;
    }
    private function getUserBookingHistory($userId)
    {

        try {

            return [
                [
                    'movie_name' => 'Avengers: Endgame',
                    'showtime' => '2024-06-25 19:30:00',
                    'seats' => 'A5, A6',
                    'total_price' => 200000,
                    'booking_date' => '2024-06-20 14:30:00',
                    'status' => 'completed'
                ],
                [
                    'movie_name' => 'Spider-Man: No Way Home',
                    'showtime' => '2024-06-28 21:00:00',
                    'seats' => 'B8, B9',
                    'total_price' => 180000,
                    'booking_date' => '2024-06-26 16:45:00',
                    'status' => 'pending'
                ]
            ];
        } catch (Exception $e) {
            error_log("Get booking history error: " . $e->getMessage());
            return [];
        }
    }

    private function getUserStats($userId)
    {

        try {
            return [
                'total_bookings' => 15,
                'total_spent' => 2500000,
                'favorite_genre' => 'Hành động',
                'join_date' => '2024-01-15',
                'last_booking' => '2024-06-28',
                'average_spending' => 166667  // total_spent / total_bookings
            ];
        } catch (Exception $e) {
            error_log("Get user stats error: " . $e->getMessage());
            return [
                'total_bookings' => 0,
                'total_spent' => 0,
                'favorite_genre' => 'N/A',
                'join_date' => 'N/A',
                'last_booking' => 'N/A',
                'average_spending' => 0
            ];
        }
    }
}