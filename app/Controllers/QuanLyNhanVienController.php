<?php

namespace App\Controllers;

use Jenssegers\Blade\Blade;
use App\Models\NhanVien;

class QuanLyNhanVienController
{
    private $nhanVienModel;
    private $blade;
    
    public function __construct()
    {
        session_start();
        $this->nhanVienModel = new NhanVien();
        $this->blade = new Blade(
            realpath(__DIR__ . '/../Views'),
            realpath(__DIR__ . '/../../cache')
        );
        
        // Generate CSRF token if not exists
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
    }
    
    // GET: /admin/quan-ly-nhan-vien
    public function index()
    {
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
    public function create()
    {
        echo $this->blade->render('admin-views.QuanLyNhanVien.ThemNhanVien', [
            'activePage' => 'nhanvien'
        ]);
    }
    
    // POST: /admin/them-nhan-vien
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /admin/them-nhan-vien');
            return;
        }
        
        // Validate CSRF token
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            $_SESSION['error_message'] = 'Token không hợp lệ!';
            header('Location: /admin/them-nhan-vien');
            return;
        }
        
        // Validate dữ liệu
        $errors = $this->validateNhanVien($_POST);
        if (!empty($errors)) {
            $_SESSION['error_message'] = implode('<br>', $errors);
            header('Location: /admin/them-nhan-vien');
            return;
        }
        
        // Kiểm tra email/username đã tồn tại
        if ($this->nhanVienModel->getByEmail($_POST['email'])) {
            $_SESSION['error_message'] = 'Email đã tồn tại!';
            header('Location: /admin/them-nhan-vien');
            return;
        }
        
        if ($this->nhanVienModel->getByUsername($_POST['username'])) {
            $_SESSION['error_message'] = 'Tên đăng nhập đã tồn tại!';
            header('Location: /admin/them-nhan-vien');
            return;
        }
        
        // Tạo dữ liệu
        $data = [
            'ho_ten' => $_POST['ho_ten'],
            'email' => $_POST['email'],
            'so_dien_thoai' => $_POST['so_dien_thoai'],
            'chuc_vu' => $_POST['chuc_vu'],
            'dia_chi' => $_POST['dia_chi'] ?? '',
            'username' => $_POST['username'],
            'mat_khau' => password_hash($_POST['mat_khau'], PASSWORD_DEFAULT),
            'trang_thai' => $_POST['trang_thai'] ?? 1,
            'ngay_tao' => date('Y-m-d H:i:s')
        ];
        
        if ($this->nhanVienModel->create($data)) {
            $_SESSION['success_message'] = 'Thêm nhân viên thành công!';
        } else {
            $_SESSION['error_message'] = 'Có lỗi xảy ra khi thêm nhân viên!';
        }
        
        header('Location: /admin/them-nhan-vien');
    }
    
    // GET: /admin/sua-nhan-vien?id=123
    public function edit()
    {
        $id = $_GET['id'] ?? 0;
        $nhanvien = $this->nhanVienModel->getById($id);
        
        if (!$nhanvien) {
            $_SESSION['error_message'] = 'Nhân viên không tồn tại!';
            header('Location: /admin/quan-ly-nhan-vien');
            return;
        }
        
        echo $this->blade->render('admin-views.QuanLyNhanVien.SuaNhanVien', [
            'nhanvien' => $nhanvien,
            'activePage' => 'nhanvien'
        ]);
    }
    
    // POST: /admin/sua-nhan-vien
    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /admin/quan-ly-nhan-vien');
            return;
        }
        
        $id = $_POST['id'] ?? 0;
        $nhanvien = $this->nhanVienModel->getById($id);
        
        if (!$nhanvien) {
            $_SESSION['error_message'] = 'Nhân viên không tồn tại!';
            header('Location: /admin/quan-ly-nhan-vien');
            return;
        }
        
        // Validate CSRF token
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            $_SESSION['error_message'] = 'Token không hợp lệ!';
            header('Location: /admin/sua-nhan-vien?id=' . $id);
            return;
        }
        
        // Chuẩn bị dữ liệu cập nhật
        $data = [
            'ho_ten' => $_POST['ho_ten'],
            'email' => $_POST['email'],
            'so_dien_thoai' => $_POST['so_dien_thoai'],
            'chuc_vu' => $_POST['chuc_vu'],
            'dia_chi' => $_POST['dia_chi'] ?? '',
            'trang_thai' => $_POST['trang_thai'] ?? 1
        ];
        
        // Cập nhật mật khẩu nếu có
        if (!empty($_POST['mat_khau_moi'])) {
            if ($_POST['mat_khau_moi'] !== $_POST['xac_nhan_mat_khau']) {
                $_SESSION['error_message'] = 'Mật khẩu xác nhận không khớp!';
                header('Location: /admin/sua-nhan-vien?id=' . $id);
                return;
            }
            $data['mat_khau'] = password_hash($_POST['mat_khau_moi'], PASSWORD_DEFAULT);
        }
        
        if ($this->nhanVienModel->update($id, $data)) {
            $_SESSION['success_message'] = 'Cập nhật nhân viên thành công!';
        } else {
            $_SESSION['error_message'] = 'Có lỗi xảy ra khi cập nhật!';
        }
        
        header('Location: /admin/sua-nhan-vien?id=' . $id);
    }
    
    // GET: /admin/xoa-nhan-vien?id=123
    public function delete()
    {
        $id = $_GET['id'] ?? 0;
        $nhanvien = $this->nhanVienModel->getById($id);
        
        echo $this->blade->render('admin-views.QuanLyNhanVien.XoaNhanVien', [
            'nhanvien' => $nhanvien,
            'activePage' => 'nhanvien'
        ]);
    }
    
    // POST: /admin/xoa-nhan-vien
    public function destroy()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /admin/quan-ly-nhan-vien');
            return;
        }
        
        $id = $_POST['id'] ?? 0;
        
        // Validate CSRF token
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            $_SESSION['error_message'] = 'Token không hợp lệ!';
            header('Location: /admin/xoa-nhan-vien?id=' . $id);
            return;
        }
        
        if ($this->nhanVienModel->delete($id)) {
            $_SESSION['success_message'] = 'Xóa nhân viên thành công!';
        } else {
            $_SESSION['error_message'] = 'Có lỗi xảy ra khi xóa nhân viên!';
        }
        
        header('Location: /admin/xoa-nhan-vien?id=' . $id);
    }
    
    // Validate dữ liệu nhân viên
    private function validateNhanVien($data, $editId = null)
    {
        $errors = [];
        
        if (empty($data['ho_ten'])) {
            $errors[] = 'Họ tên không được để trống';
        }
        
        if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Email không hợp lệ';
        }
        
        if (empty($data['so_dien_thoai'])) {
            $errors[] = 'Số điện thoại không được để trống';
        }
        
        if (empty($data['chuc_vu'])) {
            $errors[] = 'Chức vụ không được để trống';
        }
        
        // Kiểm tra khi thêm mới
        if (!$editId) {
            if (empty($data['username'])) {
                $errors[] = 'Tên đăng nhập không được để trống';
            }
            
            if (empty($data['mat_khau']) || strlen($data['mat_khau']) < 6) {
                $errors[] = 'Mật khẩu phải có ít nhất 6 ký tự';
            }
            
            if ($data['mat_khau'] !== $data['xac_nhan_mat_khau']) {
                $errors[] = 'Mật khẩu xác nhận không khớp';
            }
        }
        
        return $errors;
    }
}