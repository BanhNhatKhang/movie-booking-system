<?php

namespace App\Controllers;

use Jenssegers\Blade\Blade;
use App\Core\Csrf;

class AuthController
{
    public function dangNhap()
    {
        $blade = new Blade(
            realpath(__DIR__ . '/../Views'),
            realpath(__DIR__ . '/../../cache')
        );

        echo $blade->render('users-views.Login.DangNhap', ['activePage' => 'dangnhap']);
    }

    public function dangKy()
    {
        $blade = new Blade(
            realpath(__DIR__ . '/../Views'),
            realpath(__DIR__ . '/../../cache')
        );

        echo $blade->render('users-views.Login.DangKy', ['activePage' => 'dangky']);
    }

    //Xử lý đăng ký người dùng
    public function xuLyDangKy()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // THÊM: Kiểm tra CSRF token ĐẦU TIÊN
            $csrfToken = $_POST['csrf_token'] ?? '';
            
            if (!Csrf::checkToken($csrfToken)) {
                $_SESSION['error_message'] = 'Token bảo mật không hợp lệ! Vui lòng thử lại.';
                header('Location: /dang-ky');
                exit;
            }

            $nguoiDungModel = new \App\Models\NguoiDung();
            
            // Lấy dữ liệu từ form
            $tendangnhap = $_POST['nd_tendangnhap'] ?? '';
            $email = $_POST['nd_email'] ?? '';
            $cccd = $_POST['nd_cccd'] ?? '';
            $sdt = $_POST['nd_sdt'] ?? '';
            $matkhau = $_POST['nd_matkhau'] ?? '';
            $ngaysinh = $_POST['nd_ngaysinh'] ?? '';
            
            // Lưu dữ liệu vào session để giữ lại khi có lỗi
            $_SESSION['form_data'] = $_POST;
            
            // THÊM: Kiểm tra độ dài mật khẩu (tối thiểu 6 ký tự)
            if (strlen($matkhau) < 6) {
                $_SESSION['error_message'] = 'Mật khẩu phải có ít nhất 6 ký tự!';
                // REFRESH CSRF token khi có lỗi
                Csrf::refreshToken();
                header('Location: /dang-ky');
                exit;
            }
            // Kiểm tra mật khẩu khớp
            if ($_POST['nd_matkhau'] !== $_POST['confirm_password']) {
                $_SESSION['error_message'] = 'Mật khẩu nhập lại không khớp!';
                Csrf::refreshToken();
                header('Location: /dang-ky');
                exit;
            }
            //Kiểm tra ngày sinh bắt buộc
            if (empty($ngaysinh)) {
                $_SESSION['error_message'] = 'Ngày sinh không được để trống!';
                Csrf::refreshToken();
                header('Location: /dang-ky');
                exit;
            }
            //Kiểm tra tuổi >13 <100
            if (!empty($ngaysinh)) {
                $birthDate = new \DateTime($ngaysinh);
                $today = new \DateTime();
                $age = $today->diff($birthDate)->y;
                
                if ($age < 13) {
                    $_SESSION['error_message'] = 'Bạn phải đủ 13 tuổi trở lên để đăng ký tài khoản!';
                    Csrf::refreshToken();
                    header('Location: /dang-ky');
                    exit;
                }
                
                if ($age > 100) {
                    $_SESSION['error_message'] = 'Ngày sinh không hợp lệ!';
                    Csrf::refreshToken();
                    header('Location: /dang-ky');
                    exit;
                }
            }
            // Kiểm tra trùng tên đăng nhập
            $checkUsername = $nguoiDungModel->checkExists('nd_tendangnhap', $tendangnhap);
            if ($checkUsername) {
                $_SESSION['error_message'] = 'Tên đăng nhập đã tồn tại!';
                Csrf::refreshToken();
                header('Location: /dang-ky');
                exit;
            }
            
            // Kiểm tra trùng email
            $checkEmail = $nguoiDungModel->checkExists('nd_email', $email);
            if ($checkEmail) {
                $_SESSION['error_message'] = 'Email đã được sử dụng!';
                Csrf::refreshToken();
                header('Location: /dang-ky');
                exit;
            }
             // Kiểm tra định dạng CCCD (12 số)
            if (!preg_match('/^\d{12}$/', $cccd)) {
                $_SESSION['error_message'] = 'CCCD phải có đúng 12 số!';
                Csrf::refreshToken();
                header('Location: /dang-ky');
                exit;
            }
            
            // Kiểm tra định dạng SĐT (10 số)
            if (!preg_match('/^\d{10}$/', $sdt)) {
                $_SESSION['error_message'] = 'Số điện thoại phải có đúng 10 số!';
                Csrf::refreshToken();
                header('Location: /dang-ky');
                exit;
            }
            // Kiểm tra trùng CCCD
            $checkCCCD = $nguoiDungModel->checkExists('nd_cccd', $cccd);
            if ($checkCCCD) {
                $_SESSION['error_message'] = 'CCCD đã được sử dụng!';
                Csrf::refreshToken();
                header('Location: /dang-ky');
                exit;
            }
            
