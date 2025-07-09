<?php

namespace App\Controllers;

use Jenssegers\Blade\Blade;
use App\Models\LoaiVe;
use App\Helpers\AuthHelper;
use Exception;

class QuanLyLoaiVeController
{
    private $loaiVeModel;
    private $blade;

    public function __construct()
    {
        $this->loaiVeModel = new LoaiVe();
        $this->blade = new Blade(
            realpath(__DIR__ . '/../Views'),
            realpath(__DIR__ . '/../../cache')
        );
    }

    /**
     * Hiển thị danh sách loại vé
     */
    public function quanLyLoaiVe()
    {
        AuthHelper::checkAccess('admin_only');
        
        try {
            $search = $_GET['search'] ?? '';
            $page = max(1, (int)($_GET['page'] ?? 1));
            $limit = 10;
            $offset = ($page - 1) * $limit;
            
            $filters = ['search' => $search];
            
            $loaiVeList = $this->loaiVeModel->getAllLoaiVe($limit, $offset, $filters);
            $totalLoaiVe = $this->loaiVeModel->countLoaiVe($filters);
            $totalPages = max(1, ceil($totalLoaiVe / $limit));
            
            echo $this->blade->render('admin-views.LoaiVe.QuanLyLoaiVe', [
                'activePage' => 'LoaiVe',
                'loaiVeList' => $loaiVeList,
                'currentPage' => $page,
                'totalPages' => $totalPages,
                'totalLoaiVe' => $totalLoaiVe,
                'filters' => $filters
            ]);
            
        } catch (Exception $e) {
            error_log("Error in QuanLyLoaiVeController@index: " . $e->getMessage());
            $_SESSION['error_message'] = 'Có lỗi xảy ra khi tải danh sách loại vé';
            header('Location: /');
            exit;
        }
    }

    /**
     * Hiển thị form thêm loại vé
     */
    public function create()
    {
        AuthHelper::checkAccess('admin_only');
        
        echo $this->blade->render('admin-views.LoaiVe.ThemLoaiVe', [
            'activePage' => 'LoaiVe'
        ]);
    }

    /**
     * Xử lý thêm loại vé
     */
    public function store()
    {
        AuthHelper::checkAccess('admin_only');
        
        try {
            $data = [
                'ma_loai_ve' => trim($_POST['ma_loai_ve'] ?? ''),
                'ten_loai_ve' => trim($_POST['ten_loai_ve'] ?? ''),
                'gia_tien' => (int)($_POST['gia_tien'] ?? 0)
            ];

            // Validate
            if (empty($data['ma_loai_ve'])) {
                throw new Exception('Mã loại vé không được để trống');
            }
            
            if (empty($data['ten_loai_ve'])) {
                throw new Exception('Tên loại vé không được để trống');
            }
            
            if ($data['gia_tien'] <= 0) {
                throw new Exception('Giá tiền phải lớn hơn 0');
            }

            // Kiểm tra mã loại vé đã tồn tại
            if ($this->loaiVeModel->checkLoaiVeExists($data['ma_loai_ve'])) {
                throw new Exception('Mã loại vé đã tồn tại');
            }

            $result = $this->loaiVeModel->createLoaiVe($data);
            
            if ($result) {
                $_SESSION['success_message'] = 'Thêm loại vé thành công';
                header('Location: /quan-ly-loai-ve');
            } else {
                throw new Exception('Có lỗi xảy ra khi thêm loại vé');
            }
            
        } catch (Exception $e) {
            $_SESSION['error_message'] = $e->getMessage();
            header('Location: /them-loai-ve');
        }
        exit;
    }

    /**
     * Hiển thị form sửa loại vé
     */
    public function edit()
    {
        AuthHelper::checkAccess('admin_only');
        
        try {
            $id = $_GET['id'] ?? '';
            
            if (empty($id)) {
                throw new Exception('Mã loại vé không hợp lệ');
            }
            
            $loaiVe = $this->loaiVeModel->getLoaiVeById($id);
            
            if (!$loaiVe) {
                throw new Exception('Không tìm thấy loại vé');
            }
            
            echo $this->blade->render('admin-views.LoaiVe.SuaLoaiVe', [
                'activePage' => 'LoaiVe',
                'loaiVe' => $loaiVe
            ]);
            
        } catch (Exception $e) {
            $_SESSION['error_message'] = $e->getMessage();
            header('Location: /quan-ly-loai-ve');
            exit;
        }
    }

    /**
     * Xử lý cập nhật loại vé
     */
    public function update()
    {
        AuthHelper::checkAccess('admin_only');
        
        try {
            $id = $_POST['id'] ?? '';
            $data = [
                'ten_loai_ve' => trim($_POST['ten_loai_ve'] ?? ''),
                'gia_tien' => (int)($_POST['gia_tien'] ?? 0)
            ];

            // Validate
            if (empty($id)) {
                throw new Exception('Mã loại vé không hợp lệ');
            }
            
            if (empty($data['ten_loai_ve'])) {
                throw new Exception('Tên loại vé không được để trống');
            }
            
            if ($data['gia_tien'] <= 0) {
                throw new Exception('Giá tiền phải lớn hơn 0');
            }

            $result = $this->loaiVeModel->updateLoaiVe($id, $data);
            
            if ($result) {
                $_SESSION['success_message'] = 'Cập nhật loại vé thành công';
            } else {
                throw new Exception('Có lỗi xảy ra khi cập nhật loại vé');
            }
            
        } catch (Exception $e) {
            $_SESSION['error_message'] = $e->getMessage();
        }
        
        header('Location: /quan-ly-loai-ve');
        exit;
    }

    /**
     * Xóa loại vé
     */
    public function delete()
    {
        AuthHelper::checkAccess('admin_only');
        
        try {
            $id = $_POST['id'] ?? '';
            
            if (empty($id)) {
                throw new Exception('Mã loại vé không hợp lệ');
            }
            
            $result = $this->loaiVeModel->deleteLoaiVe($id);
            
            if ($result) {
                $_SESSION['success_message'] = 'Xóa loại vé thành công';
            } else {
                throw new Exception('Có lỗi xảy ra khi xóa loại vé');
            }
            
        } catch (Exception $e) {
            $_SESSION['error_message'] = $e->getMessage();
        }
        
        header('Location: /quan-ly-loai-ve');
        exit;
    }
}