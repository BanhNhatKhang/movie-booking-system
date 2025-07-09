<?php

namespace App\Controllers;
use App\Models\Phim;
use App\Models\LichChieu; 
use App\Models\LoaiVe; // ✅ Thêm dòng này
use Jenssegers\Blade\Blade;
use Exception;

class MovieController
{
    private $phimModel;
    private $lichChieuModel; 
    private $loaiVeModel; // ✅ Thêm property này
    private $blade;

    public function __construct()
    {
        $this->phimModel = new Phim();
        $this->lichChieuModel = new LichChieu(); 
        $this->loaiVeModel = new LoaiVe(); // ✅ Thêm dòng này
        $this->blade = new Blade(
            realpath(__DIR__ . '/../Views'),
            cachePath: realpath(__DIR__ . '/../../cache')
        );
    }

    /**
     * Hiển thị trang phim đang chiếu
     */
    public function phimDangChieu()
    {
        try {
            header('Content-Type: text/html; charset=UTF-8');
            error_log("MovieController@phimDangChieu called");
            
            // ✅ Lấy danh sách phim đang chiếu (active)
            $phimList = $this->phimModel->getPhimByStatus('active');
            
            error_log("Found " . count($phimList) . " active movies");
            
            // ✅ Format dữ liệu để hiển thị - sửa tên biến
            $phimDangChieu = [];
            foreach ($phimList as $phim) {
                $phimDangChieu[] = [
                    'id' => $phim['p_maphim'],
                    'name' => $phim['p_tenphim'],
                    'genre' => $phim['p_theloai'],
                    'duration' => $phim['p_thoiluong'],
                    'release' => $phim['p_phathanh'],
                    'desc' => $phim['p_mota'],
                    'trailer' => $phim['p_trailer'],
                    'poster' => $phim['p_poster'] ?: '/static/imgs/default-poster.jpg',
                    'status' => $phim['p_trangthai'],
                    'director' => $phim['p_daodien'],
                    'actors' => $phim['p_dienvien']
                ];
            }
            
            error_log("Formatted movies: " . json_encode($phimDangChieu));
            
            // ✅ Render với đúng tên biến
            echo $this->blade->render('users-views.Phim.PhimDangChieu', [
                'activePage' => 'movies',
                'phimDangChieu' => $phimDangChieu // ✅ Đúng tên biến
            ]);
            
        } catch (Exception $e) {
            error_log("Error in phimDangChieu: " . $e->getMessage());
            $_SESSION['error_message'] = 'Có lỗi xảy ra khi tải danh sách phim!';
            header('Location: /');
            exit;
        }
    }

    /**
     * Hiển thị trang phim sắp chiếu
     */
    public function phimSapChieu()
    {
        try {
            header('Content-Type: text/html; charset=UTF-8');
            // Lấy danh sách phim sắp chiếu
            $phimList = $this->phimModel->getPhimByStatus('coming_soon');
            
            // Format dữ liệu
            $phimSapChieu = [];
            foreach ($phimList as $phim) {
                $phimSapChieu[] = [
                    'id' => $phim['p_maphim'],
                    'name' => $phim['p_tenphim'],
                    'genre' => $phim['p_theloai'],
                    'duration' => $phim['p_thoiluong'],
                    'release' => $phim['p_phathanh'],
                    'desc' => $phim['p_mota'],
                    'trailer' => $phim['p_trailer'],
                    'poster' => $phim['p_poster'] ?: '/static/imgs/default-poster.jpg',
                    'status' => $phim['p_trangthai'],
                    'director' => $phim['p_daodien'],
                    'actors' => $phim['p_dienvien']
                ];
            }
            
            echo $this->blade->render('users-views.Phim.PhimSapChieu', [
                'activePage' => 'movies',
                'phimSapChieu' => $phimSapChieu
            ]);
            
        } catch (Exception $e) {
            error_log("Error in phimSapChieu: " . $e->getMessage());
            $_SESSION['error_message'] = 'Có lỗi xảy ra khi tải danh sách phim!';
            header('Location: /');
            exit;
        }
    }

