<?php
// filepath: c:\mysites\ct27501-project-BanhNhatKhang-1\app\Controllers\QuanLyNhanVienController.php

namespace App\Controllers;

use Jenssegers\Blade\Blade;
use App\Models\NguoiDung;
use App\Helpers\AuthHelper;

class QuanLyNhanVienController
{
    public function quanLyNhanVien()
    {
        AuthHelper::checkAccess('admin_only');
        
        $search = $_GET['q'] ?? '';
        $status = $_GET['status'] ?? '';
        $sort = $_GET['sort'] ?? 'newest';
        $page = (int)($_GET['page'] ?? 1);
        
        $nguoiDungModel = new NguoiDung();
        $nhanViens = $nguoiDungModel->getAllWithFilterPaginatedByRole($search, 'admin', $status, $page, 15, $sort); 
        $totalNhanViens = $nguoiDungModel->getTotalCountByRole($search, 'admin', $status);
        $totalPages = ceil($totalNhanViens / 15);
        
        $blade = new Blade(
            realpath(__DIR__ . '/../Views'),
            realpath(__DIR__ . '/../../cache')
        );
        
        echo $blade->render('admin-views.QuanLyNhanVien.QuanLyNhanVien', [
            'activePage' => 'NhanVien',
            'nhanViens' => $nhanViens,
            'search' => $search,
            'status' => $status,
            'sort' => $sort, 
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalNhanViens' => $totalNhanViens
        ]);
    }

    // GET: /chi-tiet-nhan-vien
    public function chiTietNhanVien()
    {
        AuthHelper::checkAccess('admin_only');
        $blade = new Blade(
            realpath(__DIR__ . '/../Views'),
            realpath(__DIR__ . '/../../cache')
        );
        $id = $_GET['id'] ?? '';
        if (empty($id)) {
            $_SESSION['error_message'] = 'ID nhân viên không hợp lệ!';
            header('Location: /quan-ly-nhan-vien');
            exit;
        }
        
        $nguoiDungModel = new NguoiDung();
        $nhanVien = $nguoiDungModel->getById($id);
        
        if (!$nhanVien || $nhanVien['nd_role'] !== 'admin') {
            $_SESSION['error_message'] = 'Không tìm thấy nhân viên!';
            header('Location: /quan-ly-nhan-vien');
            exit;
        }

        $memberInfo = $nguoiDungModel->getMemberInfo($id);
        
        // Lấy thống kê nhân viên
        $workStats = $this->getNhanVienStats($id);
        
        echo $blade->render('admin-views.QuanLyNhanVien.ChiTietNhanVien', [
            'activePage' => 'NhanVien',
            'nhanVien' => $nhanVien,
            'memberInfo' => $memberInfo,
            'workStats' => $workStats
        ]);
    }

    // GET: /sua-nhan-vien
    public function suaNhanVien()
    {

        AuthHelper::checkAccess('admin_only');
        $blade = new Blade(
            realpath(__DIR__ . '/../Views'),
            realpath(__DIR__ . '/../../cache')
        );
        $id = $_GET['id'] ?? '';
        if (empty($id)) {
            $_SESSION['error_message'] = 'ID nhân viên không hợp lệ!';
            header('Location: /quan-ly-nhan-vien');
            exit;
        }
        
        $nguoiDungModel = new NguoiDung();
        $nhanVien = $nguoiDungModel->getById($id);
        
        if (!$nhanVien || $nhanVien['nd_role'] !== 'admin') {
            $_SESSION['error_message'] = 'Không tìm thấy nhân viên!';
            header('Location: /quan-ly-nhan-vien');
            exit;
        }
        
        echo $blade->render('admin-views.QuanLyNhanVien.SuaNhanVien', [
            'activePage' => 'NhanVien',
            'nhanVien' => $nhanVien
        ]);
    }

