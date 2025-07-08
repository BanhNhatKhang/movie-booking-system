<?php

namespace App\Controllers;

use App\Models\Poster;
use App\Models\UuDaiTrangChu;
use Jenssegers\Blade\Blade;
use App\Helpers\AuthHelper;
use Exception;

class QuanLyTrangChuController
{
    private $posterModel;
    private $uuDaiModel;
    private $blade;

    public function __construct()
    {
        $this->posterModel = new Poster();
        $this->uuDaiModel = new UuDaiTrangChu();
        $this->blade = new Blade(
            realpath(__DIR__ . '/../Views'),
            realpath(__DIR__ . '/../../cache')
        );
    }

    /**
     * Hiển thị trang quản lý trang chủ (cả poster và ưu đãi)
     */
    public function trangChu()
    {
        AuthHelper::checkAccess('admin_only');
        try {
            // Lấy danh sách poster
            $posters = $this->posterModel->getAll();
            
            // Lấy danh sách ưu đãi
            $uuDaiList = $this->uuDaiModel->getAllUuDai();
            
            echo $this->blade->render('admin-views.TrangChu.QuanLyTrangChu', [
                'posters' => $posters,
                'uuDaiList' => $uuDaiList,
                'activePage' => 'home',
            ]);
        } catch (Exception $e) {
            error_log("Error in trangChu: " . $e->getMessage());
            echo $this->blade->render('admin-views.TrangChu.QuanLyTrangChu', [
                'posters' => [],
                'uuDaiList' => [],
                'activePage' => 'quan-ly-trang-chu',
                'error' => 'Không thể tải dữ liệu'
            ]);
        }
    }
    
    public function themPoster()
    {
        AuthHelper::checkAccess('admin_only');
        $newId = $this->posterModel->generateNewId();
        echo $this->blade->render('admin-views.TrangChu.ThemPoster', [
            'activePage' => 'quan-ly-trang-chu',
            'newId' => $newId
        ]);
    }

    public function luuPoster()
    {
        AuthHelper::checkAccess('admin_only');
        // $this->checkAdminAuth(); // Kiểm tra quyền truy cập admin
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $file = $_FILES['anhPoster'];
            $imgPath = null;
            if ($file && $file['error'] === UPLOAD_ERR_OK) {
                $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                $imgPath = '/static/uploads/posters/' . uniqid('poster_') . '.' . $ext;
                
                // Tạo thư mục nếu chưa có
                $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/static/uploads/posters/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                
                move_uploaded_file($file['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . $imgPath);
            }
            
            $this->posterModel->create([
                'pt_maposter' => $_POST['pt_maposter'],
                'pt_anhposter' => $imgPath
            ]);
            header('Location: /quan-ly-trang-chu?success=add_poster');
        }
    }
    public function suaPoster()
    {
        AuthHelper::checkAccess('admin_only');
        $id = $_GET['id'] ?? '';
        $poster = $this->posterModel->getById($id);
        echo $this->blade->render('admin-views.TrangChu.SuaPoster', [
            'poster' => $poster,
            'activePage' => 'quan-ly-trang-chu'
        ]);
    }

    public function capNhatPoster()
    {
        AuthHelper::checkAccess('admin_only');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['pt_maposter'];
            $file = $_FILES['anhPoster'];
            $imgPath = $_POST['old_img'];
            
            if ($file && $file['error'] === UPLOAD_ERR_OK) {
                $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                $imgPath = '/static/uploads/posters/' . uniqid('poster_') . '.' . $ext;
                
                $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/static/uploads/posters/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                
                move_uploaded_file($file['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . $imgPath);
            }
            
            $this->posterModel->update($id, ['pt_anhposter' => $imgPath]);
            header('Location: /quan-ly-trang-chu?success=update_poster');
        }
    }

    public function xoaPoster()
    {
        AuthHelper::checkAccess('admin_only');
        $id = $_GET['id'] ?? '';
        $db = \App\Core\Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT COUNT(*) FROM phim WHERE p_maposter = ?");
        $stmt->execute([$id]);
        $count = $stmt->fetchColumn();
        
        if ($count > 0) {
            header('Location: /quan-ly-trang-chu?error=poster_in_use');
            exit;
        }
        
        // Lấy đường dẫn ảnh poster để xóa file
        $poster = $this->posterModel->getById($id);
        if ($poster && !empty($poster['pt_anhposter'])) {
            $filePath = $_SERVER['DOCUMENT_ROOT'] . $poster['pt_anhposter'];
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }
        
        $this->posterModel->delete($id);
        header('Location: /quan-ly-trang-chu?success=delete_poster');
    }


    /**
     * Hiển thị form thêm ưu đãi
     */
    public function themUuDai()
    {
        AuthHelper::checkAccess('admin_only');
        echo $this->blade->render('admin-views.TrangChu.ThemUuDaiHome', [
            'activePage' => 'quan-ly-trang-chu'
        ]);
    }

    /**
     * Xử lý thêm ưu đãi
     */
    public function luuUuDai()
    {
        AuthHelper::checkAccess('admin_only');
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                header('Location: /them-uu-dai-home');
                exit;
            }
            if (!isset($_FILES['anhUuDai']) || $_FILES['anhUuDai']['error'] !== UPLOAD_ERR_OK) {
                header('Location: /them-uu-dai-home?error=no_image');
                exit;
            }
    
            // Xử lý upload ảnh
            $imagePath = $this->handleImageUpload($_FILES['anhUuDai']);
            if (!$imagePath) {
                header('Location: /them-uu-dai-home?error=upload_failed');
                exit;
            }
    
            // Tạo mã ưu đãi mới
            $maUuDai = $this->uuDaiModel->generateNewId();
    
            // Thêm vào database - chỉ cần 2 trường
            $data = [
                'udtc_mauudai' => $maUuDai,
                'udtc_anhuudai' => $imagePath
            ];
    
            if ($this->uuDaiModel->createUuDai($data)) {
                header('Location: /quan-ly-trang-chu?success=add_uudai');
                exit;
            } else {
                header('Location: /them-uu-dai-home?error=create_failed');
                exit;
            }
        } catch (Exception $e) {
            error_log("Error in luuUuDai: " . $e->getMessage());
            header('Location: /them-uu-dai-home?error=system_error');
            exit;
        }
    }

