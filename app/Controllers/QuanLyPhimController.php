<?php

namespace App\Controllers;

use Jenssegers\Blade\Blade;
use App\Models\Phim;
use Exception;
use App\Helpers\AuthHelper;


class QuanLyPhimController
{
    private $phimModel;
    private $blade;

    public function __construct()
    {
        $this->phimModel = new Phim();
        $this->blade = new Blade(
            realpath(__DIR__ . '/../Views'),
            cachePath: realpath(__DIR__ . '/../../cache')
        );
    }

    /**
     * Hiển thị danh sách phim với phân trang và tìm kiếm
     */
    public function quanLyPhim()
    {
        AuthHelper::checkAccess('admin_only');
        try {
            // Get parameters from URL
            $search = trim($_GET['q'] ?? '');
            $status = $_GET['status'] ?? '';
            $page = max(1, intval($_GET['page'] ?? 1));
            $limit = 12;
            $offset = ($page - 1) * $limit;
    
            // Map status từ URL sang database
            $dbStatus = '';
            switch($status) {
                case 'showing':
                    $dbStatus = 'active';
                    break;
                case 'coming':
                    $dbStatus = 'coming_soon';
                    break;
                case 'ended':
                    $dbStatus = 'ended';
                    break;
                default:
                    $dbStatus = '';
            }
    
            // Get data from database
            $phimList = $this->phimModel->getAllPhim($search, $limit, $offset, $dbStatus);
            $totalPhim = $this->phimModel->countPhim($search, $dbStatus);
            $totalPages = max(1, ceil($totalPhim / $limit));
    
            // Format dữ liệu cho view
            $formattedPhimList = [];
            foreach($phimList as $phim) {
                $formattedPhimList[] = [
                    'id' => $phim['p_maphim'],
                    'name' => $phim['p_tenphim'],
                    'genre' => $phim['p_theloai'],
                    'duration' => $phim['p_thoiluong'],
                    'release' => $phim['p_phathanh'],
                    'desc' => $phim['p_mota'],
                    'trailer' => $phim['p_trailer'],
                    'poster' => $phim['p_poster'] ?: '/static/imgs/no-poster.jpg', // ✅ Sửa đây
                    'status' => $this->mapStatusToView($phim['p_trangthai']),
                    'director' => $phim['p_daodien'],
                    'actors' => $phim['p_dienvien']
                ];
            }
    
            // Render view
            echo $this->blade->render('admin-views.QuanLyPhim.QuanLyPhim', [
                'activePage' => 'admin-movies',
                'movies' => $formattedPhimList,
                'currentPage' => $page,
                'totalPages' => $totalPages,
                'totalPhim' => $totalPhim,
                'search' => $search,
                'statusFilter' => $status,
                'limit' => $limit
            ]);
        } catch (Exception $e) {
            error_log("Error in quanLyPhim: " . $e->getMessage());
            echo $this->blade->render('admin-views.QuanLyPhim.QuanLyPhim', [
                'activePage' => 'admin-movies',
                'movies' => [],
                'error' => 'Có lỗi xảy ra khi tải dữ liệu'
            ]);
        }
    }

    /**
     * Hiển thị form thêm phim
     */
    public function themPhim()
    {
        AuthHelper::checkAccess('admin_only');
        echo $this->blade->render('admin-views.QuanLyPhim.ThemPhim', [
            'activePage' => 'admin-movies'
        ]);
    }

    /**
     * Xử lý lưu phim mới
     */
    public function luuPhim()
    {
        AuthHelper::checkAccess('admin_only');
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /them-phim?error=invalid_method');
            exit;
        }
    
