<?php

namespace App\Controllers;

use App\Models\LichChieu;
use App\Models\Phim;
use App\Models\PhongChieu; 
use App\Core\Csrf; 
use Jenssegers\Blade\Blade;
use App\Helpers\AuthHelper;
use Exception;

class QuanLyLichChieuController
{
    private $lichChieuModel;
    private $phimModel;
    private $phongChieuModel;
    private $blade;

    public function __construct()
    {
        $this->lichChieuModel = new LichChieu();
        $this->phimModel = new Phim();
        $this->phongChieuModel = new PhongChieu(); 
        $this->blade = new Blade(
            realpath(__DIR__ . '/../Views'),
            realpath(__DIR__ . '/../../cache')
        );
    }

    /**
     * Hiển thị danh sách lịch chiếu
     */
    public function quanLyLichChieu()
    {
        AuthHelper::checkAccess('admin_only');
        try {
            // Cập nhật trạng thái theo thời gian
            $this->lichChieuModel->updateStatusByTime();
            
            // Lấy dữ liệu tìm kiếm
            $filters = [
                'ngay_chieu' => $_GET['ngay_chieu'] ?? '',
                'trang_thai' => $_GET['trang_thai'] ?? '',
                'ma_phim' => $_GET['ma_phim'] ?? ''
            ];

            // Phân trang
            $page = max(1, intval($_GET['page'] ?? 1));
            $itemsPerPage =5;
            $offset = ($page - 1) * $itemsPerPage;

            // Lấy danh sách lịch chiếu
            if (array_filter($filters)) {
                $lichChieuList = $this->lichChieuModel->searchLichChieu($filters);
            } else {
                $lichChieuList = $this->lichChieuModel->getAllLichChieuFiltered($filters, $itemsPerPage, $offset);
            }

            $totalLichChieu = $this->lichChieuModel->countLichChieu($filters);
            $totalPages = ceil($totalLichChieu / $itemsPerPage);

            // Lấy danh sách phim để filter
            $phimList = $this->lichChieuModel->getAllPhim();

            echo $this->blade->render('admin-views.LichChieu.QuanLyLichChieu', [
                'activePage' => 'schedule', 
                'lichChieuList' => $lichChieuList,
                'phimList' => $phimList,
                'filters' => $filters,
                'currentPage' => $page,
                'totalPages' => $totalPages,
                'totalItems' => $totalLichChieu,
                'itemsPerPage' => $itemsPerPage,
            ]);

        } catch (Exception $e) {
            error_log("Error in quanLyLichChieu: " . $e->getMessage());
            $_SESSION['error_message'] = 'Có lỗi xảy ra khi tải danh sách lịch chiếu!';
            echo $this->blade->render('admin-views.LichChieu.QuanLyLichChieu', [
                'lichChieuList' => [],
                'phimList' => [],
                'filters' => []
            ]);
        }
    }

    /**
     * Hiển thị form thêm lịch chiếu
     */
    public function themLichChieu()
    {
        AuthHelper::checkAccess('admin_only');
        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                return $this->storeLichChieu();
            }

            // Lấy danh sách phim
            $phimList = $this->lichChieuModel->getAllPhim();
            
            // Lấy danh sách phòng chiếu
            $phongChieuList = $this->phongChieuModel->getAllPhongChieu();

