<?php

namespace App\Controllers;

use Jenssegers\Blade\Blade;

class QuanLyNguoiDungController
{
    private function checkAdminAuth()
    {
        // Kiểm tra đăng nhập
        if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
            $_SESSION['error_message'] = 'Vui lòng đăng nhập để truy cập trang này!';
            header('Location: /dang-nhap');
            exit;
        }
        
        // Kiểm tra role admin
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            $_SESSION['error_message'] = 'Bạn không có quyền truy cập trang admin!';
            header('Location: /'); // Chuyển về trang chủ user
            exit;
        }
    }

    public function quanLyNguoiDung()
    {
        $this->checkAdminAuth();
        
        $nguoiDungModel = new \App\Models\NguoiDung();
        
        // Lấy tham số tìm kiếm
        $search = $_GET['q'] ?? '';
        $role = $_GET['role'] ?? '';
        $status = $_GET['status'] ?? '';
        
        // Lấy danh sách người dùng
        $users = $nguoiDungModel->getAllWithFilter($search, $role, $status);
        
        $blade = new Blade(
            realpath(__DIR__ . '/../Views'),
            realpath(__DIR__ . '/../../cache')
        );
        
        echo $blade->render('admin-views.QuanLyNguoiDung.QuanLyNguoiDung', [
            'activePage' => 'user',
            'users' => $users,
            'search' => $search,
            'role' => $role,
            'status' => $status
        ]);
    }

    public function chiTietNguoiDung()
    {
        $this->checkAdminAuth(); 
        
        $id = $_GET['id'] ?? '';
        if (empty($id)) {
            $_SESSION['error_message'] = 'ID người dùng không hợp lệ!';
            header('Location: /admin/quan-ly-nguoi-dung');
            exit;
        }
        
        $nguoiDungModel = new \App\Models\NguoiDung();
        $user = $nguoiDungModel->getById($id);
        
        if (!$user) {
            $_SESSION['error_message'] = 'Không tìm thấy người dùng!';
            header('Location: /admin/quan-ly-nguoi-dung');
            exit;
        }
        
        $blade = new Blade(
            realpath(__DIR__ . '/../Views'),
            realpath(__DIR__ . '/../../cache')
        );
        
        echo $blade->render('admin-views.QuanLyNguoiDung.ChiTietNguoiDung', [
            'activePage' => 'user',
            'user' => $user
        ]);
    }
    
    public function khoaNguoiDung()
    {
        $this->checkAdminAuth();
        
        $id = $_GET['id'] ?? $_POST['id'] ?? '';
        if (empty($id)) {
            $_SESSION['error_message'] = 'ID người dùng không hợp lệ!';
            header('Location: /admin/quan-ly-nguoi-dung');
            exit;
        }
        
        $nguoiDungModel = new \App\Models\NguoiDung();
        $user = $nguoiDungModel->getById($id);
        
        if (!$user) {
            $_SESSION['error_message'] = 'Không tìm thấy người dùng!';
            header('Location: /admin/quan-ly-nguoi-dung');
            exit;
        }
        
        // Không cho khóa chính mình
        if ($id === $_SESSION['user_id']) {
            $_SESSION['error_message'] = 'Bạn không thể khóa tài khoản của chính mình!';
            header('Location: /admin/quan-ly-nguoi-dung');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Xử lý khóa/mở khóa
            $currentStatus = $user['nd_trangthai'] ?? 'active';
            $newStatus = $currentStatus === 'locked' ? 'active' : 'locked';
            $action = $newStatus === 'locked' ? 'khóa' : 'mở khóa';
            
            if ($nguoiDungModel->updateStatus($id, $newStatus)) {
                $_SESSION['success_message'] = 'Đã ' . $action . ' tài khoản thành công!';
            } else {
                $_SESSION['error_message'] = 'Có lỗi xảy ra khi ' . $action . ' tài khoản!';
            }
            
            header('Location: /admin/quan-ly-nguoi-dung');
            exit;
        }
        
        $blade = new Blade(
            realpath(__DIR__ . '/../Views'),
            realpath(__DIR__ . '/../../cache')
        );
        
        echo $blade->render('admin-views.QuanLyNguoiDung.KhoaNguoiDung', [
            'activePage' => 'user',
            'user' => $user,
            'id' => $id
        ]);
    }

    public function suaNguoiDung()
    {
        $this->checkAdminAuth();
        
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
        
        $blade = new Blade(
            realpath(__DIR__ . '/../Views'),
            realpath(__DIR__ . '/../../cache')
        );
        
        echo $blade->render('admin-views.QuanLyNguoiDung.SuaNguoiDung', [
            'activePage' => 'user',
            'user' => $user
        ]);
    }

    public function xuLySuaNguoiDung()
    {
        $this->checkAdminAuth();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nguoiDungModel = new \App\Models\NguoiDung();
            
            $id = $_POST['id'] ?? '';
            $hoten = trim($_POST['nd_hoten'] ?? '');
            $email = trim($_POST['nd_email'] ?? '');
            $role = $_POST['nd_role'] ?? 'user';
            
            // Validation
            if (empty($id) || empty($hoten) || empty($email)) {
                $_SESSION['error_message'] = 'Vui lòng nhập đầy đủ thông tin!';
                header('Location: /sua-nguoi-dung?id=' . $id);
                exit;
            }
            
            // Kiểm tra email trùng (trừ chính user này)
            $existingUser = $nguoiDungModel->findByEmail($email);
            if ($existingUser && $existingUser['nd_id'] !== $id) {
                $_SESSION['error_message'] = 'Email đã được sử dụng bởi người dùng khác!';
                header('Location: /sua-nguoi-dung?id=' . $id);
                exit;
            }
            
            // Update chỉ những trường cần thiết
            if ($nguoiDungModel->updatePartial($id, [
                'nd_hoten' => $hoten,
                'nd_email' => $email, 
                'nd_role' => $role
            ])) {
                $_SESSION['success_message'] = 'Cập nhật thông tin thành công!';
            } else {
                $_SESSION['error_message'] = 'Có lỗi xảy ra khi cập nhật!';
            }
        }
        
        header('Location: /quan-ly-nguoi-dung');
        exit;
    }
}