    // POST: /sua-nhan-vien
    public function capNhatNhanVien()
    {
        AuthHelper::checkAccess('admin_only');
        
        $id = $_POST['id'] ?? '';
        
        $data = [
            'nd_hoten' => $_POST['nd_hoten'] ?? '',
            'nd_email' => $_POST['nd_email'] ?? '',
            'nd_sdt' => $_POST['nd_sdt'] ?? '',
            'nd_gioitinh' => $_POST['nd_gioitinh'] ?? null,
            'nd_ngaysinh' => $_POST['nd_ngaysinh'] ?? null,
            'nd_cccd' => $_POST['nd_cccd'] ?? '',
            'nd_role' => $_POST['nd_role'] ?? 'admin',
            'nd_trangthai' => $_POST['nd_trangthai'] ?? 'active'
        ];
        
        // Validate
        if (empty($data['nd_hoten']) || empty($data['nd_email'])) {
            $_SESSION['error_message'] = 'Vui lòng điền đầy đủ thông tin bắt buộc!';
            header('Location: /sua-nhan-vien?id=' . $id);
            exit;
        }
        
        $nguoiDungModel = new NguoiDung();
        
        $currentUser = $nguoiDungModel->getById($id);
        $oldRole = $currentUser['nd_role'] ?? 'admin';
        $newRole = $data['nd_role'];
        
        if ($nguoiDungModel->updatePartial($id, $data)) {
            $_SESSION['success_message'] = 'Cập nhật thông tin nhân viên thành công!';
            if ($oldRole === 'admin' && $newRole === 'user') {
                // Đổi từ admin → user: chuyển về quản lý người dùng
                header('Location: /quan-ly-nhan-vien');
            } elseif ($oldRole === 'user' && $newRole === 'admin') {
                // Đổi từ user → admin: ở lại quản lý nhân viên
                header('Location: /sua-nhan-vien?id=' . $id);
            } else {
                // Không đổi role: ở lại trang hiện tại
                header('Location: /quan-ly-nhan-vien');
            }
        } else {
            $_SESSION['error_message'] = 'Có lỗi xảy ra khi cập nhật!';
            header('Location: /sua-nhan-vien?id=' . $id);
        }
        exit;
    }

    // GET: /khoa-nhan-vien
    public function khoaNhanVien()
    {
        AuthHelper::checkAccess('admin_only');
        
        $id = $_GET['id'] ?? '';
        if (empty($id)) {
            $_SESSION['error_message'] = 'ID nhân viên không hợp lệ!';
            header('Location: /quan-ly-nhan-vien');
            exit;
        }
        
        // Không cho phép khóa chính mình
        if ($id === $_SESSION['user_id']) {
            $_SESSION['error_message'] = 'Không thể khóa tài khoản của chính mình!';
            header('Location: /quan-ly-nhan-vien');
            exit;
        }
        
        $nguoiDungModel = new NguoiDung();
        
        if ($nguoiDungModel->toggleStatus($id)) {
            $_SESSION['success_message'] = 'Đã thay đổi trạng thái nhân viên!';
        } else {
            $_SESSION['error_message'] = 'Có lỗi xảy ra!';
        }
        
        header('Location: /quan-ly-nhan-vien');
        exit;
    }

    private function getNhanVienStats($id)
    {
        return [
            'total_tasks' => 0,
            'completed_tasks' => 0,
            'pending_tasks' => 0,
            'join_date' => date('Y-m-d')
        ];
    }
    public function themNhanVien()
    {
        AuthHelper::checkAccess('admin_only');
        
        $blade = new Blade(
            realpath(__DIR__ . '/../Views'),
            realpath(__DIR__ . '/../../cache')
        );
        
        echo $blade->render('admin-views.QuanLyNhanVien.ThemNhanVien', [
            'activePage' => 'NhanVien'
        ]);
    }

