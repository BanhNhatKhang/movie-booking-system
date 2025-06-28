<?php

namespace App\Controllers;

use Jenssegers\Blade\Blade;
use App\Models\NhanVien;

class QuanLyNhanVienController
{
    private $nhanVienModel;
    private $blade;
    private function checkAdminAuth()
    {
        // Kiểm tra đăng nhập
        if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
            $_SESSION['error_message'] = 'Vui lòng đăng nhập để truy cập trang admin!';
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
    // GET: /admin/quan-ly-nhan-vien
    public function index()
    {
        $this->checkAdminAuth();
        $search = $_GET['search'] ?? '';
        
        if ($search) {
            $nhanViens = $this->nhanVienModel->searchNhanVien($search);
        } else {
            $nhanViens = $this->nhanVienModel->getAll();
        }
        
        echo $this->blade->render('admin-views.QuanLyNhanVien.QuanLyNhanVien', [
            'nhanViens' => $nhanViens,
            'search' => $search,
            'activePage' => 'nhanvien'
        ]);
    }
    
    // GET: /admin/them-nhan-vien  
}