        try {
            // Validate input
            $movieId = strtoupper(trim($_POST['movie_id'] ?? ''));
            $tenPhim = trim($_POST['name'] ?? '');
            $theLoai = trim($_POST['genre'] ?? '');
            $thoiLuong = intval($_POST['duration'] ?? 0);
            $ngayPhatHanh = $_POST['release_date'] ?? '';
            $moTa = trim($_POST['description'] ?? '');
            $trailer = trim($_POST['trailer'] ?? '');
            $daoTien = trim($_POST['director'] ?? '');
            $dienVien = trim($_POST['actors'] ?? '');
            $trangThai = $_POST['status'] ?? '';
    
            // Basic validation
            if (empty($movieId) || empty($tenPhim) || empty($theLoai) || $thoiLuong <= 0 || empty($ngayPhatHanh) || empty($trangThai)) {
                header('Location: /them-phim?error=missing_fields');
                exit;
            }
    
            // Validate movie ID format
            if (!preg_match('/^[A-Z]\d{3,}$/', $movieId)) {
                header('Location: /them-phim?error=invalid_movie_id');
                exit;
            }
    
            // Check if movie code exists
            if ($this->phimModel->checkPhimCodeExists($movieId)) {
                header('Location: /them-phim?error=movie_code_exists');
                exit;
            }
    
            // Check if movie name exists
            if ($this->phimModel->checkPhimNameExists($tenPhim)) {
                header('Location: /them-phim?error=movie_name_exists');
                exit;
            }
    
            // Handle poster upload
            $posterPath = '';
            if (isset($_FILES['poster']) && $_FILES['poster']['error'] === UPLOAD_ERR_OK) {
                $posterPath = $this->uploadPoster($_FILES['poster']);
                if (!$posterPath) {
                    header('Location: /them-phim?error=upload_failed');
                    exit;
                }
            }
    
            // ✅ Prepare data for database - đơn giản hóa
            $data = [
                'p_maphim' => $movieId,
                'p_tenphim' => $tenPhim,
                'p_theloai' => $theLoai,
                'p_thoiluong' => $thoiLuong,
                'p_phathanh' => $ngayPhatHanh,
                'p_mota' => $moTa,
                'p_trailer' => $trailer,
                'p_trangthai' => $trangThai,
                'p_dienvien' => $dienVien,
                'p_daodien' => $daoTien,
                'p_poster' => $posterPath // ✅ Trực tiếp lưu đường dẫn
            ];
    
            // Save to database
            if ($this->phimModel->createPhim($data)) {
                header('Location: /quan-ly-phim?success=add_success');
            } else {
                header('Location: /them-phim?error=save_failed');
            }
        } catch (Exception $e) {
            error_log("Error adding phim: " . $e->getMessage());
            header('Location: /them-phim?error=system_error');
        }
        exit;
    }

    /**
     * Hiển thị form sửa phim
     */
    public function suaPhim()
    {
        AuthHelper::checkAccess('admin_only');
        $id = $_GET['id'] ?? '';
        if (empty($id)) {
            header('Location: /quan-ly-phim?error=invalid_id');
            exit;
        }

        try {
            $phim = $this->phimModel->getPhimById($id);
            if (!$phim) {
                header('Location: /quan-ly-phim?error=phim_not_found');
                exit;
            }

            // Format dữ liệu cho view
            $movie = [
                'id' => $phim['p_maphim'],
                'name' => $phim['p_tenphim'],
                'genre' => $phim['p_theloai'],
                'duration' => $phim['p_thoiluong'],
                'release' => $phim['p_phathanh'],
                'desc' => $phim['p_mota'],
                'trailer' => $phim['p_trailer'],
                'poster' => $phim['p_poster'] ?? '',
                'status' => $phim['p_trangthai'],
                'director' => $phim['p_daodien'],
                'actors' => $phim['p_dienvien']
            ];

            echo $this->blade->render('admin-views.QuanLyPhim.SuaPhim', [
                'activePage' => 'admin-movies',
                'movie' => $movie
            ]);
        } catch (Exception $e) {
            error_log("Error in suaPhim: " . $e->getMessage());
            header('Location: /quan-ly-phim?error=system_error');
            exit;
        }
    }

    /**
     * Xử lý cập nhật phim
     */
    public function capNhatPhim()
    {
        AuthHelper::checkAccess('admin_only');
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /quan-ly-phim');
            exit;
        }
    
        try {
            $id = $_POST['id'] ?? $_GET['id'] ?? '';
            if (empty($id)) {
                header('Location: /quan-ly-phim?error=invalid_id');
                exit;
            }
    
            // Get existing movie
            $existingPhim = $this->phimModel->getPhimById($id);
            if (!$existingPhim) {
                header('Location: /quan-ly-phim?error=phim_not_found');
                exit;
            }
    
            // Validate input
            $tenPhim = trim($_POST['name'] ?? '');
            $theLoai = trim($_POST['genre'] ?? '');
            $thoiLuong = intval($_POST['duration'] ?? 0);
            $ngayPhatHanh = $_POST['release'] ?? '';
            $moTa = trim($_POST['desc'] ?? '');
            $trailer = trim($_POST['trailer'] ?? '');
            $daoTien = trim($_POST['director'] ?? '');
            $dienVien = trim($_POST['actors'] ?? '');
            $trangThai = $_POST['status'] ?? $existingPhim['p_trangthai'];
    
            if (empty($tenPhim) || empty($theLoai) || $thoiLuong <= 0 || empty($ngayPhatHanh)) {
                header('Location: /sua-phim?id=' . $id . '&error=missing_fields');
                exit;
            }
    
            // Check if name exists (exclude current movie)
            if ($this->phimModel->checkPhimNameExists($tenPhim, $id)) {
                header('Location: /sua-phim?id=' . $id . '&error=movie_name_exists');
                exit;
            }
    
            // Handle poster upload
            $posterPath = $existingPhim['p_poster']; // ✅ Giữ poster cũ
            if (isset($_FILES['poster']) && $_FILES['poster']['error'] === UPLOAD_ERR_OK) {
                $newPosterPath = $this->uploadPoster($_FILES['poster']);
                if ($newPosterPath) {
                    $posterPath = $newPosterPath;
                }
            }
    
            // ✅ Prepare data - đơn giản hóa
            $data = [
                'p_tenphim' => $tenPhim,
                'p_theloai' => $theLoai,
                'p_thoiluong' => $thoiLuong,
                'p_phathanh' => $ngayPhatHanh,
                'p_mota' => $moTa,
                'p_trailer' => $trailer,
                'p_trangthai' => $trangThai,
                'p_dienvien' => $dienVien,
                'p_daodien' => $daoTien,
                'p_poster' => $posterPath // ✅ Poster path
            ];
    
            // Update database
            if ($this->phimModel->updatePhim($id, $data)) {
                header('Location: /quan-ly-phim?success=update_success');
            } else {
                header('Location: /sua-phim?id=' . $id . '&error=save_failed');
            }
        } catch (Exception $e) {
            error_log("Error updating phim: " . $e->getMessage());
            header('Location: /quan-ly-phim?error=system_error');
        }
        exit;
    }

    /**
     * Hiển thị form đổi trạng thái phim
     */
    public function doiTrangThaiPhim()
    {
        AuthHelper::checkAccess('admin_only');
        $id = $_GET['id'] ?? '';
        if (empty($id)) {
            header('Location: /quan-ly-phim?error=invalid_id');
            exit;
        }
    
        try {
            $phim = $this->phimModel->getPhimById($id);
            if (!$phim) {
                header('Location: /quan-ly-phim?error=phim_not_found');
                exit;
            }
    
            // Format dữ liệu cho view
            $movie = [
                'id' => $phim['p_maphim'],
                'name' => $phim['p_tenphim'],
                'genre' => $phim['p_theloai'],
                'duration' => $phim['p_thoiluong'],
                'release' => $phim['p_phathanh'],
                'desc' => $phim['p_mota'],
                'trailer' => $phim['p_trailer'],
                'poster' => $phim['p_poster'] ?? '',
                'status' => $phim['p_trangthai'],
                'director' => $phim['p_daodien'],
                'actors' => $phim['p_dienvien']
            ];
    
            echo $this->blade->render('admin-views.QuanLyPhim.DoiTrangThaiPhim', [
                'activePage' => 'admin-movies',
                'phim' => $movie
            ]);
        } catch (Exception $e) {
            error_log("Error in doiTrangThaiPhim: " . $e->getMessage());
            header('Location: /quan-ly-phim?error=system_error');
            exit;
        }
    }

    /**
     * Xử lý POST request đổi trạng thái
     */
    public function capNhatTrangThai()
    {
        AuthHelper::checkAccess('admin_only');
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /quan-ly-phim');
            exit;
        }

        try {
            $id = $_POST['id'] ?? '';
            $status = $_POST['status'] ?? '';

            if (empty($id) || empty($status)) {
                header('Location: /quan-ly-phim?error=invalid_params');
                exit;
            }

            if ($this->phimModel->updateStatus($id, $status)) {
                header('Location: /quan-ly-phim?success=status_updated');
            } else {
                header('Location: /quan-ly-phim?error=update_failed');
            }
        } catch (Exception $e) {
            error_log("Error updating phim status: " . $e->getMessage());
            header('Location: /quan-ly-phim?error=system_error');
        }
        exit;
    }

    /**
     * Upload poster image
     */
    private function uploadPoster($file)
    {
        AuthHelper::checkAccess('admin_only');
        try {
            $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
            $maxSize = 5 * 1024 * 1024; // 5MB

            // Validate file type
            if (!in_array($file['type'], $allowedTypes)) {
                return false;
            }

            // Validate file size
            if ($file['size'] > $maxSize) {
                return false;
            }

            // Create upload directory
            $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/static/uploads/posters/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            // Generate unique filename
            $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
            $filename = 'poster_' . uniqid() . '_' . date('Ymd') . '.' . $extension;
            $filepath = $uploadDir . $filename;

            // Move uploaded file
            if (move_uploaded_file($file['tmp_name'], $filepath)) {
                return '/static/uploads/posters/' . $filename;
            }

            return false;
        } catch (Exception $e) {
            error_log("Error uploading poster: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Map trạng thái database sang view
     */
    private function mapStatusToView($dbStatus)
    {
        AuthHelper::checkAccess('admin_only');
        switch($dbStatus) {
            case 'active':
                return 'showing';
            case 'coming_soon':
                return 'coming';
            case 'ended':
            case 'inactive':
            case 'suspended':
                return 'ended';
            default:
                return 'ended';
        }
    }

    /**
     * Get next status for cycling
     */
    private function getNextStatus($currentStatus)
    {
        AuthHelper::checkAccess('admin_only');
        $statusCycle = [
            'active' => 'coming_soon',
            'coming_soon' => 'ended',
            'ended' => 'suspended',
            'suspended' => 'active',
            'inactive' => 'active'
        ];

        return $statusCycle[$currentStatus] ?? 'active';
    }

    /**
     * Get status display name
     */
    private function getStatusDisplayName($status)
    {
        AuthHelper::checkAccess('admin_only');
        $statusNames = [
            'active' => 'Đang chiếu',
            'coming_soon' => 'Sắp chiếu',
            'ended' => 'Đã kết thúc',
            'suspended' => 'Tạm ngưng',
            'inactive' => 'Không hoạt động'
        ];

        return $statusNames[$status] ?? 'Không xác định';
    }
}