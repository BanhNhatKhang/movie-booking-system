<?php

namespace App\Controllers;

use Jenssegers\Blade\Blade;
use App\Models\PhongChieu;
use App\Models\Ghe;
use App\Helpers\AuthHelper;
use Exception;

class QuanLyPhongGheController
{
    private $phongChieuModel;
    private $gheModel;
    
    public function __construct()
    {
        $this->phongChieuModel = new PhongChieu();
        $this->gheModel = new Ghe();
    }
    
    public function quanLyPhongGhe()
    {
        AuthHelper::checkAccess('admin_only');

        $blade = new Blade(
            realpath(__DIR__ . '/../Views'),
            realpath(__DIR__ . '/../../cache')
        );
        
        // Lấy danh sách phòng chiếu từ database
        $rooms = $this->phongChieuModel->getAllPhongChieu();
        
        // Kiểm tra có phòng nào không
        if (empty($rooms)) {
            error_log("No rooms found in database");
            
            // Render view với thông báo không có phòng
            echo $blade->render('admin-views.QuanLyPhongGhe.QuanLyPhongGhe', [
                'activePage' => 'PhongGhe',
                'rooms' => [],
                'roomId' => null,
                'room' => null,
                'roomTypes' => ['3D', 'IMAX', '4DX', 'Ultra 4DX'],
                'gheList' => [],
                'allGhes' => [],
                'noRoomsMessage' => 'Chưa có phòng chiếu nào trong hệ thống.'
            ]);
            return;
        }
        
        // Chuyển đổi định dạng cho template
        $roomsFormatted = [];
        foreach ($rooms as $index => $room) {
            $roomsFormatted[$index + 1] = [
                'id' => $room['pc_maphongchieu'],
                'name' => $room['pc_tenphong'],
                'type' => $room['pc_loaiphong']
            ];
        }
        
        // Lấy room hiện tại - sửa logic này
        $roomId = isset($_GET['room']) && isset($roomsFormatted[$_GET['room']]) ? intval($_GET['room']) : 1;
        
        // Đảm bảo có room mặc định
        if (!isset($roomsFormatted[$roomId])) {
            $roomId = array_key_first($roomsFormatted);
        }
        
        $room = $roomsFormatted[$roomId];
        
        // LẤY MÃ PHÒNG THỰC TẾ 
        $actualRoomCode = $room['id']; 
        
        // Lấy danh sách ghế từ database
        $allGhes = $this->gheModel->getAllGhe();
        
        // Lọc ghế theo search params
        $q = $_GET['q'] ?? '';
        $loaiGhe = $_GET['loai_ghe'] ?? '';
        $trangThai = $_GET['trang_thai'] ?? '';
        
        // SỬA LOGIC LỌC - dùng mã phòng thực tế
        $filteredGhes = array_filter($allGhes, function($ghe) use ($q, $loaiGhe, $trangThai, $actualRoomCode) {
            $ok = true;
            
            // lọc câu truy vấn tìm kiếm
            if ($q && !str_contains(strtolower($ghe['g_maghe']), strtolower($q))) {
                $ok = false;
            }
            
            // lọc phòng
            if ($actualRoomCode && $ghe['pc_maphongchieu'] != $actualRoomCode) {
                $ok = false;
            }
            
            // lọc loại chỗ ngồi
            if ($loaiGhe && $ghe['g_loaighe'] != $loaiGhe) {
                $ok = false;
            }
            
            // Filter by status
            if ($trangThai && $ghe['g_trangthai'] != $trangThai) {
                $ok = false;
            }
            
            return $ok;
        });
        
        // tính toán phân trang
        $perPage = 12;
        $totalSeats = count($filteredGhes);
        $totalPages = max(1, ceil($totalSeats / $perPage));
        $currentPage = max(1, min((int)($_GET['page'] ?? 1), $totalPages));
        
        // nhóm các ghế trong phòng
        $gheListByRoom = array_filter($filteredGhes, function($ghe) use ($actualRoomCode) {
            return $ghe['pc_maphongchieu'] == $actualRoomCode;
        });
        
        // debug
        error_log("Selected room: " . $actualRoomCode);
        error_log("Total filtered seats: " . count($filteredGhes));
        error_log("Seats for room: " . count($gheListByRoom));
        
        // render view
        echo $blade->render('admin-views.QuanLyPhongGhe.QuanLyPhongGhe', [
            'activePage' => 'PhongGhe',
            'rooms' => $roomsFormatted,
            'roomId' => $roomId,
            'room' => $room,
            'actualRoomCode' => $actualRoomCode,
            'roomTypes' => ['3D', 'IMAX', '4DX', 'Ultra 4DX'],
            'gheList' => $filteredGhes,
            'allGhes' => $allGhes,
            'gheListByRoom' => $gheListByRoom,
            'totalPages' => $totalPages,
            'currentPage' => $currentPage,
            'perPage' => $perPage
        ]);
    }
    