    public function storeNhanVien()
    {
        AuthHelper::checkAccess('admin_only');
        
        // Lưu form data để giữ lại khi có lỗi
        $_SESSION['form_data'] = $_POST;
        
        $data = [
            'nd_hoten' => $_POST['nd_hoten'] ?? '',
            'nd_email' => $_POST['nd_email'] ?? '',
            'nd_tendangnhap' => $_POST['nd_tendangnhap'] ?? '',
            'nd_sdt' => $_POST['nd_sdt'] ?? '',
            'nd_gioitinh' => $_POST['nd_gioitinh'] ?? null,
            'nd_ngaysinh' => $_POST['nd_ngaysinh'] ?? null,
            'nd_cccd' => $_POST['nd_cccd'] ?? '',
            'nd_role' => 'admin', 
            'nd_trangthai' => 'active',
            'nd_ngaydangky' => date('Y-m-d')
        ];
        
        if (empty($data['nd_hoten']) || empty($data['nd_email']) || empty($data['nd_tendangnhap'])) {
            $_SESSION['error_message'] = 'Vui lòng điền đầy đủ thông tin bắt buộc!';
            header('Location: /them-nhan-vien');
            exit;
        }
        
        if (empty($_POST['nd_matkhau']) || strlen($_POST['nd_matkhau']) < 6) {
            $_SESSION['error_message'] = 'Mật khẩu phải có ít nhất 6 ký tự!';
            header('Location: /them-nhan-vien');
            exit;
        }
        
        if ($_POST['nd_matkhau'] !== $_POST['xac_nhan_mat_khau']) {
            $_SESSION['error_message'] = 'Mật khẩu xác nhận không khớp!';
            header('Location: /them-nhan-vien');
            exit;
        }
        
        if (!empty($data['nd_ngaysinh'])) {
            $birthDate = new \DateTime($data['nd_ngaysinh']);
            $today = new \DateTime();
            $age = $today->diff($birthDate)->y;
            
            if ($age < 18) {
                $_SESSION['error_message'] = 'Nhân viên phải đủ 18 tuổi trở lên!';
                header('Location: /them-nhan-vien');
                exit;
            }
            
            if ($age > 65) {
                $_SESSION['error_message'] = 'Tuổi nhân viên không được vượt quá 65!';
                header('Location: /them-nhan-vien');
                exit;
            }
        }
        
        $nguoiDungModel = new NguoiDung();
        
        $checkUsername = $nguoiDungModel->checkExists('nd_tendangnhap', $data['nd_tendangnhap']);
        if ($checkUsername) {
            $_SESSION['error_message'] = 'Tên đăng nhập đã tồn tại!';
            header('Location: /them-nhan-vien');
            exit;
        }
        
        $checkEmail = $nguoiDungModel->checkExists('nd_email', $data['nd_email']);
        if ($checkEmail) {
            $_SESSION['error_message'] = 'Email đã được sử dụng!';
            header('Location: /them-nhan-vien');
            exit;
        }
        
        if (!empty($data['nd_cccd'])) {
            if (!preg_match('/^\d{12}$/', $data['nd_cccd'])) {
                $_SESSION['error_message'] = 'CCCD phải có đúng 12 số!';
                header('Location: /them-nhan-vien');
                exit;
            }
            
            // Kiểm tra trùng CCCD
            $checkCCCD = $nguoiDungModel->checkExists('nd_cccd', $data['nd_cccd']);
            if ($checkCCCD) {
                $_SESSION['error_message'] = 'CCCD đã được sử dụng!';
                header('Location: /them-nhan-vien');
                exit;
            }
        }
        
        if (!empty($data['nd_sdt'])) {
            if (!preg_match('/^\d{10}$/', $data['nd_sdt'])) {
                $_SESSION['error_message'] = 'Số điện thoại phải có đúng 10 số!';
                header('Location: /them-nhan-vien');
                exit;
            }
            
            // Kiểm tra trùng SĐT
            $checkSDT = $nguoiDungModel->checkExists('nd_sdt', $data['nd_sdt']);
            if ($checkSDT) {
                $_SESSION['error_message'] = 'Số điện thoại đã được sử dụng!';
                header('Location: /them-nhan-vien');
                exit;
            }
        }
        
        $data['nd_matkhau'] = password_hash($_POST['nd_matkhau'], PASSWORD_DEFAULT);
        
        $data['nd_id'] = $nguoiDungModel->generateRandomId();
        
        if (empty($data['nd_id'])) {
            $_SESSION['error_message'] = 'Không thể tạo ID nhân viên! Vui lòng thử lại.';
            header('Location: /them-nhan-vien');
            exit;
        }
        
        if ($nguoiDungModel->create($data)) {
            // Xóa form_data sau khi thành công
            unset($_SESSION['form_data']);
            $_SESSION['success_message'] = 'Tạo nhân viên mới thành công! ID: ' . $data['nd_id'] . ' - Username: ' . $data['nd_tendangnhap'];
            header('Location: /quan-ly-nhan-vien');
        } else {
            $_SESSION['error_message'] = 'Có lỗi xảy ra khi tạo nhân viên! Vui lòng thử lại.';
            header('Location: /them-nhan-vien');
        }
        exit;
    }
}