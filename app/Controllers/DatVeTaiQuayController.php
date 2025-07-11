<?php

namespace App\Controllers;

use App\Helpers\AuthHelper;
use App\Models\LichChieu;
use App\Models\Phim;
use App\Models\Ghe;
use App\Models\Ve;
use App\Models\LoaiVe; 
use App\Models\PhongChieu;
use App\Models\ThanhToan;  
use App\Core\Database;
use Jenssegers\Blade\Blade;
use Exception;

class DatVeTaiQuayController
{
    private $blade;
    private $loaiVeModel;
    private $db;

    public function __construct()
    {
        $this->blade = new Blade(
            realpath(__DIR__ . '/../Views'),
            cachePath: realpath(__DIR__ . '/../../cache')
        );
        $this->loaiVeModel = new LoaiVe();
        
        // ✅ KIỂM TRA DATABASE CONNECTION
        try {
            $this->db = Database::getInstance()->getConnection();
            error_log("✅ Database connected successfully");
        } catch (Exception $e) {
            error_log("💥 Database connection failed: " . $e->getMessage());
            throw $e;
        }
    }

    public function index()
    {
        try {
            $phimModel = new Phim();
            $lichChieuModel = new LichChieu();
            
            $phimList = $phimModel->getAllPhim();
            $lichChieuList = $lichChieuModel->getLichChieuWithFullDetails();
            
            return $this->blade->render('admin-views.DatVeTaiQuay.DatVeTaiQuay', [
                'phimList' => $phimList,
                'lichChieuList' => $lichChieuList,
                'gheList' => [],
                'soldSeats' => []
            ]);
            
        } catch (Exception $e) {
            return $this->blade->render('admin-views.DatVeTaiQuay.DatVeTaiQuay', [
                'error' => 'Có lỗi xảy ra: ' . $e->getMessage(),
                'phimList' => [],
                'lichChieuList' => [],
                'gheList' => [],
                'soldSeats' => []
            ]);
        }
    }

    public function datVeTaiQuay()
    {
        AuthHelper::checkAccess('admin_only');
        
        try {
            $phimModel = new Phim();
            $lichChieuModel = new LichChieu();
            $gheModel = new Ghe();
            $veModel = new Ve();
            $phongChieuModel = new PhongChieu();

            $phimList = $phimModel->getActivePhim();
            $lichChieuList = $lichChieuModel->getAllLichChieu();
            $phongChieuList = $phongChieuModel->getAll();
            $gheList = $gheModel->getAllGhe();
            $soldSeats = [];

            echo $this->blade->render('admin-views.DatVeTaiQuay.DatVeTaiQuay', [
                'activePage' => 'book-ticket',
                'phimList' => $phimList,
                'lichChieuList' => $lichChieuList,
                'phongChieuList' => $phongChieuList,
                'gheList' => $gheList,
                'soldSeats' => $soldSeats
            ]);
        } catch (Exception $e) {
            echo $this->blade->render('admin-views.DatVeTaiQuay.DatVeTaiQuay', [
                'activePage' => 'book-ticket',
                'phimList' => [],
                'lichChieuList' => [],
                'phongChieuList' => [],
                'gheList' => [],
                'soldSeats' => [],
                'error' => 'Không thể tải dữ liệu'
            ]);
        }
    }