    //hiển thị sơ đồ ghế
    public function showTaoSoDoGhe()
    {
        AuthHelper::checkAccess('admin_only');
        $blade = new Blade(
            realpath(__DIR__ . '/../Views'),
            realpath(__DIR__ . '/../../cache')
        );
        
        echo $blade->render('admin-views.QuanLyPhongGhe.TaoSoDoGhe', [
            'activePage' => 'PhongGhe',
            'pageTitle' => 'Tạo Sơ Đồ Ghế'
        ]);
    }
    
    // Quy trình tạo sơ đồ chỗ ngồi
    public function luuSoDoGhe()
    {
        AuthHelper::checkAccess('admin_only');
        try {
            // Validate input
            $maPhong = trim($_POST['ma_phong'] ?? '');
            $tenPhong = trim($_POST['ten_phong'] ?? '');
            $loaiPhong = trim($_POST['loai_phong'] ?? '');
            $seatDataJson = trim($_POST['seat_data'] ?? '');
            
            if (empty($maPhong) || empty($tenPhong) || empty($loaiPhong) || empty($seatDataJson)) {
                error_log("Missing required data for seat map creation");
                header('Location: /tao-so-do-ghe?error=missing_data');
                exit;
            }
            
            // Phân tích dữ liệu chỗ ngồi
            $seatData = json_decode($seatDataJson, true);
            if (!$seatData || !is_array($seatData)) {
                error_log("Invalid seat data JSON: " . $seatDataJson);
                header('Location: /tao-so-do-ghe?error=invalid_seat_data');
                exit;
            }
            
            // Chuyển đổi dữ liệu chỗ ngồi bao gồm tiền tố phòng
            $transformedSeats = [];
            $processedDisplays = []; // ✅ THÊM BIẾN ĐỂ TRACK GHẾ ĐÃ XỬ LÝ

            foreach ($seatData as $seatCode => $seat) {
                // ✅ LẤY DISPLAY NAME (VD: J01)
                $displayName = $seat['display'] ?? '';
                
                // ✅ BỎ QUA NẾU ĐÃ XỬ LÝ DISPLAY NAME NÀY
                if (in_array($displayName, $processedDisplays)) {
                    error_log("Bỏ qua ghế trùng: {$displayName}");
                    continue;
                }
                
                // ✅ TẠO MÃ GHẾ CHUẨN
                $standardSeatCode = $maPhong . '_' . $displayName;
                
                $transformedSeats[$standardSeatCode] = [
                    'code' => $standardSeatCode,
                    'type' => $seat['type'],
                    'room' => $maPhong,
                    'display' => $displayName
                ];
                
                // ✅ ĐÁNH DẤU ĐÃ XỬ LÝ
                $processedDisplays[] = $displayName;
                
                error_log("Tạo ghế: {$standardSeatCode} (display: {$displayName})");
            }
            
            error_log("✅ Tổng ghế unique: " . count($transformedSeats));
            
            // kiểm tra nếu phòng đã tồn tại
            if ($this->phongChieuModel->checkPhongExists($maPhong)) {
                error_log("phòng đã tồn tại: " . $maPhong);
                header('Location: /tao-so-do-ghe?error=room_exists&ma_phong=' . urlencode($maPhong));
                exit;
            }
            
            // tạo phòng trước
            $roomData = [
                'ma_phong' => $maPhong,
                'ten_phong' => $tenPhong,
                'loai_phong' => $loaiPhong
            ];
            
            if (!$this->phongChieuModel->createPhongChieu($roomData)) {
                error_log("tạo phòng thất bại: " . $maPhong);
                header('Location: /tao-so-do-ghe?error=create_room_failed');
                exit;
            }
            
            // tạo ghế với mã tiền tố pref 
            if (!$this->gheModel->createMultipleSeats($maPhong, $transformedSeats)) {
                error_log("Failed to create seats for room: " . $maPhong);
                header('Location: /tao-so-do-ghe?error=create_seats_failed');
                exit;
            }
            
            // thông báo thành công
            error_log("tạo phòng và ghế thành công: " . $maPhong);
            header('Location: /quan-ly-phong-ghe?success=created&room=' . urlencode($maPhong));
            exit;
            
        } catch (Exception $e) {
            error_log("xảy ra lỗi khi lưu sơ đồ ghế: " . $e->getMessage());
            header('Location: /tao-so-do-ghe?error=system_error');
            exit;
        }
    }
    