    /**
     * Hiển thị chi tiết phim
     */
    public function chiTietPhim()
    {
        try {
            header('Content-Type: text/html; charset=UTF-8');
            $phimId = $_GET['id'] ?? '';
            
            error_log("=== CHI TIET PHIM DEBUG ===");
            error_log("Phim ID: " . $phimId);
            
            if (empty($phimId)) {
                error_log("Empty phim ID");
                $_SESSION['error_message'] = 'Không tìm thấy thông tin phim!';
                header('Location: /phim-dang-chieu');
                exit;
            }
    
            // Lấy thông tin phim
            $phim = $this->phimModel->getPhimById($phimId);
            error_log("Phim data: " . json_encode($phim));
            
            if (!$phim) {
                error_log("Phim not found in database");
                $_SESSION['error_message'] = 'Phim không tồn tại!';
                header('Location: /phim-dang-chieu');
                exit;
            }
    
            // ✅ Format dữ liệu phim - sửa poster
            $phimData = [
                'id' => $phim['p_maphim'],
                'name' => $phim['p_tenphim'],
                'poster' => $phim['p_poster'] ?: '/static/imgs/no-poster.jpg', // ✅ Sửa đây
                'genre' => $phim['p_theloai'],
                'director' => $phim['p_daodien'],
                'actors' => $phim['p_dienvien'],
                'duration' => $phim['p_thoiluong'],
                'release' => $phim['p_phathanh'],
                'desc' => $phim['p_mota'],
                'trailer' => $phim['p_trailer'],
                'status' => $phim['p_trangthai']
            ];
            
            error_log("Formatted phim data: " . json_encode($phimData));
    
            // Lấy lịch chiếu của phim
            $lichChieuList = $this->lichChieuModel->getLichChieuByPhim($phimId);
            error_log("LichChieu list count: " . count($lichChieuList));
            
            // Tổ chức lịch chiếu theo ngày
            $lichChieuByDate = $this->organizeLichChieuByDate($lichChieuList);
            error_log("Organized lichChieu: " . json_encode($lichChieuByDate));
    
            echo $this->blade->render('users-views.Phim.ChiTietPhim', [
                'activePage' => 'movies',
                'phim' => $phimData,
                'lichChieuByDate' => $lichChieuByDate
            ]);
    
        } catch (Exception $e) {
            error_log("=== ERROR IN CHI TIET PHIM ===");
            error_log("Error message: " . $e->getMessage());
            error_log("Error trace: " . $e->getTraceAsString());
            
            $_SESSION['error_message'] = 'Có lỗi xảy ra khi tải thông tin phim: ' . $e->getMessage();
            header('Location: /phim-dang-chieu');
            exit;
        }
    }

    /**
     * Hiển thị trang chọn ghế
     */
    public function chonGhe()
    {
        try {
            header('Content-Type: text/html; charset=UTF-8');
            $lichChieuId = $_GET['lich_chieu'] ?? '';
            
            error_log("=== CHON GHE DEBUG ===");
            error_log("Lich Chieu ID: " . $lichChieuId);
            
            if (empty($lichChieuId)) {
                $_SESSION['error_message'] = 'Không tìm thấy thông tin lịch chiếu!';
                header('Location: /phim-dang-chieu');
                exit;
            }

            // Lấy thông tin lịch chiếu với phòng chiếu
            $lichChieu = $this->lichChieuModel->getLichChieuWithPhongChieu($lichChieuId);
            error_log("Lich Chieu data: " . json_encode($lichChieu));
            
            if (!$lichChieu) {
                $_SESSION['error_message'] = 'Lịch chiếu không tồn tại!';
                header('Location: /phim-dang-chieu');
                exit;
            }

            
            if (!in_array($lichChieu['lc_trangthai'], ['Sắp chiếu', 'Đang chiếu'])) {
                $_SESSION['error_message'] = 'Suất chiếu này không thể đặt vé! Trạng thái: ' . $lichChieu['lc_trangthai'];
                header('Location: /chi-tiet-phim?id=' . $lichChieu['p_maphim']);
                exit;
            }

            // Lấy danh sách ghế của phòng
            $phongChieuModel = new \App\Models\PhongChieu();
            $gheList = $phongChieuModel->getGheByPhongChieu($lichChieu['pc_maphongchieu']);
            error_log("Ghe list count: " . count($gheList));
            
            
            if (empty($gheList)) {
                $_SESSION['error_message'] = 'Phòng chiếu chưa được thiết lập sơ đồ ghế!';
                header('Location: /chi-tiet-phim?id=' . $lichChieu['p_maphim']);
                exit;
            }
            
            // Lấy danh sách ghế đã đặt cho lịch chiếu này
            $gheDaDat = $phongChieuModel->getGheDaDatByLichChieu($lichChieuId);
            error_log("Ghe da dat: " . json_encode($gheDaDat));
            
            // Tổ chức ghế theo hàng (A, B, C...)
            $gheByRow = $this->organizeGheByRow($gheList, $gheDaDat);
            error_log("Ghe by row: " . json_encode($gheByRow));
            
            // Tính giá vé theo loại ghế
            $giaBanVe = $this->getGiaBanVeTheoPhong($lichChieu);

            echo $this->blade->render('users-views.Phim.ChonGhe', [
                'activePage' => 'movies',
                'lichChieu' => $lichChieu,
                'gheByRow' => $gheByRow,
                'giaBanVe' => $giaBanVe,
                'lichChieuId' => $lichChieuId
            ]);

        } catch (Exception $e) {
            error_log("=== ERROR IN CHON GHE ===");
            error_log("Error message: " . $e->getMessage());
            error_log("Error trace: " . $e->getTraceAsString());
            
            $_SESSION['error_message'] = 'Có lỗi xảy ra khi tải trang chọn ghế: ' . $e->getMessage();
            header('Location: /phim-dang-chieu');
            exit;
        }
    }

