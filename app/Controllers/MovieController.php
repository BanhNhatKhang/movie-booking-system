<?php

namespace App\Controllers;

use App\Models\Phim;
use App\Models\LichChieu; 
use App\Models\LoaiVe;
use App\Models\Ghe;
use Jenssegers\Blade\Blade;
use Exception;

class MovieController
{
    private $phimModel;
    private $lichChieuModel; 
    private $loaiVeModel;
    private $gheModel;
    private $blade;

    public function __construct()
    {
        $this->phimModel = new Phim();
        $this->lichChieuModel = new LichChieu(); 
        $this->loaiVeModel = new LoaiVe();
        $this->gheModel = new Ghe();
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
            
            $phimList = $this->phimModel->getPhimByStatus('active');
            error_log("Found " . count($phimList) . " active movies");
            
            $phimDangChieu = [];
            foreach ($phimList as $phim) {
                $phimDangChieu[] = [
                    'id' => $phim['p_maphim'],
                    'name' => $phim['p_tenphim'],
                    'slug' => $this->createSlug($phim['p_tenphim']),
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
            
            echo $this->blade->render('users-views.Phim.PhimDangChieu', [
                'activePage' => 'movies',
                'phimDangChieu' => $phimDangChieu
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
            $phimList = $this->phimModel->getPhimByStatus('coming_soon');
            
            $phimSapChieu = [];
            foreach ($phimList as $phim) {
                $phimSapChieu[] = [
                    'id' => $phim['p_maphim'],
                    'name' => $phim['p_tenphim'],
                    'slug' => $this->createSlug($phim['p_tenphim']), 
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
     * Hiển thị chi tiết phim với slug hoặc ID
     */
    public function chiTietPhim()
    {
        try {
            header('Content-Type: text/html; charset=UTF-8');
            
            // Lấy slug hoặc ID từ URL
            $slug = $_GET['slug'] ?? '';
            $phimId = $_GET['id'] ?? '';
            
            // error_log("=== CHI TIET PHIM DEBUG ===");
            // error_log("Slug: " . $slug);
            // error_log("Phim ID: " . $phimId);
            
            $phim = null;
            
            // Tìm phim theo slug trước, nếu không có thì tìm theo ID
            if (!empty($slug)) {
                // error_log("Searching by slug: " . $slug);
                $phim = $this->phimModel->getPhimBySlug($slug);
            } elseif (!empty($phimId)) {
                // error_log("Searching by ID: " . $phimId);
                $phim = $this->phimModel->getPhimById($phimId);
            }
            
            if (!$phim) {
                // error_log("Phim not found");
                $_SESSION['error_message'] = 'Phim không tồn tại!';
                header('Location: /phim-dang-chieu');
                exit;
            }

            $phimData = [
                'id' => $phim['p_maphim'],
                'name' => $phim['p_tenphim'],
                'slug' => $this->createSlug($phim['p_tenphim']),
                'poster' => $phim['p_poster'] ?: '/static/imgs/no-poster.jpg',
                'genre' => $phim['p_theloai'],
                'director' => $phim['p_daodien'],
                'actors' => $phim['p_dienvien'],
                'duration' => $phim['p_thoiluong'],
                'release' => $phim['p_phathanh'],
                'desc' => $phim['p_mota'],
                'trailer' => $phim['p_trailer'],
                'status' => $phim['p_trangthai']
            ];
            
            // error_log("Formatted phim data: " . json_encode($phimData));

            $lichChieuList = $this->lichChieuModel->getLichChieuByPhim($phim['p_maphim']);
            // error_log("LichChieu list count: " . count($lichChieuList));
            
            $lichChieuByDate = $this->organizeLichChieuByDate($lichChieuList);
            // error_log("Organized lichChieu: " . json_encode($lichChieuByDate));

            echo $this->blade->render('users-views.Phim.ChiTietPhim', [
                'activePage' => 'movies',
                'phim' => $phimData,
                'lichChieuByDate' => $lichChieuByDate
            ]);

        } catch (Exception $e) {
            // error_log("=== ERROR IN CHI TIET PHIM ===");
            // error_log("Error message: " . $e->getMessage());
            // error_log("Error trace: " . $e->getTraceAsString());
            
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
            
            // Lấy slug hoặc ID từ URL
            $showtimeSlug = $_GET['showtime_slug'] ?? '';
            $lichChieuId = $_GET['lich_chieu'] ?? '';
            
            // error_log("=== CHON GHE DEBUG ===");
            // error_log("Showtime Slug: " . $showtimeSlug);
            // error_log("Lich Chieu ID: " . $lichChieuId);
            
            $lichChieu = null;
            
            // Tìm lịch chiếu theo slug trước, nếu không có thì tìm theo ID
            if (!empty($showtimeSlug)) {
                $lichChieu = $this->lichChieuModel->getLichChieuBySlug($showtimeSlug);
                if ($lichChieu) {
                    $lichChieuId = $lichChieu['lc_malichchieu'];
                }
            } elseif (!empty($lichChieuId)) {
                $lichChieu = $this->lichChieuModel->getLichChieuWithPhongChieu($lichChieuId);
            }
            
            if (!$lichChieu) {
                // error_log("Lich chieu not found");
                $_SESSION['error_message'] = 'Không tìm thấy thông tin lịch chiếu!';
                header('Location: /phim-dang-chieu');
                exit;
            }

            if (!in_array($lichChieu['lc_trangthai'], ['Sắp chiếu', 'Đang chiếu'])) {
                $_SESSION['error_message'] = 'Suất chiếu này không thể đặt vé! Trạng thái: ' . $lichChieu['lc_trangthai'];
                header('Location: /phim/' . $this->createSlug($lichChieu['p_tenphim']));
                exit;
            }

            // LẤY GHẾ TỪ MODEL GHE
            $gheList = $this->gheModel->getByPhongChieu($lichChieu['pc_maphongchieu']);
            // error_log("Ghe list count: " . count($gheList));
            
            if (empty($gheList)) {
                $_SESSION['error_message'] = 'Phòng chiếu chưa được thiết lập sơ đồ ghế!';
                header('Location: /phim/' . $this->createSlug($lichChieu['p_tenphim']));
                exit;
            }
            
            // Lấy danh sách ghế đã đặt cho lịch chiếu này
            $phongChieuModel = new \App\Models\PhongChieu();
            $gheDaDat = $phongChieuModel->getGheDaDatByLichChieu($lichChieuId);
            // error_log("Ghe da dat: " . json_encode($gheDaDat));
            
            // Tổ chức ghế theo hàng (A, B, C...)
            $gheByRow = $this->organizeGheByRow($gheList, $gheDaDat);
            // error_log("Ghe by row: " . json_encode($gheByRow));
            
            // TÍNH GIÁ VÉ THEO LOẠI GHẾ
            $giaBanVe = $this->getGiaBanVeTheoPhong($lichChieu);

            echo $this->blade->render('users-views.Phim.ChonGhe', [
                'activePage' => 'movies',
                'lichChieu' => $lichChieu,
                'gheByRow' => $gheByRow,
                'giaBanVe' => $giaBanVe,
                'lichChieuId' => $lichChieuId
            ]);

        } catch (Exception $e) {
            // error_log("=== ERROR IN CHON GHE ===");
            // error_log("Error message: " . $e->getMessage());
            // error_log("Error trace: " . $e->getTraceAsString());
            
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
                    'display_code' => $row . str_pad($seatNumber, 2, '0', STR_PAD_LEFT),
                    'gia_ghe' => (int)($ghe['g_giaghe'] ?? 0)
                ];
                
            } catch (Exception $seatError) {
                // error_log("Error processing seat: " . $ghe['g_maghe'] . " - " . $seatError->getMessage());
                continue;
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
     * Lấy bảng giá vé theo phòng chiếu - SỬ DỤNG g_giaghe TỪ DATABASE
     */
    public function getGiaBanVeTheoPhong($lichChieu)
    {
        try {
            $maPhongChieu = $lichChieu['pc_maphongchieu'] ?? '';
            $tenPhongChieu = $lichChieu['pc_tenphong'] ?? '';
            $loaiPhongChieu = $lichChieu['pc_loaiphong'] ?? '';
            
            // error_log("=== GET PRICE FOR ROOM (MOVIECONTROLLER) ===");
            // error_log("Ma phong: " . $maPhongChieu);
            // error_log("Ten phong: " . $tenPhongChieu);
            // error_log("Loai phong: " . $loaiPhongChieu);
            
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
                    // error_log("Using first available loai_ve as fallback");
                }
            }
            
            if ($loaiVe) {
                $giaCoban = (int)$loaiVe['lv_giatien'];
                // error_log("Found loai_ve: " . $loaiVe['lv_tenloaive'] . " with base price: " . $giaCoban);
                
                // ✅ LẤY GHẾ CỦA PHÒNG CHIẾU ĐỂ TÍNH GIÁ TỪ g_giaghe
                $gheList = $this->gheModel->getByPhongChieu($maPhongChieu);
                // error_log("Found " . count($gheList) . " seats in room");
                
                // ✅ TÍNH GIÁ THEO LOẠI GHẾ = GIÁ CƠ BẢN + g_giaghe
                $giaBanVe = [];
                foreach ($gheList as $ghe) {
                    $loaiGhe = $ghe['g_loaighe'];
                    $giaGhe = (int)($ghe['g_giaghe'] ?? 0);
                    $tongGia = $giaCoban + $giaGhe;
                    
                    // Lưu giá theo loại ghế (lấy giá đại diện)
                    if (!isset($giaBanVe[$loaiGhe]) || $tongGia > $giaBanVe[$loaiGhe]) {
                        $giaBanVe[$loaiGhe] = $tongGia;
                    }
                    
                    // error_log("Ghe {$ghe['g_maghe']} ({$loaiGhe}): {$giaCoban} + {$giaGhe} = {$tongGia}");
                }
                
                // NẾU KHÔNG CÓ GHẾ TRONG PHÒNG, DÙNG GIÁ MẶC ĐỊNH
                if (empty($giaBanVe)) {
                    // error_log("No seats found, using default prices");
                    $giaBanVe = [
                        'normal' => $giaCoban,
                        'vip' => $giaCoban + 20000,
                        'luxury' => $giaCoban + 50000
                    ];
                }
                
                // error_log("Calculated prices (from database): " . json_encode($giaBanVe));
                return $giaBanVe;
            }
            
            // Fallback cuối cùng
            // error_log("No loai_ve found, using hardcoded default");
            return $this->getGiaBanVeDefault();
            
        } catch (Exception $e) {
            // error_log("Error in getGiaBanVeTheoPhong: " . $e->getMessage());
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
     * ✅ Tạo slug từ tên phim
     */
    private function createSlug($text) 
    {
        $text = $this->removeVietnameseAccents($text);
        $text = strtolower($text);
        $text = preg_replace('/[^a-z0-9]+/', '-', $text);
        $text = trim($text, '-');
        return $text;
    }

    /**
     * ✅ Loại bỏ dấu tiếng Việt
     */
    private function removeVietnameseAccents($str) 
    {
        $accents = [
            'à', 'á', 'ạ', 'ả', 'ã', 'â', 'ầ', 'ấ', 'ậ', 'ẩ', 'ẫ', 'ă', 'ằ', 'ắ', 'ặ', 'ẳ', 'ẵ',
            'è', 'é', 'ẹ', 'ẻ', 'ẽ', 'ê', 'ề', 'ế', 'ệ', 'ể', 'ễ',
            'ì', 'í', 'ị', 'ỉ', 'ĩ',
            'ò', 'ó', 'ọ', 'ỏ', 'õ', 'ô', 'ồ', 'ố', 'ộ', 'ổ', 'ỗ', 'ơ', 'ờ', 'ớ', 'ợ', 'ở', 'ỡ',
            'ù', 'ú', 'ụ', 'ủ', 'ũ', 'ư', 'ừ', 'ứ', 'ự', 'ử', 'ữ',
            'ỳ', 'ý', 'ỵ', 'ỷ', 'ỹ',
            'đ'
        ];
        
        $noAccents = [
            'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a',
            'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e',
            'i', 'i', 'i', 'i', 'i',
            'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o',
            'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u',
            'y', 'y', 'y', 'y', 'y',
            'd'
        ];
        
        return str_replace($accents, $noAccents, $str);
    }

    /**
     * ✅ Tổ chức lịch chiếu theo ngày với slug URLs
     */
    private function organizeLichChieuByDate($lichChieuList)
    {
        $organized = [];
        
        foreach ($lichChieuList as $lichChieu) {
            try {
                // error_log("=== ORGANIZING LICH CHIEU ===");
                // error_log("LichChieu data: " . json_encode($lichChieu));
                
                $ngay = $lichChieu['lc_ngaychieu'];
                $gioChieuRaw = $lichChieu['lc_giobatdau'];
                
                // Fix time parsing - chỉ lấy time part
                if (strpos($gioChieuRaw, ' ') !== false) {
                    // Full datetime: "2025-07-19 10:45:00" -> chỉ lấy "10:45:00"
                    $timePart = explode(' ', $gioChieuRaw)[1];
                    $gio = date('H:i', strtotime($timePart));
                } else {
                    // Time only: "10:45:00"
                    $gio = date('H:i', strtotime($gioChieuRaw));
                }
                
                // ✅ Status calculation - sử dụng datetime đúng format
                $trangthai = 'Sắp chiếu';
                try {
                    $now = new \DateTime();
                    
                    // ✅ Tạo datetime từ date + time riêng biệt
                    $lichChieuDateTime = new \DateTime($ngay . ' ' . $gio);
                    
                    if ($lichChieuDateTime < $now) {
                        $trangthai = 'Đã chiếu';
                    } else {
                        $nowPlus15 = clone $now;
                        $nowPlus15->add(new \DateInterval('PT15M'));
                        
                        if ($lichChieuDateTime <= $nowPlus15) {
                            $trangthai = 'Đang chiếu';
                        }
                    }
                } catch (Exception $dateError) {
                    // error_log("Date parsing error: " . $dateError->getMessage());
                    $trangthai = 'Sắp chiếu'; // fallback
                }
                
                if (!isset($organized[$ngay])) {
                    $organized[$ngay] = [];
                }
                
                // Tạo slug với full data (bao gồm p_tenphim)
                $slug = $this->lichChieuModel->createLichChieuSlug($lichChieu);
                // error_log("Generated slug for UI: " . $slug);
                
                $organized[$ngay][] = [
                    'id' => $lichChieu['lc_malichchieu'],
                    'gio' => $gio,
                    'trangthai' => $trangthai,
                    'phong' => $lichChieu['pc_maphongchieu'] ?? 'N/A',
                    'slug' => $slug
                ];
                
                // error_log("Added to organized: " . json_encode(end($organized[$ngay])));
                
            } catch (Exception $itemError) {
                // error_log("Error processing lich chieu item: " . $itemError->getMessage());
                continue; 
            }
        }
        
        // Sort by date and time
        ksort($organized);
        foreach ($organized as &$gioList) {
            usort($gioList, function($a, $b) {
                return strcmp($a['gio'], $b['gio']);
            });
        }
        
        // error_log("Final organized lichChieu: " . json_encode($organized));
        return $organized;
    }
}