            // Kiểm tra trùng SĐT
            $checkSDT = $nguoiDungModel->checkExists('nd_sdt', $sdt);
            if ($checkSDT) {
                $_SESSION['error_message'] = 'Số điện thoại đã được sử dụng!';
                Csrf::refreshToken();
                header('Location: /dang-ky');
                exit;
            }
    
            $id = $nguoiDungModel->generateRandomId();
            $data = [
                'nd_id' => $id,
                'nd_hoten' => $_POST['nd_hoten'] ?? '',
                'nd_ngaysinh' => $ngaysinh ?: null,
                'nd_gioitinh' => isset($_POST['nd_gioitinh']) ? (bool)$_POST['nd_gioitinh'] : null,
                'nd_sdt' => $sdt,
                'nd_cccd' => $cccd,
                'nd_email' => $email,
                'nd_tendangnhap' => $tendangnhap,
                'nd_matkhau' => password_hash($_POST['nd_matkhau'] ?? '', PASSWORD_DEFAULT),
                'nd_ngaydangky' => date('Y-m-d'),
                'nd_role' => 'user'
            ];
            
            $nguoiDungModel->create($data);
            
            // Xóa form_data sau khi thành công
            unset($_SESSION['form_data']);
            
            // REFRESH CSRF token sau khi thành công
            Csrf::refreshToken();
            
            $_SESSION['success_message'] = 'Đăng ký thành công! Vui lòng đăng nhập để tiếp tục.';
            header('Location: /dang-nhap');
            exit;
        }
        header('Location: /dang-ky');
        exit;
    }

    //Xử lý đăng nhập người dùng
    public function xuLyDangNhap()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // THÊM: Kiểm tra CSRF token ĐẦU TIÊN
            $csrfToken = $_POST['csrf_token'] ?? '';
            
            if (!Csrf::checkToken($csrfToken)) {
                $_SESSION['error_message'] = 'Token bảo mật không hợp lệ! Vui lòng thử lại.';
                header('Location: /dang-nhap');
                exit;
            }

            $nguoiDungModel = new \App\Models\NguoiDung();
            
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';
            
            if (empty($username) || empty($password)) {
                $_SESSION['error_message'] = 'Vui lòng nhập đầy đủ thông tin!';
                // REFRESH CSRF token khi có lỗi
                Csrf::refreshToken();
                header('Location: /dang-nhap');
                exit;
            }
            
            $user = $nguoiDungModel->findByUsernameOrEmail($username);
            
            if ($user && (password_verify($password, $user['nd_matkhau']) || $password === $user['nd_matkhau'])) {
                // Kiểm tra tài khoản có bị khóa không
                if (isset($user['nd_trangthai']) && $user['nd_trangthai'] === 'locked') {
                    $_SESSION['error_message'] = 'Tài khoản của bạn đã bị khóa. Vui lòng liên hệ admin!';
                    Csrf::refreshToken();
                    header('Location: /dang-nhap');
                    exit;
                }
                
                // Lưu thông tin vào session
                $_SESSION['logged_in'] = true;
                $_SESSION['user_id'] = $user['nd_id'];
                $_SESSION['user_name'] = $user['nd_hoten'];
                $_SESSION['user_role'] = $user['nd_role'];
                $_SESSION['user_email'] = $user['nd_email'];
                
                //  REFRESH CSRF token sau khi đăng nhập thành công
                Csrf::refreshToken();
                
                // PHÂN HƯỚNG THEO ROLE
                if ($user['nd_role'] === 'admin') {
                    // Kiểm tra có pending payment không (admin không cần)
                    unset($_SESSION['pending_payment']);
                    $_SESSION['success_message'] = 'Đăng nhập thành công! Chào mừng Admin ' . $user['nd_hoten'];
                    header('Location: /dashboard'); 
                    exit;
                } else {
                    // User thường
                    // Kiểm tra có pending payment không
                    if (isset($_SESSION['pending_payment'])) {
                        $_SESSION['success_message'] = 'Đăng nhập thành công! Vui lòng hoàn tất thanh toán.';
                        header('Location: /thanh-toan');
                        exit;
                    }
                    
                    $_SESSION['success_message'] = 'Đăng nhập thành công! Chào mừng ' . $user['nd_hoten'];
                    header('Location: /');
                    exit;
                }
            } else {
                // Đăng nhập thất bại
                $_SESSION['error_message'] = 'Tên đăng nhập hoặc mật khẩu không chính xác!';
                Csrf::refreshToken();
                header('Location: /dang-nhap');
                exit;
            }
        }
        header('Location: /dang-nhap');
        exit;
    }
    
    public function dangXuat()
    {
        // Xóa tất cả session
        session_unset();
        session_destroy();
        
        // Tạo session mới và thông báo
        session_start();
        $_SESSION['success_message'] = 'Đăng xuất thành công!';
        
        header('Location: /');
        exit;
    }
}