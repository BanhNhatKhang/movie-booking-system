<?php

namespace App\Controllers;

use App\Models\LichChieu;
use App\Models\PhongChieu;
use App\Models\LoaiVe;
use App\Models\Ve;
use App\Models\ThanhToan;
use App\Models\Ghe; // THÊM DÒNG NÀY
use App\Controllers\MovieController;
use App\Core\Database;
use Jenssegers\Blade\Blade;
use Exception;
use App\Models\NguoiDung; // Thêm dòng này

class PayController
{
    private $lichChieuModel;
    private $phongChieuModel;
    private $loaiVeModel;
    private $veModel;
    private $thanhToanModel;
    private $movieController;
    private $gheModel; // THÊM PROPERTY NÀY
    private $db; // Thêm db property
    private $blade;

    public function __construct()
    {
        $this->lichChieuModel = new LichChieu();
        $this->phongChieuModel = new PhongChieu();
        $this->loaiVeModel = new LoaiVe();
        $this->veModel = new Ve();
        $this->thanhToanModel = new ThanhToan();
        $this->movieController = new MovieController();
        $this->gheModel = new Ghe(); // ✅ THÊM DÒNG NÀY
        
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
            error_log("POST params: " . json_encode($_POST));
            error_log("SESSION logged_in: " . ($_SESSION['logged_in'] ?? 'not set'));
            error_log("SESSION user_id: " . ($_SESSION['user_id'] ?? 'not set'));
            error_log("SESSION user_name: " . ($_SESSION['user_name'] ?? 'not set'));
            
            // Kiểm tra đăng nhập
            if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
                error_log("User not logged in, redirecting to login");
                
                // ✅ LƯU POST DATA VÀO SESSION
                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['lich_chieu'])) {
                    $_SESSION['pending_payment'] = $_POST;
                    error_log("Saved pending payment: " . json_encode($_SESSION['pending_payment']));
                }
                
                $_SESSION['error_message'] = 'Vui lòng đăng nhập để thanh toán vé!';
                header('Location: /dang-nhap');
                exit;
            }
            
            // ✅ LẤY DỮ LIỆU TỪ POST HOẶC SESSION
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $lichChieuId = $_POST['lich_chieu'] ?? '';
                $seatCodes = $_POST['seats'] ?? '';
                $seatDisplays = $_POST['seat_displays'] ?? '';
                $seatTypes = $_POST['seat_types'] ?? '';
                $seatPrices = $_POST['seat_prices'] ?? '';
                $total = intval($_POST['total'] ?? 0);
                
                // Lưu vào session để có thể reload page
                $_SESSION['current_payment'] = $_POST;
                
            } elseif (isset($_SESSION['current_payment'])) {
                // Nếu là GET và có session data (reload page)
                $postData = $_SESSION['current_payment'];
                $lichChieuId = $postData['lich_chieu'] ?? '';
                $seatCodes = $postData['seats'] ?? '';
                $seatDisplays = $postData['seat_displays'] ?? '';
                $seatTypes = $postData['seat_types'] ?? '';
                $seatPrices = $postData['seat_prices'] ?? '';
                $total = intval($postData['total'] ?? 0);
                
            } elseif (isset($_SESSION['pending_payment'])) {
                // Nếu có pending payment từ trước khi đăng nhập
                $postData = $_SESSION['pending_payment'];
                $lichChieuId = $postData['lich_chieu'] ?? '';
                $seatCodes = $postData['seats'] ?? '';
                $seatDisplays = $postData['seat_displays'] ?? '';
                $seatTypes = $postData['seat_types'] ?? '';
                $seatPrices = $postData['seat_prices'] ?? '';
                $total = intval($postData['total'] ?? 0);
                
                // Lưu vào current_payment và xóa pending
                $_SESSION['current_payment'] = $_SESSION['pending_payment'];
                unset($_SESSION['pending_payment']);
                
            } else {
                // ✅ KHÔNG CÓ DỮ LIỆU - REDIRECT VỀ TRANG CHỦ
                error_log("No payment data found - redirecting");
                $_SESSION['error_message'] = 'Không tìm thấy thông tin đặt vé!';
                header('Location: /phim-dang-chieu');
                exit;
            }
            
            error_log("Processed params:");
            error_log("LichChieu ID: " . $lichChieuId);
            error_log("Seat codes: " . $seatCodes);
            error_log("Seat displays: " . $seatDisplays);
            error_log("Total: " . $total);
            
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
            
            // ✅ XỬ LÝ SEAT DETAILS TỪ POST DATA
            $seatList = explode(',', $seatCodes);
            $seatDisplayList = explode(',', $seatDisplays);
            $seatTypeList = !empty($seatTypes) ? explode(',', $seatTypes) : [];
            $seatPriceList = !empty($seatPrices) ? explode(',', $seatPrices) : [];
            
            error_log("Seat processing:");
            error_log("Seat list: " . json_encode($seatList));
            error_log("Seat display list: " . json_encode($seatDisplayList));
            error_log("Seat type list: " . json_encode($seatTypeList));
            error_log("Seat price list: " . json_encode($seatPriceList));
            
            // ✅ Lấy giá vé từ MovieController
            error_log("Getting price from MovieController");
            $giaBanVe = $this->movieController->getGiaBanVeTheoPhong($lichChieu);
            error_log("Price list: " . json_encode($giaBanVe));
            
            // Lấy thông tin chi tiết ghế với giá chính xác
            $seatDetails = [];
            $calculatedTotal = 0;
            
            foreach ($seatList as $index => $seatCode) {
                error_log("Processing seat: " . $seatCode);
                
                // ✅ SỬ DỤNG DATA TỪ POST TRƯỚC, NẾU KHÔNG CÓ THÌ QUERY DATABASE
                if (!empty($seatTypeList[$index]) && !empty($seatPriceList[$index])) {
                    $seatType = $seatTypeList[$index];
                    $seatPrice = intval($seatPriceList[$index]);
                    error_log("Using POST data - Seat type: " . $seatType . ", Price: " . $seatPrice);
                } else {
                    // Fallback: query database
                    $seatInfo = $this->gheModel->getGheByMaGhe($seatCode);
                    error_log("Seat info from DB: " . json_encode($seatInfo));
                    
                    if ($seatInfo) {
                        $seatType = $seatInfo['g_loaighe'] ?? 'normal';
                        $seatPrice = $giaBanVe[$seatType] ?? $giaBanVe['normal'];
                        error_log("From DB - Seat type: " . $seatType . ", Price: " . $seatPrice);
                    } else {
                        error_log("Seat info not found for: " . $seatCode);
                        continue;
                    }
                }
                
                $seatDetails[] = [
                    'code' => $seatCode,
                    'display' => $seatDisplayList[$index] ?? $seatCode,
                    'type' => $seatType,
                    'price' => $seatPrice
                ];
                $calculatedTotal += $seatPrice;
            }
            
            // ✅ Sử dụng total từ POST nếu có, nếu không thì dùng calculated
            $total = $total > 0 ? $total : $calculatedTotal;
            
            // Debug trước khi render
            error_log("=== RENDERING THANH TOAN VIEW ===");
            error_log("LichChieu data: " . json_encode($lichChieu));
            error_log("Seat details: " . json_encode($seatDetails));
            error_log("Total: " . $total);
            
            // ✅ Kiểm tra file poster có tồn tại không
            $posterPath = $lichChieu['p_poster'] ?? '';
            if ($posterPath && !file_exists($_SERVER['DOCUMENT_ROOT'] . $posterPath)) {
                error_log("Poster file not found: " . $_SERVER['DOCUMENT_ROOT'] . $posterPath);
                $lichChieu['p_poster'] = '/static/images/default-poster.jpg';
            }
            
            // Render view
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
            if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || empty($_SESSION['user_id'])) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Bạn cần đăng nhập để thanh toán!'
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
            
            //  Tạo thanh toán
            $maThanhToan = $this->generatePaymentCode();
            $thanhToanData = [
                'tt_mathanhtoan' => $maThanhToan,
                'tt_sotien' => $total,
                'tt_phuongthuc' => $paymentMethod,
                'tt_thoigianthanhtoan' => date('Y-m-d H:i:s'),
                'nd_id' => $_SESSION['user_id']
            ];
            
            error_log("nd_id: " . $thanhToanData['nd_id']);
            error_log("tt_mathanhtoan: " . $thanhToanData['tt_mathanhtoan']);
            error_log("tt_sotien: " . $thanhToanData['tt_sotien']);
            error_log("tt_phuongthuc: " . $thanhToanData['tt_phuongthuc']);
            error_log("tt_thoigianthanhtoan: " . $thanhToanData['tt_thoigianthanhtoan']);
            
            $thanhToanCreated = $this->createThanhToan($thanhToanData);
            
            if (!$thanhToanCreated) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Không thể tạo thanh toán!'
                ]);
                exit;
            }
            
            $ticketCodes = [];
            foreach ($seatDetails as $seat) {
                $maVe = $this->generateTicketCode();
                $veData = [
                    'v_mave' => $maVe,
                    'v_ngaydat' => date('Y-m-d'),
                    'v_tongtien' => $seat['price'],
                    'v_trangthai' => 'chua_in',
                    'nd_id' => $_SESSION['user_id'],
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
            
            // Bắt đầu thêm mã tích điểm vào đây
            $userId = $_SESSION['user_id'];
            $nguoiDungModel = new NguoiDung();

            // 1. Lấy hạng hiện tại của user
            $user = $nguoiDungModel->getById($userId);
            $loai = $user['nd_loaithanhvien'] ?? 'bac';

            // 2. Xác định tỷ lệ tích điểm theo hạng
            switch ($loai) {
                case 'vang': $tyle = 0.07; break;
                case 'kimcuong': $tyle = 0.10; break;
                default: $tyle = 0.05;
            }

            // 3. Cộng điểm tích lũy cho từng vé
            foreach ($seatDetails as $seat) {
                $diemCongTien = round($seat['price'] * $tyle);
                $diemCong = floor($diemCongTien / 1000);
                if ($diemCong > 0) {
                    $nguoiDungModel->congDiemTichLuy($userId, $diemCong);
                }
            }

            // 4. Sau khi cộng điểm, kiểm tra tổng chi tiêu để nâng bậc
            $totalChiTieu = $nguoiDungModel->tongChiTieu($userId);
            if ($totalChiTieu >= 4000000) {
                $hang = 'kimcuong';
            } elseif ($totalChiTieu >= 2000000) {
                $hang = 'vang';
            } else {
                $hang = 'bac';
            }
            $nguoiDungModel->capNhatLoaiThanhVien($userId, $hang);
            // Kết thúc thêm mã tích điểm
            
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
        return 'V' . strtoupper(substr(bin2hex(random_bytes(5)), 0, 9));
        // 'V' + 9 ký tự = 10 ký tự
    }
    
    /**
     * Lấy mã loại vé theo loại ghế
     */
    
    /**
     * Tạo thanh toán - Sử dụng ThanhToan Model
     */
    private function createThanhToan($data)
    {
        try {
            error_log("Creating thanh_toan with data: " . json_encode($data));
            $result = $this->thanhToanModel->create($data);

            if ($result) {
                error_log("Thanh_toan created successfully");
            } else {
                error_log("Failed to create thanh_toan");
            }

            return $result;

        } catch (Exception $e) {
            error_log("Error creating thanh_toan: " . $e->getMessage());
            error_log("Data: " . json_encode($data));
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