    /**
     * Nhóm lịch chiếu theo ngày
     */
    private function groupLichChieuByDate($lichChieuList)
    {
        $grouped = [];
        
        foreach ($lichChieuList as $lichChieu) {
            $ngayChieu = $lichChieu['lc_ngaychieu'];
            $gioChieu = date('H:i', strtotime($lichChieu['lc_giobatdau']));
            
            if (!isset($grouped[$ngayChieu])) {
                $grouped[$ngayChieu] = [];
            }
            
            $grouped[$ngayChieu][] = [
                'id' => $lichChieu['lc_malichchieu'],
                'gio' => $gioChieu,
                'trangthai' => $lichChieu['lc_trangthai']
            ];
        }

        // Sắp xếp theo ngày và giờ
        ksort($grouped);
        foreach ($grouped as &$gioList) {
            usort($gioList, function($a, $b) {
                return strcmp($a['gio'], $b['gio']);
            });
        }

        return $grouped;
    }

    /**
     * Tổ chức ghế theo hàng
     */
    private function organizeGheByRow($gheList, $gheDaDat)
    {
        $organized = [];
        
        foreach ($gheList as $ghe) {
            try {
               
                $maGhe = $ghe['g_maghe'];
                
                // Format 1: PC001_A01
                if (strpos($maGhe, '_') !== false) {
                    $parts = explode('_', $maGhe);
                    if (count($parts) >= 2) {
                        $rowAndSeat = $parts[1]; // A01, B01, etc.
                        $row = substr($rowAndSeat, 0, 1); // A, B, C...
                        $seatNumber = substr($rowAndSeat, 1); // 01, 02, 03...
                    }
                } 
                // Format 2: A01, B01 (direct)
                elseif (preg_match('/^([A-Z])(\d+)$/', $maGhe, $matches)) {
                    $row = $matches[1];
                    $seatNumber = $matches[2];
                }
                // Format 3: Fallback
                else {
                    // Thử extract từ 2 ký tự đầu
                    $row = substr($maGhe, 0, 1);
                    $seatNumber = substr($maGhe, 1);
                }
                
                if (!isset($organized[$row])) {
                    $organized[$row] = [];
                }
                
                // Xác định trạng thái ghế
                $trangThai = 'available';
                if (in_array($ghe['g_maghe'], $gheDaDat)) {
                    $trangThai = 'sold';
                } elseif (isset($ghe['g_trangthai']) && $ghe['g_trangthai'] !== 'available') {
                    $trangThai = $ghe['g_trangthai'];
                }
                
                $organized[$row][] = [
                    'ma_ghe' => $ghe['g_maghe'],
                    'loai_ghe' => $ghe['g_loaighe'] ?? 'normal',
                    'trang_thai' => $trangThai,
                    'seat_number' => $seatNumber,
                    'display_code' => $row . str_pad($seatNumber, 2, '0', STR_PAD_LEFT)
                ];
                
            } catch (Exception $seatError) {
                error_log("Error processing seat: " . $ghe['g_maghe'] . " - " . $seatError->getMessage());
                continue; // Skip seat này
            }
        }
        
        // Sắp xếp theo thứ tự alphabet cho row và số ghế
        ksort($organized);
        foreach ($organized as &$seats) {
            usort($seats, function($a, $b) {
                return (int)$a['seat_number'] - (int)$b['seat_number'];
            });
        }
        
        return $organized;
    }