    public function datVeTaiQuaySubmit()
    {
        AuthHelper::checkAccess('admin_only');
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $data = [
                    'v_mave' => 'VE' . time() . rand(100, 999),
                    'v_ngaydat' => date('Y-m-d H:i:s'),
                    'v_tongtien' => $_POST['tongtien'],
                    'v_trangthai' => 'da_in',
                    'nd_id' => $_SESSION['user_id'],
                    'tt_mathanhtoan' => null,
                    'g_maghe' => $_POST['ghe'],
                    'lc_malichchieu' => $_POST['lichchieu']
                ];
                
                $veModel = new Ve();
                $result = $veModel->create($data);
                
                if ($result) {
                    echo json_encode([
                        'success' => true,
                        'message' => 'Đặt vé thành công',
                        'ticket_id' => $data['v_mave']
                    ]);
                } else {
                    echo json_encode([
                        'success' => false,
                        'message' => 'Có lỗi xảy ra khi đặt vé'
                    ]);
                }
            } catch (Exception $e) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Lỗi hệ thống: ' . $e->getMessage()
                ]);
            }
            exit;
        }
    }

    public function getLichChieuByPhim()
    {
        AuthHelper::checkAccess('admin_only');
        
        if (isset($_GET['phim_id'])) {
            $phimId = $_GET['phim_id'];
            $lichChieuModel = new LichChieu();
            $lichChieu = $lichChieuModel->getLichChieuByPhim($phimId);
            
            echo json_encode($lichChieu);
            exit;
        }
    }

    public function getGheByLichChieu()
    {
        AuthHelper::checkAccess('admin_only');
        
        if (isset($_GET['lichchieu_id'])) {
            $lichChieuId = $_GET['lichchieu_id'];
            $lichChieuModel = new LichChieu();
            $gheModel = new Ghe();
            $veModel = new Ve();
            
            $lichChieu = $lichChieuModel->getLichChieuWithPhongChieu($lichChieuId);
            
            if ($lichChieu && isset($lichChieu['pc_maphongchieu'])) {
                $ghe = $gheModel->getByPhongChieu($lichChieu['pc_maphongchieu']);
                $soldSeats = $veModel->getGheDaDatByLichChieu($lichChieuId);
                $giaBanVe = $this->getGiaBanVeTheoGhe($lichChieu, $ghe);
                
                echo json_encode([
                    'ghe' => $ghe,
                    'soldSeats' => array_column($soldSeats, 'g_maghe'),
                    'giaBanVe' => $giaBanVe['byType'],
                    'giaBanVeChiTiet' => $giaBanVe['bySeat']
                ]);
            } else {
                echo json_encode([
                    'ghe' => [],
                    'soldSeats' => [],
                    'giaBanVe' => $this->getGiaBanVeDefault(),
                    'giaBanVeChiTiet' => []
                ]);
            }
            exit;
        }
    }

    private function getGiaBanVeDefault()
    {
        return [
            'normal' => 70000,
            'vip' => 90000,
            'luxury' => 120000,
            'couple' => 150000
        ];
    }
    
    public function getGiaBanVeTheoGhe($lichChieu, $gheList)
    {
        try {
            $maPhongChieu = $lichChieu['pc_maphongchieu'] ?? '';
            $tenPhongChieu = $lichChieu['pc_tenphong'] ?? '';
            $loaiPhongChieu = $lichChieu['pc_loaiphong'] ?? '';
            
            $loaiVe = null;
            
            // 1. Tìm theo mã phòng chiếu
            $loaiVe = $this->loaiVeModel->getLoaiVeById($maPhongChieu);
            
            // 2. Nếu không tìm thấy, tìm theo loại phòng
            if (!$loaiVe) {
                $allLoaiVe = $this->loaiVeModel->getAllLoaiVeSimple();
                
                foreach ($allLoaiVe as $lv) {
                    $tenLoaiVe = strtolower($lv['lv_tenloaive']);
                    $maLoaiVe = strtolower($lv['lv_maloaive']);
                    
                    if (stripos($tenLoaiVe, $loaiPhongChieu) !== false ||
                        stripos($maLoaiVe, $loaiPhongChieu) !== false ||
                        stripos($tenLoaiVe, $tenPhongChieu) !== false) {
                        $loaiVe = $lv;
                        break;
                    }
                }
            }
            
            // 3. Nếu vẫn không tìm thấy, lấy loại vé đầu tiên
            if (!$loaiVe) {
                $allLoaiVe = $this->loaiVeModel->getAllLoaiVeSimple();
                if (!empty($allLoaiVe)) {
                    $loaiVe = $allLoaiVe[0];
                }
            }
            
            $giaCoban = $loaiVe ? (int)$loaiVe['lv_giatien'] : 70000;
            
            $giaBanVeTheoLoai = [];
            $giaBanVeTheoGhe = [];
            
            foreach ($gheList as $ghe) {
                $loaiGhe = $ghe['g_loaighe'];
                $giaGhe = (int)($ghe['g_giaghe'] ?? 0);
                $tongGia = $giaCoban + $giaGhe;
                
                $giaBanVeTheoGhe[$ghe['g_maghe']] = $tongGia;
                
                if (!isset($giaBanVeTheoLoai[$loaiGhe])) {
                    $giaBanVeTheoLoai[$loaiGhe] = $tongGia;
                }
            }
            
            return [
                'bySeat' => $giaBanVeTheoGhe,
                'byType' => $giaBanVeTheoLoai
            ];
            
        } catch (Exception $e) {
            return [
                'bySeat' => [],
                'byType' => $this->getGiaBanVeDefault()
            ];
        }
    }
    
    /**
     * ✅ SỬA STORVE - BỎ TẤT CẢ ERROR_LOG & THÊM HEADER JSON
     */
    public function storeDatVe()
    {
        header('Content-Type: application/json');
        
        try {
            $input = json_decode(file_get_contents('php://input'), true);
            $selectedSeats = $input['selected_seats'] ?? [];
            $showtimeId = $input['showtime_id'] ?? '';
            $customerInfo = $input['customer_info'] ?? [];
            $paymentMethod = $input['payment_method'] ?? 'Tiền mặt';
            
            error_log("📥 Input data: " . json_encode($input));
            
            if (empty($selectedSeats) || empty($showtimeId)) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Vui lòng chọn ghế và suất chiếu'
                ]);
                exit;
            }
            $employeeId = $_SESSION['user_id'] ?? null;
            if (!$employeeId) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Vui lòng đăng nhập để thực hiện đặt vé'
                ]);
                exit;
            }
            
            error_log("👤 Employee ID: " . $employeeId);
            
            // ✅ KHÔNG DÙNG TRANSACTION, XỬ LÝ TỪNG BƯỚC
            try {
                // Tạo thanh toán trước
                $paymentId = $this->createPaymentByEmployee($employeeId, $selectedSeats, $paymentMethod);
                error_log("💰 Payment created: " . $paymentId);
                
                // Tạo vé cho từng ghế
                $ticketIds = [];
                foreach ($selectedSeats as $index => $seatData) {
                    error_log("🪑 Processing seat " . ($index + 1) . ": " . json_encode($seatData));
                    
                    $ticketId = $this->createTicketForWalkIn([
                        'employee_id' => $employeeId,
                        'payment_id' => $paymentId,
                        'seat_id' => $seatData['id'],
                        'showtime_id' => $showtimeId,
                        'price' => $seatData['price'],
                        'customer_info' => $customerInfo
                    ]);
                    
                    $ticketIds[] = $ticketId;
                    error_log("✅ Ticket created: " . $ticketId);
                }
                
                error_log("🎉 All tickets created successfully: " . json_encode($ticketIds));
                
                echo json_encode([
                    'success' => true,
                    'message' => 'Đặt vé thành công cho khách vãng lai!',
                    'ticket_ids' => $ticketIds,
                    'payment_id' => $paymentId,
                    'customer_name' => $customerInfo['name'] ?? 'Khách vãng lai',
                    'total_amount' => array_sum(array_column($selectedSeats, 'price'))
                ]);
                
            } catch (Exception $e) {
                error_log("💥 Error in booking process: " . $e->getMessage());
                throw $e;
            }
            
        } catch (Exception $e) {
            error_log("💥 Error in storeDatVe: " . $e->getMessage());
            echo json_encode([
                'success' => false,
                'message' => 'Lỗi hệ thống: ' . $e->getMessage()
            ]);
        }
        
        exit;
    }

    private function createPaymentByEmployee($employeeId, $selectedSeats, $paymentMethod)
    {
        $thanhToanModel = new ThanhToan();
        
        $totalAmount = array_sum(array_column($selectedSeats, 'price'));
        $paymentId = 'TT' . date('YmdHis') . rand(100, 999);        $paymentData = [
            'tt_mathanhtoan' => $paymentId,
            'tt_sotien' => $totalAmount,
            'tt_phuongthuc' => $paymentMethod,
            'tt_thoigianthanhtoan' => date('Y-m-d H:i:s'),
            'nd_id' => $employeeId
        ];
        
        // ✅ THÊM DEBUG ĐỂ KIỂM TRA
        error_log("💰 Creating payment: " . json_encode($paymentData));
        
        $result = $thanhToanModel->create($paymentData);
        
        // ✅ KIỂM TRA KẾT QUẢ
        if (!$result) {
            throw new Exception("Không thể tạo thanh toán");
        }
        
        error_log("✅ Payment created successfully: " . $paymentId);
        return $paymentId;
    }

    private function createTicketForWalkIn($ticketData)
    {
        // ✅ SỬA TỪ:
        // $veModel = new \App\Models\Ve();
    
        // ✅ THÀNH:
        $veModel = new Ve();
    
        $ticketId = 'V' . strtoupper(substr(uniqid(), 0, 9));       
        $ticketInfo = [
            'v_mave' => $ticketId,
            'v_ngaydat' => date('Y-m-d'),
            'v_tongtien' => $ticketData['price'],
            'v_trangthai' => 'da_in',
            'nd_id' => $ticketData['employee_id'],
            'tt_mathanhtoan' => $ticketData['payment_id'],
            'g_maghe' => $ticketData['seat_id'] ?? $ticketData['id'], // ✅ SỬA
            'lc_malichchieu' => $ticketData['showtime_id']
        ];
        
        // ✅ THÊM DEBUG
        error_log("🎫 Creating ticket: " . json_encode($ticketInfo));
    
        $result = $veModel->create($ticketInfo);
    
        // ✅ KIỂM TRA KẾT QUẢ
        if (!$result) {
            throw new Exception("Không thể tạo vé cho ghế: " . $ticketData['seat_id']);
        }
    
        error_log("✅ Ticket created successfully: " . $ticketId);
        return $ticketId;
    }
}