            echo $this->blade->render('admin-views.LichChieu.ThemLichChieu', [
                'phimList' => $phimList,
                'phongChieuList' => $phongChieuList // Thêm danh sách phòng chiếu
            ]);

        } catch (Exception $e) {
            error_log("Error in themLichChieu: " . $e->getMessage());
            $_SESSION['error_message'] = 'Có lỗi xảy ra: ' . $e->getMessage();
            header('Location: /quan-ly-lich-chieu');
            exit;
        }
    }

    /**
     * Lưu lịch chiếu mới
     */
    public function storeLichChieu()
    {
        AuthHelper::checkAccess('admin_only');
        try {
            $compositeKey = trim($_POST['lc_malichchieu_composite'] ?? '');
            $ngayChieu = trim($_POST['lc_ngaychieu'] ?? '');
            $gioChieu = trim($_POST['lc_giobatdau'] ?? '');

            $data = [
                'lc_malichchieu' => $compositeKey,
                'p_maphim' => trim($_POST['p_maphim'] ?? ''),
                'pc_maphongchieu' => trim($_POST['pc_maphongchieu'] ?? ''), // Thêm phòng chiếu
                'lc_ngaychieu' => $ngayChieu,
                'lc_giobatdau' => $ngayChieu && $gioChieu ? "$ngayChieu $gioChieu:00" : '',
                'lc_trangthai' => trim($_POST['lc_trangthai'] ?? 'Sắp chiếu')
            ];

            // Validate - thêm kiểm tra phòng chiếu
            if (empty($data['lc_malichchieu']) || empty($data['p_maphim']) || 
                empty($data['pc_maphongchieu']) || empty($data['lc_ngaychieu']) || 
                empty($_POST['lc_giobatdau'])) {
                $_SESSION['old_input'] = $_POST;
                $_SESSION['error_message'] = 'Vui lòng điền đầy đủ thông tin!';
                header('Location: /them-lich-chieu');
                exit;
            }

            // Kiểm tra trùng lịch theo phòng và thời gian
            if ($this->lichChieuModel->checkConflictByRoom($data['pc_maphongchieu'], $data['lc_ngaychieu'], $_POST['lc_giobatdau'])) {
                $_SESSION['old_input'] = $_POST;
                $_SESSION['error_message'] = 'Phòng chiếu đã có lịch chiếu vào thời gian này!';
                header('Location: /them-lich-chieu');
                exit;
            }

            // Tạo lịch chiếu
            $result = $this->lichChieuModel->createLichChieuWithComposite($data);

            if ($result) {
                if (isset($_SESSION['old_input'])) {
                    unset($_SESSION['old_input']);
                }
                $_SESSION['success_message'] = 'Thêm lịch chiếu thành công!';
                header('Location: /quan-ly-lich-chieu');
            } else {
                $_SESSION['old_input'] = $_POST;
                $_SESSION['error_message'] = 'Có lỗi xảy ra khi thêm lịch chiếu!';
                header('Location: /them-lich-chieu');
            }
            exit;

        } catch (Exception $e) {
            error_log("Error in storeLichChieu: " . $e->getMessage());
            $_SESSION['old_input'] = $_POST;
            $_SESSION['error_message'] = 'Có lỗi xảy ra khi lưu lịch chiếu!';
            header('Location: /them-lich-chieu');
            exit;
        }
    }

    /**
     * Hiển thị form sửa lịch chiếu
     */
    public function suaLichChieu()
    {
        AuthHelper::checkAccess('admin_only');
        try {
            $id = trim($_GET['id'] ?? '');
            
            if (empty($id)) {
                $_SESSION['error_message'] = 'ID lịch chiếu không hợp lệ!';
                header('Location: /quan-ly-lich-chieu');
                exit;
            }

            $lichChieu = $this->lichChieuModel->getLichChieuById($id);
            $phongChieuList = $this->phongChieuModel->getAllPhongChieu();
            
            // Lấy danh sách phim với thời lượng
            $phimListRaw = $this->lichChieuModel->getAllPhim();
            $phimList = [];
            
            foreach ($phimListRaw as $phim) {
                // Lấy thêm thông tin thời lượng từ bảng phim
                $phimDetail = $this->phimModel->getPhimById($phim['p_maphim']);
                $phimList[] = [
                    'p_maphim' => $phim['p_maphim'],
                    'p_tenphim' => $phim['p_tenphim'],
                    'p_trangthai' => $phim['p_trangthai'],
                    'p_thoiluong' => $phimDetail['p_thoiluong'] ?? 120 // Default 120 phút
                ];
            }

            echo $this->blade->render('admin-views.LichChieu.SuaLichChieu', [
                'lichChieu' => $lichChieu,
                'phimList' => $phimList, // Có thêm thời lượng
                'phongChieuList' => $phongChieuList,
                'csrf_token' => Csrf::generateToken()
            ]);

        } catch (Exception $e) {
            error_log("Error in suaLichChieu: " . $e->getMessage());
            $_SESSION['error_message'] = 'Có lỗi xảy ra: ' . $e->getMessage();
            header('Location: /quan-ly-lich-chieu');
            exit;
        }
    }

    /**
     * Cập nhật lịch chiếu
     */
    public function updateLichChieu()
    {
        AuthHelper::checkAccess('admin_only');
        try {
            // Check CSRF token
            $csrfToken = trim($_POST['csrf_token'] ?? '');
            if (!Csrf::checkToken($csrfToken)) {
                $_SESSION['error_message'] = 'CSRF token không hợp lệ!';
                header('Location: /quan-ly-lich-chieu');
                exit;
            }

            $id = $_GET['id'] ?? $_POST['id'] ?? '';
            if (empty($id)) {
                $_SESSION['error_message'] = 'Không tìm thấy lịch chiếu!';
                header('Location: /quan-ly-lich-chieu');
                exit;
            }

            error_log("=== UPDATING LICH CHIEU: $id ===");

            $data = [
                'p_maphim' => trim($_POST['p_maphim'] ?? ''),
                'pc_maphongchieu' => trim($_POST['pc_maphongchieu'] ?? ''),
                'lc_ngaychieu' => trim($_POST['lc_ngaychieu'] ?? ''),
                'lc_giobatdau' => trim($_POST['lc_ngaychieu'] . ' ' . $_POST['lc_giobatdau'] ?? ''),
                'lc_trangthai' => trim($_POST['lc_trangthai'] ?? '')
            ];

            error_log("Update data: " . print_r($data, true));
            error_log("Raw time input: " . ($_POST['lc_giobatdau'] ?? 'EMPTY'));

            // Validate
            if (empty($data['p_maphim']) || empty($data['pc_maphongchieu']) || 
                empty($data['lc_ngaychieu']) || empty($_POST['lc_giobatdau'])) {
                $_SESSION['error_message'] = 'Vui lòng điền đầy đủ thông tin!';
                header('Location: /sua-lich-chieu?id=' . $id);
                exit;
            }

            // Kiểm tra trùng lịch theo phòng (exclude current record)
            $hasConflict = $this->lichChieuModel->checkConflictByRoom(
                $data['pc_maphongchieu'], 
                $data['lc_ngaychieu'], 
                $_POST['lc_giobatdau'], 
                $id // Quan trọng: exclude ID hiện tại
            );

            if ($hasConflict) {
                // Lấy thông tin chi tiết về conflict để debug
                $conflictDetails = $this->lichChieuModel->getConflictDetails(
                    $data['pc_maphongchieu'], 
                    $data['lc_ngaychieu'], 
                    $_POST['lc_giobatdau'], 
                    $id
                );
                
                error_log("Conflict details: " . print_r($conflictDetails, true));
                
                $conflictInfo = [];
                foreach ($conflictDetails as $conflict) {
                    $conflictInfo[] = "{$conflict['p_tenphim']} lúc " . date('H:i', strtotime($conflict['lc_giobatdau']));
                }
                
                $errorMessage = 'Phòng chiếu đã có lịch chiếu vào thời gian này!';
                if (!empty($conflictInfo)) {
                    $errorMessage .= ' Trùng với: ' . implode(', ', $conflictInfo);
                }
                
                $_SESSION['error_message'] = $errorMessage;
                header('Location: /sua-lich-chieu?id=' . $id);
                exit;
            }

            // Cập nhật lịch chiếu
            $result = $this->lichChieuModel->updateLichChieu($id, $data);

            if ($result) {
                $_SESSION['success_message'] = 'Cập nhật lịch chiếu thành công!';
                header('Location: /quan-ly-lich-chieu');
            } else {
                $_SESSION['error_message'] = 'Có lỗi xảy ra khi cập nhật!';
                header('Location: /sua-lich-chieu?id=' . $id);
            }
            exit;

        } catch (Exception $e) {
            error_log("Error in updateLichChieu: " . $e->getMessage());
            $_SESSION['error_message'] = 'Có lỗi xảy ra khi cập nhật lịch chiếu!';
            header('Location: /quan-ly-lich-chieu');
            exit;
        }
    }

    /**
     * Xóa lịch chiếu
     */
    public function deleteLichChieu()
    {
        AuthHelper::checkAccess('admin_only');
        try {
            $id = $_GET['id'] ?? '';
            if (empty($id)) {
                $_SESSION['error_message'] = 'Không tìm thấy lịch chiếu!';
                header('Location: /quan-ly-lich-chieu');
                exit;
            }

            // Kiểm tra lịch chiếu có tồn tại không
            $lichChieu = $this->lichChieuModel->getLichChieuById($id);
            if (!$lichChieu) {
                $_SESSION['error_message'] = 'Không tìm thấy lịch chiếu!';
                header('Location: /quan-ly-lich-chieu');
                exit;
            }

            // Xóa lịch chiếu
            $result = $this->lichChieuModel->deleteLichChieu($id);

            if ($result) {
                $_SESSION['success_message'] = 'Xóa lịch chiếu thành công!';
            } else {
                $_SESSION['error_message'] = 'Có lỗi xảy ra khi xóa lịch chiếu!';
            }

            header('Location: /quan-ly-lich-chieu');
            exit;

        } catch (Exception $e) {
            error_log("Error in deleteLichChieu: " . $e->getMessage());
            $_SESSION['error_message'] = 'Có lỗi xảy ra khi xóa lịch chiếu!';
            header('Location: /quan-ly-lich-chieu');
            exit;
        }
    }
}