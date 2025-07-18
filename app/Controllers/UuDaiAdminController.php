<?php

namespace App\Controllers;

use App\Models\UuDai;
use App\Models\UuDaiTrangChu;
use Jenssegers\Blade\Blade;
use App\Helpers\AuthHelper;
use Exception;

class UuDaiAdminController
{
    private $uuDaiModel;
    private $uuDaiTrangChuModel;
    private $blade;
    private $uploadsPath;

    public function __construct()
    {
        $this->uuDaiModel = new UuDai();
        $this->uuDaiTrangChuModel = new UuDaiTrangChu();
        $this->blade = new Blade(
            realpath(__DIR__ . '/../Views'),
            realpath(__DIR__ . '/../../cache')
        );
        $this->uploadsPath = realpath(__DIR__ . '/../../public/static/uploads/uudai');
    }

    /**
     * Hiển thị danh sách ưu đãi
     */
    public function uuDai()
    {
        AuthHelper::checkAccess('admin_only');
        try {
            // Cập nhật trạng thái theo thời gian
            $this->uuDaiModel->updateStatusByDate();

            // Lấy filters từ GET request
            $filters = [
                'ten_uu_dai' => $_GET['ten_uu_dai'] ?? '',
                'loai_uu_dai' => $_GET['loai_uu_dai'] ?? '',
                'trang_thai' => $_GET['trang_thai'] ?? ''
            ];

            // Tìm kiếm hoặc lấy tất cả
            if (!empty($filters['ten_uu_dai']) || !empty($filters['loai_uu_dai']) || !empty($filters['trang_thai'])) {
                $uuDaiList = $this->uuDaiModel->searchUuDai($filters);
            } else {
                $uuDaiList = $this->uuDaiModel->getAllUuDai();
            }

            echo $this->blade->render('admin-views.UuDai.QuanLyUuDai', [
                'activePage' => 'uudai',
                'uuDaiList' => $uuDaiList,
                'filters' => $filters,
                'success' => $_GET['success'] ?? null,
                'error' => $_GET['error'] ?? null
            ]);
        } catch (Exception $e) {
            error_log("Error in UuDaiAdminController uuDai: " . $e->getMessage());
            
            echo $this->blade->render('admin-views.UuDai.QuanLyUuDai', [
                'uuDaiList' => [],
                'filters' => [],
                'error' => 'Không thể tải dữ liệu ưu đãi'
            ]);
        }
    }

    /**
     * Hiển thị form thêm ưu đãi
     */
    public function themUuDai()
    {
        AuthHelper::checkAccess('admin_only');
        try {
            echo $this->blade->render('admin-views.UuDai.ThemUuDai', [
                'activePage' => 'uudai',
                'error' => $_GET['error'] ?? null,
                'oldData' => $_SESSION['old_data'] ?? [] // ✅ Truyền old data thay vì dùng old() function
            ]);
            
            // Clear old data after rendering
            unset($_SESSION['old_data']);
            
        } catch (Exception $e) {
            error_log("Error in UuDaiAdminController themUuDai: " . $e->getMessage());
            header('Location: /quan-ly-uu-dai?error=' . urlencode('Không thể tải trang thêm ưu đãi'));
            exit;
        }
    }