    //kiểm tra nếu mã phòng có sẵn (AJAX)
    public function checkRoomAvailable()
    {
        AuthHelper::checkAccess('admin_only');
        header('Content-Type: application/json');
        
        $maPhong = trim($_GET['ma_phong'] ?? '');
        
        if (empty($maPhong)) {
            echo json_encode([
                'available' => false, 
                'message' => 'Mã phòng không được để trống'
            ]);
            exit;
        }
        
        // Validate định dạng mã phòng
        if (!preg_match('/^[A-Z0-9]{3,10}$/', $maPhong)) {
            echo json_encode([
                'available' => false,
                'message' => 'Mã phòng phải là 3-10 ký tự, chỉ gồm chữ và số'
            ]);
            exit;
        }
        
        $exists = $this->phongChieuModel->checkPhongExists($maPhong);
        
        echo json_encode([
            'available' => !$exists,
            'message' => $exists ? 'Mã phòng đã tồn tại' : 'Mã phòng có thể sử dụng'
        ]);
        exit;
    }
    
    // cập nhật trạng thái ghế
    public function updateSeatStatus()
    {
        AuthHelper::checkAccess('admin_only');
        try {
            // ✅ Set header JSON ngay từ đầu
            header('Content-Type: application/json');
            
            $maGhe = $_POST['ma_ghe'] ?? '';
            $trangThai = $_POST['trang_thai'] ?? '';
            
            error_log("Update seat status - Seat: $maGhe, Status: $trangThai");
            
            if (empty($maGhe) || empty($trangThai)) {
                echo json_encode(['success' => false, 'message' => 'Thiếu thông tin']);
                exit;
            }
            
            // ✅ Validate trạng thái
            $validStatuses = ['available', 'locked', 'booked'];
            if (!in_array($trangThai, $validStatuses)) {
                echo json_encode(['success' => false, 'message' => 'Trạng thái không hợp lệ']);
                exit;
            }
            
            $result = $this->gheModel->updateSeatStatus($maGhe, $trangThai);
            
            error_log("Update result: " . ($result ? 'success' : 'failed'));
            
            echo json_encode([
                'success' => $result,
                'message' => $result ? 'Cập nhật thành công' : 'Cập nhật thất bại',
                'seat' => $maGhe,
                'status' => $trangThai
            ]);
            
        } catch (Exception $e) {
            error_log("Error in updateSeatStatus: " . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Lỗi hệ thống: ' . $e->getMessage()]);
        }
        exit;
    }
    
    //cập nhật loại ghế
    public function updateSeatType()
    {
        AuthHelper::checkAccess('admin_only');
        try {
            // ✅ Set header JSON ngay từ đầu
            header('Content-Type: application/json');
            
            $maGhe = $_POST['ma_ghe'] ?? '';
            $loaiGhe = $_POST['loai_ghe'] ?? '';
            
            error_log("Update seat type - Seat: $maGhe, Type: $loaiGhe");
            
            if (empty($maGhe) || empty($loaiGhe)) {
                echo json_encode(['success' => false, 'message' => 'Thiếu thông tin']);
                exit;
            }
            
            // ✅ Validate loại ghế
            $validTypes = ['normal', 'vip', 'luxury'];
            if (!in_array($loaiGhe, $validTypes)) {
                echo json_encode(['success' => false, 'message' => 'Loại ghế không hợp lệ']);
                exit;
            }
            
            $result = $this->gheModel->updateSeatType($maGhe, $loaiGhe);
            
            error_log("Update result: " . ($result ? 'success' : 'failed'));
            
            echo json_encode([
                'success' => $result,
                'message' => $result ? 'Cập nhật thành công' : 'Cập nhật thất bại',
                'seat' => $maGhe,
                'type' => $loaiGhe
            ]);
            
        } catch (Exception $e) {
            error_log("Error in updateSeatType: " . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Lỗi hệ thống: ' . $e->getMessage()]);
        }
        exit;
    }
    
    // nhận thống kê chỗ ngồi
    public function getSeatStatistics()
    {
        AuthHelper::checkAccess('admin_only');
        try {
            $maPhong = $_GET['ma_phong'] ?? null;
            $stats = $this->gheModel->getSeatStatistics($maPhong);
            
            echo json_encode(['success' => true, 'data' => $stats]);
            
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Lỗi hệ thống']);
        }
        exit;
    }
    
    // Cập nhật hàng loạt loại chỗ ngồi (để chỉnh sửa sơ đồ chỗ ngồi)
    public function bulkUpdateSeatTypes()
    {
        AuthHelper::checkAccess('admin_only');
        try {
            $updates = json_decode(file_get_contents('php://input'), true);
            
            if (!$updates || !is_array($updates)) {
                echo json_encode(['success' => false, 'message' => 'Dữ liệu không hợp lệ']);
                exit;
            }
            
            $result = $this->gheModel->bulkUpdateSeatTypes($updates);
            
            echo json_encode([
                'success' => $result,
                'message' => $result ? 'Cập nhật thành công' : 'Cập nhật thất bại'
            ]);
            
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Lỗi hệ thống']);
        }
        exit;
    }
    
    /**
     * Cập nhật giá theo loại ghế - TOÀN HỆ THỐNG
     */
    public function updatePriceByType()
    {
        AuthHelper::checkAccess('admin_only');
        
        try {
            header('Content-Type: application/json');
            
            $loaiGhe = $_POST['loai_ghe'] ?? '';
            $giaGhe = $_POST['gia_ghe'] ?? 0;
            // ✅ BỎ HOÀN TOÀN PHÒNG CHIẾU - CẬP NHẬT TẤT CẢ
            
            error_log("=== UPDATE PRICE BY TYPE - GLOBAL SYSTEM ===");
            error_log("Loai ghe: " . $loaiGhe);
            error_log("Gia ghe: " . $giaGhe);
            
            // Validate loại ghế
            $validTypes = ['normal', 'vip', 'luxury', 'couple'];
            if (empty($loaiGhe) || !in_array($loaiGhe, $validTypes)) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Loại ghế không hợp lệ. Chỉ chấp nhận: ' . implode(', ', $validTypes)
                ]);
                exit;
            }
            
            // Validate giá
            if (!is_numeric($giaGhe) || $giaGhe < 0) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Giá ghế phải là số không âm'
                ]);
                exit;
            }
            
            $gheModel = new Ghe();
            
            // ✅ KHÔNG TRUYỀN PHÒNG CHIẾU = CẬP NHẬT TẤT CẢ HỆ THỐNG
            $result = $gheModel->bulkUpdateAllSeatPrices($loaiGhe, intval($giaGhe));
            
            if ($result !== false) {
                error_log("✅ Successfully updated price GLOBALLY for seat type: " . $loaiGhe . ", affected rows: " . $result);
                echo json_encode([
                    'success' => true,
                    'message' => "Cập nhật thành công giá cho {$result} ghế loại {$loaiGhe} trên toàn hệ thống",
                    'affected_rows' => $result,
                    'seat_type' => $loaiGhe,
                    'new_price' => intval($giaGhe)
                ]);
            } else {
                error_log("❌ Failed to update global price for seat type: " . $loaiGhe);
                echo json_encode([
                    'success' => false,
                    'message' => 'Không thể cập nhật giá ghế. Vui lòng thử lại.'
                ]);
            }
            
        } catch (Exception $e) {
            error_log("💥 Error in updatePriceByType: " . $e->getMessage());
            echo json_encode([
                'success' => false,
                'message' => 'Lỗi hệ thống: ' . $e->getMessage()
            ]);
        }
        
        exit;
    }
    
    /**
     * Lấy thống kê giá ghế
     */
    public function getPriceStats()
    {
        AuthHelper::checkAccess('admin_only');
        
        try {
            $gheModel = new Ghe();
            $stats = $gheModel->getPriceStatsByType();
            
            echo json_encode([
                'success' => true,
                'data' => $stats
            ]);
            
        } catch (Exception $e) {
            error_log("Error in getPriceStats: " . $e->getMessage());
            echo json_encode([
                'success' => false,
                'message' => 'Lỗi hệ thống: ' . $e->getMessage()
            ]);
        }
        
        exit;
    }
}