    /**
     * Lấy bảng giá vé theo phòng chiếu
     */
    public function getGiaBanVeTheoPhong($lichChieu)
    {
        try {
            $maPhongChieu = $lichChieu['pc_maphongchieu'] ?? '';
            $tenPhongChieu = $lichChieu['pc_tenphong'] ?? '';
            $loaiPhongChieu = $lichChieu['pc_loaiphong'] ?? '';
            
            error_log("=== GET PRICE FOR ROOM ===");
            error_log("Ma phong: " . $maPhongChieu);
            error_log("Ten phong: " . $tenPhongChieu);
            error_log("Loai phong: " . $loaiPhongChieu);
            
            // ✅ Tìm loại vé theo thứ tự ưu tiên
            $loaiVe = null;
            
            // 1. Tìm theo mã phòng chiếu
            $loaiVe = $this->loaiVeModel->getLoaiVeById($maPhongChieu);
            
            // 2. Nếu không tìm thấy, tìm theo loại phòng
            if (!$loaiVe) {
                $allLoaiVe = $this->loaiVeModel->getAllLoaiVeSimple();
                
                foreach ($allLoaiVe as $lv) {
                    $tenLoaiVe = strtolower($lv['lv_tenloaive']);
                    $maLoaiVe = strtolower($lv['lv_maloaive']);
                    
                    // So sánh với loại phòng
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
            
            if ($loaiVe) {
                $giaCoban = (int)$loaiVe['lv_giatien'];
                
                error_log("Found loai_ve: " . $loaiVe['lv_tenloaive'] . " with base price: " . $giaCoban);
                
                // ✅ Tính giá theo loại ghế = giá cơ bản + phụ phí loại ghế
                $giaBanVe = [
                    'normal' => $giaCoban,                    // Giá cơ bản
                    'vip' => $giaCoban + 20000,              // Cộng 20k cho VIP
                    'luxury' => $giaCoban + 50000,           // Cộng 50k cho Luxury
                    'couple' => $giaCoban + 80000            // Cộng 80k cho Couple
                ];
                
                error_log("Calculated prices: " . json_encode($giaBanVe));
                return $giaBanVe;
            }
            
            // Fallback cuối cùng
            error_log("No loai_ve found, using hardcoded default");
            return $this->getGiaBanVeDefault();
            
        } catch (Exception $e) {
            error_log("Error in getGiaBanVeTheoPhong: " . $e->getMessage());
            return $this->getGiaBanVeDefault();
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
     * Lấy bảng giá vé (old method - giờ gọi method mới)
     */
    private function getGiaBanVe($lichChieu = null)
    {
        if ($lichChieu) {
            return $this->getGiaBanVeTheoPhong($lichChieu);
        }
        
        return $this->getGiaBanVeDefault();
    }

    /**
     * Tổ chức lịch chiếu theo ngày
     */
    private function organizeLichChieuByDate($lichChieuList)
    {
        $organized = [];
        
        foreach ($lichChieuList as $lichChieu) {
            try {
                $ngay = $lichChieu['lc_ngaychieu'];
                
                
                $gioChieuRaw = $lichChieu['lc_giobatdau'];
                
                // Nếu có cả ngày và giờ trong field
                if (strpos($gioChieuRaw, ' ') !== false) {
                    $gio = date('H:i', strtotime($gioChieuRaw));
                } else {
                    // Nếu chỉ có giờ
                    $gio = date('H:i', strtotime($gioChieuRaw));
                }
                
                
                $trangthai = 'Sắp chiếu';
                
                try {
                    $now = new \DateTime();
                    
                    // Tạo datetime cho lịch chiếu
                    if (strpos($gioChieuRaw, ' ') !== false) {
                        // Format: "2025-07-07 09:00:00"
                        $lichChieuTime = new \DateTime($gioChieuRaw);
                    } else {
                        // Format: "09:00:00"
                        $lichChieuTime = new \DateTime($ngay . ' ' . $gioChieuRaw);
                    }
                    
                    if ($lichChieuTime < $now) {
                        $trangthai = 'Đã chiếu';
                    } else {
                        // Tạo copy để avoid modifying original
                        $nowPlus15 = clone $now;
                        $nowPlus15->add(new \DateInterval('PT15M'));
                        
                        if ($lichChieuTime <= $nowPlus15) {
                            $trangthai = 'Đang chiếu';
                        }
                    }
                } catch (Exception $dateError) {
                    error_log("Error processing datetime: " . $dateError->getMessage());
                    // Keep default "Sắp chiếu"
                }
                
                if (!isset($organized[$ngay])) {
                    $organized[$ngay] = [];
                }
                
                $organized[$ngay][] = [
                    'id' => $lichChieu['lc_malichchieu'],
                    'gio' => $gio,
                    'trangthai' => $trangthai,
                    'phong' => $lichChieu['pc_maphongchieu'] ?? 'N/A'
                ];
                
            } catch (Exception $itemError) {
                error_log("Error processing lich chieu item: " . $itemError->getMessage());
                continue; 
            }
        }
        
        // Sắp xếp theo ngày
        ksort($organized);
        
        // Sắp xếp theo giờ trong mỗi ngày
        foreach ($organized as &$gioList) {
            usort($gioList, function($a, $b) {
                return strcmp($a['gio'], $b['gio']);
            });
        }
        
        return $organized;
    }
}