    /**
     * Hiển thị form sửa ưu đãi
     */
    public function suaUuDai()
    {
        AuthHelper::checkAccess('admin_only');
        try {
            $id = $_GET['id'] ?? '';
            if (empty($id)) {
                header('Location: /quan-ly-trang-chu?error=invalid_id');
                exit;
            }

            $uuDai = $this->uuDaiModel->getUuDaiById($id);
            if (!$uuDai) {
                header('Location: /quan-ly-trang-chu?error=not_found');
                exit;
            }

            echo $this->blade->render('admin-views.TrangChu.SuaUuDaiHome', [
                'activePage' => 'quan-ly-trang-chu',
                'uuDai' => $uuDai
            ]);
        } catch (Exception $e) {
            error_log("Error in suaUuDai: " . $e->getMessage());
            header('Location: /quan-ly-trang-chu?error=system_error');
            exit;
        }
    }

    /**
     * Xử lý cập nhật ưu đãi
     */
    public function capNhatUuDai()
    {
        AuthHelper::checkAccess('admin_only');
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                header('Location: /quan-ly-trang-chu');
                exit;
            }
    
            $id = $_POST['id'] ?? '';
    
            if (empty($id)) {
                header('Location: /quan-ly-trang-chu?error=invalid_id');
                exit;
            }
    
            // Lấy thông tin hiện tại
            $currentUuDai = $this->uuDaiModel->getUuDaiById($id);
            if (!$currentUuDai) {
                header('Location: /quan-ly-trang-chu?error=not_found');
                exit;
            }
    
            // Xử lý upload ảnh mới (nếu có)
            $imagePath = $currentUuDai['udtc_anhuudai']; // Giữ ảnh cũ
            if (isset($_FILES['anhUuDai']) && $_FILES['anhUuDai']['error'] === UPLOAD_ERR_OK) {
                $newImagePath = $this->handleImageUpload($_FILES['anhUuDai']);
                if ($newImagePath) {
                    // Xóa ảnh cũ
                    if (!empty($imagePath) && file_exists($_SERVER['DOCUMENT_ROOT'] . $imagePath)) {
                        unlink($_SERVER['DOCUMENT_ROOT'] . $imagePath);
                    }
                    $imagePath = $newImagePath;
                }
            }
    
            // ✅ Cập nhật database - chỉ cập nhật ảnh
            $data = [
                'udtc_anhuudai' => $imagePath
            ];
    
            if ($this->uuDaiModel->updateUuDai($id, $data)) {
                header('Location: /quan-ly-trang-chu?success=update_uudai');
                exit;
            } else {
                header('Location: /sua-uu-dai-home?id=' . $id . '&error=update_failed');
                exit;
            }
        } catch (Exception $e) {
            error_log("Error in capNhatUuDai: " . $e->getMessage());
            header('Location: /quan-ly-trang-chu?error=system_error');
            exit;
        }
    }

    /**
     * Xóa ưu đãi
     */
    public function xoaUuDai()
    {
        AuthHelper::checkAccess('admin_only');
        try {
            $id = $_GET['id'] ?? '';
            if (empty($id)) {
                header('Location: /quan-ly-trang-chu?error=invalid_id');
                exit;
            }

            // Lấy thông tin ưu đãi để xóa ảnh
            $uuDai = $this->uuDaiModel->getUuDaiById($id);
            if (!$uuDai) {
                header('Location: /quan-ly-trang-chu?error=not_found');
                exit;
            }

            // Xóa khỏi database
            if ($this->uuDaiModel->deleteUuDai($id)) {
                // Xóa file ảnh
                if (!empty($uuDai['udtc_anhuudai']) && file_exists($_SERVER['DOCUMENT_ROOT'] . $uuDai['udtc_anhuudai'])) {
                    unlink($_SERVER['DOCUMENT_ROOT'] . $uuDai['udtc_anhuudai']);
                }
                
                header('Location: /quan-ly-trang-chu?success=delete_uudai');
                exit;
            } else {
                header('Location: /quan-ly-trang-chu?error=delete_failed');
                exit;
            }
        } catch (Exception $e) {
            error_log("Error in xoaUuDai: " . $e->getMessage());
            header('Location: /quan-ly-trang-chu?error=system_error');
            exit;
        }
    }

    /**
     * Xử lý upload ảnh ưu đãi
     */
    private function handleImageUpload($file)
    {
        AuthHelper::checkAccess('admin_only');
        try {
            $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
            $maxSize = 5 * 1024 * 1024; // 5MB

            if (!in_array($file['type'], $allowedTypes)) {
                return false;
            }

            if ($file['size'] > $maxSize) {
                return false;
            }

            $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/static/uploads/uudai/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
            $filename = 'uudai_' . uniqid() . '_' . date('Ymd') . '.' . $extension;
            $uploadPath = $uploadDir . $filename;

            if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
                return '/static/uploads/uudai/' . $filename;
            }

            return false;
        } catch (Exception $e) {
            error_log("Error in handleImageUpload: " . $e->getMessage());
            return false;
        }
    }
}