    /**
     * Xử lý thêm ưu đãi mới
     */
    public function storeUuDai()
    {
        AuthHelper::checkAccess('admin_only');
        try {
            // ✅ Validate file size trước khi xử lý
            if (isset($_FILES['anhUuDai']) && $_FILES['anhUuDai']['error'] !== UPLOAD_ERR_NO_FILE) {
                
                // Kiểm tra lỗi upload
                switch ($_FILES['anhUuDai']['error']) {
                    case UPLOAD_ERR_INI_SIZE:
                    case UPLOAD_ERR_FORM_SIZE:
                        $_SESSION['old_data'] = $_POST;
                        header('Location: /them-uu-dai?error=' . urlencode('File ảnh quá lớn. Vui lòng chọn file nhỏ hơn 5MB'));
                        exit;
                        
                    case UPLOAD_ERR_PARTIAL:
                        $_SESSION['old_data'] = $_POST;
                        header('Location: /them-uu-dai?error=' . urlencode('Upload file bị gián đoạn'));
                        exit;
                        
                    case UPLOAD_ERR_NO_TMP_DIR:
                        $_SESSION['old_data'] = $_POST;
                        header('Location: /them-uu-dai?error=' . urlencode('Lỗi server: không có thư mục tạm'));
                        exit;
                        
                    case UPLOAD_ERR_CANT_WRITE:
                        $_SESSION['old_data'] = $_POST;
                        header('Location: /them-uu-dai?error=' . urlencode('Lỗi server: không thể ghi file'));
                        exit;
                }
                
                // Kiểm tra kích thước file (5MB = 5 * 1024 * 1024 bytes)
                $maxSize = 5 * 1024 * 1024; // 5MB
                if ($_FILES['anhUuDai']['size'] > $maxSize) {
                    $_SESSION['old_data'] = $_POST;
                    header('Location: /them-uu-dai?error=' . urlencode('File ảnh không được vượt quá 5MB'));
                    exit;
                }
                
                // Kiểm tra định dạng file
                $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $mimeType = finfo_file($finfo, $_FILES['anhUuDai']['tmp_name']);
                finfo_close($finfo);
                
                if (!in_array($mimeType, $allowedTypes)) {
                    $_SESSION['old_data'] = $_POST;
                    header('Location: /them-uu-dai?error=' . urlencode('Chỉ chấp nhận file ảnh JPG, PNG, GIF, WebP'));
                    exit;
                }
            }

            // Tiếp tục xử lý như bình thường...
            $_SESSION['old_data'] = $_POST;
            
            // Validate dữ liệu
            if (empty($_POST['tenUuDai']) || empty($_POST['noiDungChiTiet']) || 
                empty($_POST['dateUuDai']) || empty($_POST['dateUuDaiEnd']) || 
                empty($_POST['trangThai']) || empty($_POST['loaiUuDai'])) {
                header('Location: /them-uu-dai?error=' . urlencode('Vui lòng điền đầy đủ thông tin'));
                exit;
            }
    
            // Validate dates
            if ($_POST['dateUuDai'] > $_POST['dateUuDaiEnd']) {
                header('Location: /them-uu-dai?error=' . urlencode('Ngày bắt đầu phải nhỏ hơn ngày kết thúc'));
                exit;
            }
    
            // Xử lý upload ảnh
            $imagePath = '';
            if (isset($_FILES['anhUuDai']) && $_FILES['anhUuDai']['error'] === UPLOAD_ERR_OK) {
                $imagePath = $this->uploadImage($_FILES['anhUuDai']);
                if (!$imagePath) {
                    header('Location: /them-uu-dai?error=' . urlencode('Lỗi upload ảnh'));
                    exit;
                }
            }
    
            // Chuẩn bị dữ liệu
            $data = [
                'ud_tenuudai' => trim($_POST['tenUuDai']),
                'ud_anhuudai' => $imagePath,
                'ud_loaiuudai' => $_POST['loaiUuDai'],
                'ud_noidung' => trim($_POST['noiDungChiTiet']),
                'ud_thoigianbatdau' => $_POST['dateUuDai'],
                'ud_thoigianketthuc' => $_POST['dateUuDaiEnd'],
                'ud_trangthai' => $this->getStatusByDate($_POST['dateUuDai'], $_POST['dateUuDaiEnd'])
            ];
    
            // Thêm vào database
            $maUuDai = $this->uuDaiModel->createUuDai($data);
            
            if ($maUuDai) {
                // ✅ Clear old data khi thành công
                unset($_SESSION['old_data']);
                header('Location: /quan-ly-uu-dai?success=' . urlencode('Thêm ưu đãi thành công'));
                exit;
            } else {
                // Xóa ảnh nếu thêm vào DB thất bại
                if ($imagePath && file_exists($this->uploadsPath . '/' . basename($imagePath))) {
                    unlink($this->uploadsPath . '/' . basename($imagePath));
                }
                header('Location: /them-uu-dai?error=' . urlencode('Lỗi thêm ưu đãi vào cơ sở dữ liệu'));
                exit;
            }
        } catch (Exception $e) {
            error_log("Error in UuDaiAdminController storeUuDai: " . $e->getMessage());
            $_SESSION['old_data'] = $_POST;
            header('Location: /them-uu-dai?error=' . urlencode('Lỗi hệ thống'));
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
                header('Location: /quan-ly-uu-dai?error=' . urlencode('ID ưu đãi không hợp lệ'));
                exit;
            }
    
            $uuDai = $this->uuDaiModel->getUuDaiById($id);
            if (!$uuDai) {
                header('Location: /quan-ly-uu-dai?error=' . urlencode('Không tìm thấy ưu đãi'));
                exit;
            }
    
            echo $this->blade->render('admin-views.UuDai.SuaUuDai', [
                'activePage' => 'uudai',
                'uuDai' => $uuDai,
                'error' => $_GET['error'] ?? null,
                'oldData' => $_SESSION['old_data'] ?? []
            ]);
            
            // Clear old data after rendering
            unset($_SESSION['old_data']);
            
        } catch (Exception $e) {
            error_log("Error in UuDaiAdminController suaUuDai: " . $e->getMessage());
            header('Location: /quan-ly-uu-dai?error=' . urlencode('Không thể tải trang sửa ưu đãi'));
            exit;
        }
    }

    /**
     * Xử lý cập nhật ưu đãi
     */
    public function updateUuDai()
    {
        AuthHelper::checkAccess('admin_only');
        try {
            $id = $_GET['id'] ?? $_POST['id'] ?? '';
            if (empty($id)) {
                header('Location: /quan-ly-uu-dai?error=' . urlencode('ID ưu đãi không hợp lệ'));
                exit;
            }

            // Validate dữ liệu
            if (empty($_POST['tenUuDai']) || empty($_POST['noiDungChiTiet']) || 
                empty($_POST['dateUuDai']) || empty($_POST['dateUuDaiEnd']) || 
                empty($_POST['trangThai']) || empty($_POST['loaiUuDai'])) {
                header('Location: /sua-uu-dai?id=' . $id . '&error=' . urlencode('Vui lòng điền đầy đủ thông tin'));
                exit;
            }

            // Validate dates
            if ($_POST['dateUuDai'] > $_POST['dateUuDaiEnd']) {
                header('Location: /sua-uu-dai?id=' . $id . '&error=' . urlencode('Ngày bắt đầu phải nhỏ hơn ngày kết thúc'));
                exit;
            }

            // Lấy thông tin ưu đãi hiện tại
            $currentUuDai = $this->uuDaiModel->getUuDaiById($id);
            if (!$currentUuDai) {
                header('Location: /quan-ly-uu-dai?error=' . urlencode('Không tìm thấy ưu đãi'));
                exit;
            }

            // Xử lý upload ảnh mới (nếu có)
            $imagePath = $currentUuDai['ud_anhuudai']; // Giữ ảnh cũ
            if (isset($_FILES['anhUuDai']) && $_FILES['anhUuDai']['error'] === UPLOAD_ERR_OK) {
                $newImagePath = $this->uploadImage($_FILES['anhUuDai']);
                if ($newImagePath) {
                    // Xóa ảnh cũ
                    if ($imagePath && file_exists($this->uploadsPath . '/' . basename($imagePath))) {
                        unlink($this->uploadsPath . '/' . basename($imagePath));
                    }
                    $imagePath = $newImagePath;
                }
            }

            // Chuẩn bị dữ liệu
            $data = [
                'ud_tenuudai' => trim($_POST['tenUuDai']),
                'ud_anhuudai' => $imagePath,
                'ud_loaiuudai' => $_POST['loaiUuDai'],
                'ud_noidung' => trim($_POST['noiDungChiTiet']),
                'ud_thoigianbatdau' => $_POST['dateUuDai'],
                'ud_thoigianketthuc' => $_POST['dateUuDaiEnd'],
                'ud_trangthai' => $this->getStatusByDate($_POST['dateUuDai'], $_POST['dateUuDaiEnd'])
            ];

            // Cập nhật database
            if ($this->uuDaiModel->updateUuDai($id, $data)) {
                header('Location: /quan-ly-uu-dai?success=' . urlencode('Cập nhật ưu đãi thành công'));
                exit;
            } else {
                header('Location: /sua-uu-dai?id=' . $id . '&error=' . urlencode('Lỗi cập nhật ưu đãi'));
                exit;
            }
        } catch (Exception $e) {
            error_log("Error in UuDaiAdminController updateUuDai: " . $e->getMessage());
            $id = $_GET['id'] ?? $_POST['id'] ?? '';
            header('Location: /sua-uu-dai?id=' . $id . '&error=' . urlencode('Lỗi hệ thống'));
            exit;
        }
    }

    /**
     * Xóa ưu đãi
     */
    public function deleteUuDai()
    {
        AuthHelper::checkAccess('admin_only');
        try {
            $id = $_GET['id'] ?? '';
            if (empty($id)) {
                header('Location: /quan-ly-uu-dai?error=' . urlencode('ID ưu đãi không hợp lệ'));
                exit;
            }

            // Lấy thông tin ưu đãi để xóa ảnh
            $uuDai = $this->uuDaiModel->getUuDaiById($id);
            
            if ($this->uuDaiModel->deleteUuDai($id)) {
                // Xóa ảnh khỏi hệ thống file
                if ($uuDai && $uuDai['ud_anhuudai'] && file_exists($this->uploadsPath . '/' . basename($uuDai['ud_anhuudai']))) {
                    unlink($this->uploadsPath . '/' . basename($uuDai['ud_anhuudai']));
                }
                
                header('Location: /quan-ly-uu-dai?success=' . urlencode('Xóa ưu đãi thành công'));
                exit;
            } else {
                header('Location: /quan-ly-uu-dai?error=' . urlencode('Lỗi xóa ưu đãi'));
                exit;
            }
        } catch (Exception $e) {
            error_log("Error in UuDaiAdminController deleteUuDai: " . $e->getMessage());
            header('Location: /quan-ly-uu-dai?error=' . urlencode('Lỗi hệ thống'));
            exit;
        }
    }

    /**
     * Upload ảnh ưu đãi
     */
    private function uploadImage($file)
    {
        AuthHelper::checkAccess('admin_only');
        try {
            // Validate file type
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            if (!in_array($file['type'], $allowedTypes)) {
                return false;
            }

            // Validate file size (max 5MB)
            if ($file['size'] > 5 * 1024 * 1024) {
                return false;
            }

            // Create uploads directory if not exists
            if (!is_dir($this->uploadsPath)) {
                mkdir($this->uploadsPath, 0755, true);
            }

            // Generate unique filename
            $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
            $filename = 'uudai_' . uniqid() . '_' . date('Ymd') . '.' . $extension;
            $filepath = $this->uploadsPath . '/' . $filename;

            // Move uploaded file
            if (move_uploaded_file($file['tmp_name'], $filepath)) {
                return '/static/uploads/uudai/' . $filename;
            }

            return false;
        } catch (Exception $e) {
            error_log("Error uploading image: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Xác định trạng thái dựa trên ngày
     */
    private function getStatusByDate($startDate, $endDate)
    {
        AuthHelper::checkAccess('admin_only');
        $today = date('Y-m-d');
        
        if ($today < $startDate) {
            return 'Sắp diễn ra';
        } elseif ($today > $endDate) {
            return 'Kết thúc';
        } else {
            return 'Đang diễn ra';
        }
    }
}