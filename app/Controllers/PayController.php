<?php

namespace App\Controllers;

use App\Models\LichChieu;
use App\Models\PhongChieu;
use App\Models\LoaiVe;
use App\Models\Ve;
use App\Models\ThanhToan;
use App\Controllers\MovieController;
use App\Core\Database; // ✅ Thêm Database class
use Jenssegers\Blade\Blade;
use Exception;

class PayController
{
    private $lichChieuModel;
    private $phongChieuModel;
    private $loaiVeModel;
    private $veModel;
    private $thanhToanModel;
    private $movieController;
    private $db; // ✅ Thêm db property
    private $blade;

    public function __construct()
    {
        $this->lichChieuModel = new LichChieu();
        $this->phongChieuModel = new PhongChieu();
        $this->loaiVeModel = new LoaiVe();
        $this->veModel = new Ve();
        $this->thanhToanModel = new ThanhToan();
        $this->movieController = new MovieController();
        
        // ✅ Sử dụng Database class có sẵn
        $this->db = Database::getInstance()->getConnection();
        
        $this->blade = new Blade(
            realpath(__DIR__ . '/../Views'),
            realpath(__DIR__ . '/../../cache')
        );
    }

    public function thanhToan()
    {
        try {
            header('Content-Type: text/html; charset=UTF-8');
            
            error_log("=== THANH TOAN DEBUG START ===");
            error_log("GET params: " . json_encode($_GET));
            error_log("SESSION logged_in: " . ($_SESSION['logged_in'] ?? 'not set'));
            error_log("SESSION user_id: " . ($_SESSION['user_id'] ?? 'not set'));
            error_log("SESSION user_name: " . ($_SESSION['user_name'] ?? 'not set'));
            
            // Kiểm tra đăng nhập
            if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
                error_log("User not logged in, redirecting to login");
                
                // Lưu thông tin vào session để giữ lại sau khi đăng nhập
                if (isset($_GET['lich_chieu']) && isset($_GET['seats'])) {
                    $_SESSION['pending_payment'] = [
                        'lich_chieu' => $_GET['lich_chieu'],
                        'seats' => $_GET['seats'],
                        'seat_displays' => $_GET['seat_displays'] ?? '',
                        'total' => $_GET['total'] ?? 0
                    ];
                    error_log("Saved pending payment: " . json_encode($_SESSION['pending_payment']));
                }
                
                $_SESSION['error_message'] = 'Vui lòng đăng nhập để thanh toán vé!';
                header('Location: /dang-nhap');
                exit;
            }
            
            // Lấy thông tin từ URL hoặc session
            $lichChieuId = $_GET['lich_chieu'] ?? $_SESSION['pending_payment']['lich_chieu'] ?? '';
            $seatCodes = $_GET['seats'] ?? $_SESSION['pending_payment']['seats'] ?? '';
            $seatDisplays = $_GET['seat_displays'] ?? $_SESSION['pending_payment']['seat_displays'] ?? '';
            $total = $_GET['total'] ?? $_SESSION['pending_payment']['total'] ?? 0;
            
            error_log("Processed params:");
            error_log("LichChieu ID: " . $lichChieuId);
            error_log("Seat codes: " . $seatCodes);
            error_log("Seat displays: " . $seatDisplays);
            error_log("Total: " . $total);
            
            // Xóa pending payment sau khi lấy dữ liệu
            if (isset($_SESSION['pending_payment'])) {
                unset($_SESSION['pending_payment']);
            }
            
            // Kiểm tra dữ liệu đầu vào
            if (empty($lichChieuId) || empty($seatCodes)) {
                error_log("Invalid input data - missing lichChieuId or seatCodes");
                $_SESSION['error_message'] = 'Thông tin đặt vé không hợp lệ!';
                header('Location: /phim-dang-chieu');
                exit;
            }
            
            // Lấy thông tin lịch chiếu
            error_log("Getting lichChieu info for ID: " . $lichChieuId);
            $lichChieu = $this->lichChieuModel->getLichChieuWithPhongChieu($lichChieuId);
            error_log("LichChieu result: " . json_encode($lichChieu));
            
            if (!$lichChieu) {
                error_log("LichChieu not found");
                $_SESSION['error_message'] = 'Không tìm thấy thông tin lịch chiếu!';
                header('Location: /phim-dang-chieu');
                exit;
            }
            
            // Xử lý danh sách ghế
            $seatList = explode(',', $seatCodes);
            $seatDisplayList = explode(',', $seatDisplays);
            
            error_log("Seat processing:");
            error_log("Seat list: " . json_encode($seatList));
            error_log("Seat display list: " . json_encode($seatDisplayList));
            
            // ✅ Lấy giá vé từ MovieController
            error_log("Getting price from MovieController");
            $giaBanVe = $this->movieController->getGiaBanVeTheoPhong($lichChieu);
            error_log("Price list: " . json_encode($giaBanVe));
            
            // Lấy thông tin chi tiết ghế với giá chính xác
            $seatDetails = [];
            $calculatedTotal = 0;
            
            foreach ($seatList as $index => $seatCode) {
                error_log("Processing seat: " . $seatCode);
                
                // ✅ Sử dụng method đúng
                $seatInfo = $this->phongChieuModel->getGheByMaGhe($seatCode);
                error_log("Seat info: " . json_encode($seatInfo));
                
                if ($seatInfo) {
                    $seatType = $seatInfo['g_loaighe'] ?? 'normal';
                    $seatPrice = $giaBanVe[$seatType] ?? $giaBanVe['normal'];
                    
                    error_log("Seat type: " . $seatType . ", Price: " . $seatPrice);
                    
                    $seatDetails[] = [
                        'code' => $seatCode,
                        'display' => $seatDisplayList[$index] ?? $seatCode,
                        'type' => $seatType,
                        'price' => $seatPrice
                    ];
                    $calculatedTotal += $seatPrice;
                } else {
                    error_log("Seat info not found for: " . $seatCode);
                }
            }
            
            // ✅ Sử dụng giá tính toán từ MovieController
            $total = $calculatedTotal;
            
            // Debug trước khi render
            error_log("=== RENDERING THANH TOAN VIEW ===");
            error_log("LichChieu data: " . json_encode($lichChieu));
            error_log("Seat details: " . json_encode($seatDetails));
            error_log("Total: " . $total);
            
            // ✅ Debug dữ liệu chi tiết
            error_log("=== THANH TOAN DEBUG ===");
            error_log("Full lichChieu data: " . print_r($lichChieu, true));
            error_log("Poster path: " . ($lichChieu['p_poster'] ?? 'MISSING'));
            error_log("The loai: " . ($lichChieu['p_theloai'] ?? 'MISSING'));
            
            // ✅ Kiểm tra file poster có tồn tại không
            $posterPath = $lichChieu['p_poster'] ?? '';
            if ($posterPath && !file_exists($_SERVER['DOCUMENT_ROOT'] . $posterPath)) {
                error_log("Poster file not found: " . $_SERVER['DOCUMENT_ROOT'] . $posterPath);
                $lichChieu['p_poster'] = '/static/images/default-poster.jpg';
            }
            
            // Trong method thanhToan(), render view mà KHÔNG cần countdown:
            echo $this->blade->render('users-views.ThanhToan.ThanhToan', [
                'activePage' => 'movies',
                'lichChieu' => $lichChieu,
                'seatDetails' => $seatDetails,
                'seatDisplays' => $seatDisplays,
                'total' => $total,
                'lichChieuId' => $lichChieuId,
                'user' => $_SESSION['user_name'] ?? 'User'
            ]);
            
        } catch (Exception $e) {
            error_log("Error in thanhToan: " . $e->getMessage());
            $_SESSION['error_message'] = 'Lỗi tải trang thanh toán: ' . $e->getMessage();
            header('Location: /phim-dang-chieu');
            exit;
        }
    }
    
    public function xuLyThanhToan()
    {
        try {
            header('Content-Type: application/json; charset=UTF-8');
            
            // Kiểm tra đăng nhập
            if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Vui lòng đăng nhập để thực hiện thanh toán!'
                ]);
                exit;
            }
            
            // Lấy dữ liệu JSON
            $input = json_decode(file_get_contents('php://input'), true);
            error_log("Payment request data: " . json_encode($input));
            
            $lichChieuId = $input['lichChieuId'] ?? '';
            $seatDetails = $input['seatDetails'] ?? [];
            $total = $input['total'] ?? 0;
            $paymentMethod = $input['paymentMethod'] ?? 'momo';
            
            // Validate dữ liệu
            if (empty($lichChieuId) || empty($seatDetails) || $total <= 0) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Dữ liệu thanh toán không hợp lệ!'
                ]);
                exit;
            }
            
            // ✅ Lấy lịch chiếu bằng Model
            $lichChieu = $this->lichChieuModel->getLichChieuWithPhongChieu($lichChieuId);
            if (!$lichChieu) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Không tìm thấy lịch chiếu!'
                ]);
                exit;
            }
            
            // ✅ Kiểm tra ghế đã đặt bằng Ve Model
            foreach ($seatDetails as $seat) {
                if ($this->isGheDaDat($lichChieuId, $seat['code'])) {
                    echo json_encode([
                        'success' => false,
                        'message' => 'Ghế ' . $seat['display'] . ' đã được đặt!'
                    ]);
                    exit;
                }
            }
            
            // ✅ Tạo thanh toán
            $maThanhToan = $this->generatePaymentCode();
            $thanhToanData = [
                'tt_mathanhtoan' => $maThanhToan,
                'tt_sotien' => $total,
                'tt_phuongthuc' => $paymentMethod,
                'tt_thoigianthanhtoan' => date('Y-m-d H:i:s'),
                'nd_id' => $_SESSION['user_id'] ?? 'ND001'
            ];
            
            $thanhToanCreated = $this->createThanhToan($thanhToanData);
            
            if (!$thanhToanCreated) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Không thể tạo thanh toán!'
                ]);
                exit;
            }
            
            // ✅ Tạo vé cho từng ghế
            $ticketCodes = [];
            foreach ($seatDetails as $seat) {
                $maVe = $this->generateTicketCode();
                $veData = [
                    'v_mave' => $maVe,
                    'v_ngaydat' => date('Y-m-d'),
                    'v_tongtien' => $seat['price'],
                    'v_trangthai' => 'da_thanh_toan',
                    'nd_id' => $_SESSION['user_id'] ?? 'ND001',
                    'tt_mathanhtoan' => $maThanhToan,
                    'g_maghe' => $seat['code'],
                    'lc_malichchieu' => $lichChieuId
                ];
                
                $veCreated = $this->createVe($veData);
                if ($veCreated) {
                    $ticketCodes[] = $maVe;
                }
            }
            
            if (empty($ticketCodes)) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Không thể tạo vé!'
                ]);
                exit;
            }
            
            echo json_encode([
                'success' => true,
                'message' => 'Thanh toán thành công!',
                'payment_code' => $maThanhToan,
                'ticket_codes' => $ticketCodes
            ]);
            
        } catch (Exception $e) {
            error_log("Error in PayController xuLyThanhToan: " . $e->getMessage());
            echo json_encode([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi xử lý thanh toán!'
            ]);
        }
    }
    
    /**
     * Tạo mã thanh toán
     */
    private function generatePaymentCode()
    {
        return 'TT' . date('YmdHis') . rand(100, 999);
    }
    
    /**
     * Tạo mã vé
     */
    private function generateTicketCode()
    {
        return 'V' . date('YmdHis') . rand(1000, 9999);
    }
    
    /**
     * Lấy mã loại vé theo loại ghế
     */
    private function getLoaiVeIdByType($seatType)
    {
        $mapping = [
            'normal' => 'LV001',
            'vip' => 'LV002',
            'luxury' => 'LV003',
            'couple' => 'LV004'
        ];
        
        return $mapping[$seatType] ?? 'LV001';
    }
    
    /**
     * Tạo thanh toán - Sử dụng ThanhToan Model
     */
    private function createThanhToan($data)
    {
        try {
            error_log("Creating thanh_toan with data: " . json_encode($data));
            
            // ✅ Sử dụng ThanhToan Model thay vì SQL trực tiếp
            $result = $this->thanhToanModel->create($data);
            
            if ($result) {
                error_log("Thanh_toan created successfully");
            } else {
                error_log("Failed to create thanh_toan");
            }
            
            return $result;
            
        } catch (Exception $e) {
            error_log("Error creating thanh_toan: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Tạo vé - Sử dụng Ve Model
     */
    private function createVe($data)
    {
        try {
            error_log("Creating ve with data: " . json_encode($data));
            
            // ✅ Sử dụng Ve Model thay vì SQL trực tiếp
            $result = $this->veModel->create($data);
            
            if ($result) {
                error_log("Ve created successfully: " . $data['v_mave']);
            } else {
                error_log("Failed to create ve: " . $data['v_mave']);
            }
            
            return $result;
            
        } catch (Exception $e) {
            error_log("Error creating ve: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Kiểm tra ghế đã được đặt chưa - Sử dụng Ve Model
     */
    private function isGheDaDat($lichChieuId, $seatCode)
    {
        try {
            // ✅ Sử dụng Ve Model để kiểm tra
            $bookedSeats = $this->veModel->getGheDaDatByLichChieu($lichChieuId);
            
            foreach ($bookedSeats as $bookedSeat) {
                if ($bookedSeat['g_maghe'] === $seatCode) {
                    return true;
                }
            }
            
            return false;
            
        } catch (Exception $e) {
            error_log("Error checking seat: " . $e->getMessage());
            return false;
        }
    }
}