<?php

namespace App\Controllers;

use Jenssegers\Blade\Blade;
use App\Helpers\AuthHelper;
use Exception;

class AdminController
{
    private $blade;

    public function __construct()
    {
        $this->blade = new Blade(
            realpath(__DIR__ . '/../Views'),
            realpath(__DIR__ . '/../../cache')
        );
    }

    public function thongTinCaNhan()
    {
        AuthHelper::checkAccess('admin_only');
        
        try {
            $adminId = $_SESSION['user_id'];
            $nguoiDungModel = new \App\Models\NguoiDung();
            $admin = $nguoiDungModel->getById($adminId);
            
            if (!$admin) {
                $_SESSION['error_message'] = 'Không tìm thấy thông tin tài khoản';
                header('Location: /dashboard');
                exit;
            }
            
            echo $this->blade->render('admin-views.ThongTinCaNhan.ThongTinCaNhan', [
                'admin' => $admin
            ]);
            
        } catch (Exception $e) {
            error_log("Error in thongTinCaNhan: " . $e->getMessage());
            $_SESSION['error_message'] = 'Có lỗi xảy ra';
            header('Location: /dashboard');
            exit;
        }
    }

    public function doiMatKhau()
    {
        AuthHelper::checkAccess('admin_only');
        
        echo $this->blade->render('admin-views.ThongTinCaNhan.DoiMatKhau');
    }

    public function xuLyDoiMatKhau()
    {
        AuthHelper::checkAccess('admin_only');
        
        try {
            $adminId = $_SESSION['user_id'];
            $matKhauCu = $_POST['mat_khau_cu'] ?? '';
            $matKhauMoi = $_POST['mat_khau_moi'] ?? '';
            $xacNhanMatKhau = $_POST['xac_nhan_mat_khau'] ?? '';
            
            // Validation
            if (empty($matKhauCu) || empty($matKhauMoi) || empty($xacNhanMatKhau)) {
                $_SESSION['error_message'] = 'Vui lòng nhập đầy đủ thông tin';
                header('Location: /admin/doi-mat-khau');
                exit;
            }
            
            if ($matKhauMoi !== $xacNhanMatKhau) {
                $_SESSION['error_message'] = 'Mật khẩu mới và xác nhận mật khẩu không khớp';
                header('Location: /admin/doi-mat-khau');
                exit;
            }
            
            if (strlen($matKhauMoi) < 6) {
                $_SESSION['error_message'] = 'Mật khẩu mới phải có ít nhất 6 ký tự';
                header('Location: /admin/doi-mat-khau');
                exit;
            }
            
            // Kiểm tra mật khẩu cũ
            $nguoiDungModel = new \App\Models\NguoiDung();
            $admin = $nguoiDungModel->getById($adminId);
            
            if (!$admin || !password_verify($matKhauCu, $admin['nd_matkhau'])) {
                $_SESSION['error_message'] = 'Mật khẩu cũ không chính xác';
                header('Location: /admin/doi-mat-khau');
                exit;
            }
            
            // Kiểm tra mật khẩu mới khác mật khẩu cũ
            if ($matKhauCu === $matKhauMoi) {
                $_SESSION['error_message'] = 'Mật khẩu mới phải khác mật khẩu hiện tại';
                header('Location: /admin/doi-mat-khau');
                exit;
            }
            
            // Cập nhật mật khẩu mới
            $matKhauMoiHash = password_hash($matKhauMoi, PASSWORD_DEFAULT);
            $result = $nguoiDungModel->updatePassword($adminId, $matKhauMoiHash);
            
            if ($result) {
                $_SESSION['success_message'] = 'Đổi mật khẩu thành công';
            } else {
                $_SESSION['error_message'] = 'Đổi mật khẩu thất bại';
            }
            
            header('Location: /admin/doi-mat-khau');
            exit;
            
        } catch (Exception $e) {
            error_log("Error in xuLyDoiMatKhau: " . $e->getMessage());
            $_SESSION['error_message'] = 'Có lỗi xảy ra khi đổi mật khẩu';
            header('Location: /admin/doi-mat-khau');
            exit;
        }
    }
}