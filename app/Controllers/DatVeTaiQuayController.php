<?php

namespace App\Controllers;

use App\Helpers\AuthHelper;
use App\Models\LichChieu;
use App\Models\Phim;
use App\Models\Ghe;
use App\Models\Ve;
use App\Models\LoaiVe; 
use App\Models\PhongChieu; // ✅ THÊM DÒNG NÀY
use Jenssegers\Blade\Blade;
use Exception; // ✅ THÊM DÒNG NÀY

class DatVeTaiQuayController
{
    private $blade;
    private $loaiVeModel;

    public function __construct()
    {
        $this->blade = new Blade(
            realpath(__DIR__ . '/../Views'),
            cachePath: realpath(__DIR__ . '/../../cache')
        );
        $this->loaiVeModel = new LoaiVe();
    }

    public function datVeTaiQuay()
    {
        AuthHelper::checkAccess('admin_only');
        
        try {
            // Lấy dữ liệu từ database
            $phimModel = new Phim();
            $lichChieuModel = new LichChieu();
            $gheModel = new Ghe();
            $veModel = new Ve();
            $phongChieuModel = new PhongChieu();

            // Lấy danh sách phim đang chiếu (dùng method có sẵn)
            $phimList = $phimModel->getActivePhim(); // ✅ Có sẵn
            
            // Lấy tất cả lịch chiếu (dùng method có sẵn)
            $lichChieuList = $lichChieuModel->getAllLichChieu(); // ✅ Có sẵn
            
            // Lấy danh sách phòng chiếu
            $phongChieuList = $phongChieuModel->getAll(); // ✅ Có sẵn (BaseModel)
            
            // Lấy danh sách ghế (dùng method có sẵn)
            $gheList = $gheModel->getAllGhe(); // ✅ Có sẵn
            
            // Lấy ghế đã bán (tạm để array rỗng, sẽ load AJAX)
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
            error_log("Error in datVeTaiQuay: " . $e->getMessage());
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
                    'nd_id' => $_SESSION['user_id'], // Lưu mã nhân viên thực hiện
                    'tt_mathanhtoan' => null,
                    'g_maghe' => $_POST['ghe'],
                    'lc_malichchieu' => $_POST['lichchieu']
                ];
                
                $veModel = new Ve();
                $result = $veModel->create($data); // ✅ Có sẵn
                
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
                error_log("Error in datVeTaiQuaySubmit: " . $e->getMessage());
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
            $lichChieu = $lichChieuModel->getLichChieuByPhim($phimId); // ✅ Có sẵn
            
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
            
            // Lấy thông tin lịch chiếu
            $lichChieu = $lichChieuModel->getLichChieuWithPhongChieu($lichChieuId);
            
            if ($lichChieu && isset($lichChieu['pc_maphongchieu'])) {
                // Lấy ghế của phòng chiếu (CÓ GIÁ PHỤ THU)
                $ghe = $gheModel->getByPhongChieu($lichChieu['pc_maphongchieu']);
                
                // Lấy ghế đã bán
                $soldSeats = $veModel->getGheDaDatByLichChieu($lichChieuId);
                
                // ✅ SỬA: GỌI METHOD ĐÚNG - DÙNG DATABASE g_giaghe
                $giaBanVe = $this->getGiaBanVeTheoGhe($lichChieu, $ghe);
                
                echo json_encode([
                    'ghe' => $ghe,
                    'soldSeats' => array_column($soldSeats, 'g_maghe'),
                    'giaBanVe' => $giaBanVe['byType'], // Gửi giá theo loại cho JS
                    'giaBanVeChiTiet' => $giaBanVe['bySeat'] // Gửi giá từng ghế
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

    /**
     * Giá vé mặc định khi không tìm thấy loại vé
     */
    private function getGiaBanVeDefault()
    {
        return [
            'normal' => 70000,
            'vip' => 90000,
            'luxury' => 120000,
            'couple' => 150000
        ];
    }
    
    /**
     * Tính giá vé theo ghế - LOGIC GIỐNG MovieController NHƯNG DÙNG DATABASE
     */
    public function getGiaBanVeTheoGhe($lichChieu, $gheList)
    {
        try {
            // ✅ DÙNG LOGIC GIỐNG MovieController - Lấy giá cơ bản
            $maPhongChieu = $lichChieu['pc_maphongchieu'] ?? '';
            $tenPhongChieu = $lichChieu['pc_tenphong'] ?? '';
            $loaiPhongChieu = $lichChieu['pc_loaiphong'] ?? '';
            
            error_log("=== TINH GIA THEO GHE (LOGIC MOVIECONTROLLER) ===");
            error_log("Ma phong: " . $maPhongChieu);
            error_log("Ten phong: " . $tenPhongChieu);
            error_log("Loai phong: " . $loaiPhongChieu);
            
            // ✅ Tìm loại vé theo thứ tự ưu tiên (GIỐNG MovieController)
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
                    error_log("Using first available loai_ve as fallback");
                }
            }
            
            $giaCoban = $loaiVe ? (int)$loaiVe['lv_giatien'] : 70000;
            error_log("Gia co ban tu loai_ve: " . $giaCoban);
            
            // ✅ THAY VÌ HARDCODE PHỤ PHÍ - LẤY TỪ DATABASE g_giaghe
            $giaBanVeTheoLoai = [];
            $giaBanVeTheoGhe = [];
            
            foreach ($gheList as $ghe) {
                $loaiGhe = $ghe['g_loaighe'];
                $giaGhe = (int)($ghe['g_giaghe'] ?? 0); // ✅ LẤY TỪ DATABASE
                $tongGia = $giaCoban + $giaGhe;
                
                // Lưu giá cho từng ghế cụ thể
                $giaBanVeTheoGhe[$ghe['g_maghe']] = $tongGia;
                
                // Lưu giá theo loại ghế (lấy giá đại diện)
                if (!isset($giaBanVeTheoLoai[$loaiGhe])) {
                    $giaBanVeTheoLoai[$loaiGhe] = $tongGia;
                }
                
                error_log("Ghe {$ghe['g_maghe']} ({$loaiGhe}): {$giaCoban} + {$giaGhe} = {$tongGia}");
            }
            
            error_log("Gia theo loai ghe: " . json_encode($giaBanVeTheoLoai));
            
            return [
                'bySeat' => $giaBanVeTheoGhe,     // Giá theo từng ghế
                'byType' => $giaBanVeTheoLoai    // Giá theo loại ghế
            ];
            
        } catch (Exception $e) {
            error_log("Error in getGiaBanVeTheoGhe: " . $e->getMessage());
            return [
                'bySeat' => [],
                'byType' => $this->getGiaBanVeDefault()
            ];
        }
    }
}