<?php

namespace App\Controllers;

use Jenssegers\Blade\Blade;

class ThongTinCaNhanController
{
    public function thongtincanhan()
    {
        // Kiểm tra đăng nhập
        if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
            header('Location: /dang-nhap');
            exit;
        }
        
        // Lấy thông tin user từ database
        $nguoiDungModel = new \App\Models\NguoiDung();
        $user = $nguoiDungModel->getById($_SESSION['user_id']);
        
        if (!$user) {
            $_SESSION['error_message'] = 'Không tìm thấy thông tin người dùng!';
            header('Location: /');
            exit;
        }
        
        $blade = new Blade('../app/Views', '../storage/cache');
        echo $blade->render('users-views.ThongTinCaNhan.ThongTinCaNhan', ['user' => $user]);
    }

    public function doiMatKhau()
    {
        // Kiểm tra đăng nhập
        if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
            $_SESSION['error_message'] = 'Vui lòng đăng nhập để đổi mật khẩu!';
            header('Location: /dang-nhap');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nguoiDungModel = new \App\Models\NguoiDung();
            $user = $nguoiDungModel->getById($_SESSION['user_id']);
            
            if (!$user) {
                $_SESSION['error_message'] = 'Không tìm thấy thông tin người dùng!';
                header('Location: /thong-tin-ca-nhan');
                exit;
            }
            
            $currentPassword = trim($_POST['current_password'] ?? '');
            $newPassword = trim($_POST['new_password'] ?? '');
            $confirmPassword = trim($_POST['confirm_password'] ?? '');
            
            // 1. Kiểm tra dữ liệu đầu vào
            if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
                $_SESSION['error_message'] = 'Vui lòng nhập đầy đủ thông tin!';
                header('Location: /thong-tin-ca-nhan');
                exit;
            }
            
            // 2. Kiểm tra độ dài mật khẩu mới TRƯỚC
            if (strlen($newPassword) < 6) {
                $_SESSION['error_message'] = 'Mật khẩu mới phải có ít nhất 6 ký tự!';
                header('Location: /thong-tin-ca-nhan');
                exit;
            }
            
            // 3. Kiểm tra mật khẩu mới khớp
            if ($newPassword !== $confirmPassword) {
                $_SESSION['error_message'] = 'Mật khẩu mới không khớp!';
                header('Location: /thong-tin-ca-nhan');
                exit;
            }
            
            // 4. Kiểm tra mật khẩu hiện tại có đúng không
            if (!password_verify($currentPassword, $user['nd_matkhau'])) {
                $_SESSION['error_message'] = 'Mật khẩu hiện tại không đúng!';
                header('Location: /thong-tin-ca-nhan');
                exit;
            }
            
            // 5. Kiểm tra mật khẩu mới KHÁC mật khẩu cũ
            if ($currentPassword === $newPassword) {
                $_SESSION['error_message'] = 'Mật khẩu mới phải khác mật khẩu hiện tại!';
                header('Location: /thong-tin-ca-nhan');
                exit;
            }
            
            // 6. Cập nhật mật khẩu
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

            // SỬA: Dùng updatePassword thay vì update
            $updateResult = $nguoiDungModel->updatePassword($_SESSION['user_id'], $hashedPassword);

            if ($updateResult) {
                $_SESSION['success_message'] = 'Đổi mật khẩu thành công!';
            } else {
                $_SESSION['error_message'] = 'Có lỗi xảy ra khi đổi mật khẩu. Vui lòng thử lại!';
            }
            
            header('Location: /thong-tin-ca-nhan');
            exit;
        }
        
        // Nếu không phải POST request
        header('Location: /thong-tin-ca-nhan');
        exit;
    }
    
}