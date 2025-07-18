<?php
namespace App\Models;

use App\Core\BaseModel;
use Exception;
use PDO;

class Phim extends BaseModel
{
    protected $table = 'phim';
    protected $primaryKey = 'p_maphim';

    /**
     * Lấy tất cả phim
     */
    public function getAllPhim($search = '', $limit = 10, $offset = 0, $status = '')
    {
        try {
            $sql = "SELECT * FROM {$this->table} WHERE 1=1";
            $params = [];

            // Search functionality
            if (!empty($search)) {
                $sql .= " AND (p_tenphim ILIKE ? OR p_theloai ILIKE ? OR p_daodien ILIKE ? OR p_dienvien ILIKE ?)";
                $searchTerm = "%{$search}%";
                $params[] = $searchTerm;
                $params[] = $searchTerm;
                $params[] = $searchTerm;
                $params[] = $searchTerm;
            }

            // Filter by status
            if (!empty($status)) {
                $sql .= " AND p_trangthai = ?";
                $params[] = $status;
            }

            $sql .= " ORDER BY p_phathanh DESC, p_maphim DESC LIMIT ? OFFSET ?";
            $params[] = $limit;
            $params[] = $offset;

            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error getting all phim: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Đếm tổng số phim (cho phân trang)
     */
    public function countPhim($search = '', $status = '')
    {
        try {
            $sql = "SELECT COUNT(*) as total FROM {$this->table} WHERE 1=1";
            $params = [];

            if (!empty($search)) {
                $sql .= " AND (p_tenphim ILIKE ? OR p_theloai ILIKE ? OR p_daodien ILIKE ? OR p_dienvien ILIKE ?)";
                $searchTerm = "%{$search}%";
                $params[] = $searchTerm;
                $params[] = $searchTerm;
                $params[] = $searchTerm;
                $params[] = $searchTerm;
            }

            if (!empty($status)) {
                $sql .= " AND p_trangthai = ?";
                $params[] = $status;
            }

            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'] ?? 0;
        } catch (Exception $e) {
            error_log("Error counting phim: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Lấy phim theo ID
     */
    public function getPhimById($id)
    {
        try {
            $sql = "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = ?";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error getting phim by ID: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Lấy phim theo trạng thái
     */
    public function getPhimByStatus($status)
    {
        try {
            $sql = "SELECT * FROM {$this->table} WHERE p_trangthai = ? ORDER BY p_phathanh DESC";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$status]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error getting phim by status: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Thêm phim mới - đơn giản hóa
     */
    public function createPhim($data)
    {
        try {
            $sql = "INSERT INTO {$this->table} (
                p_maphim, p_tenphim, p_theloai, p_thoiluong, p_phathanh, 
                p_mota, p_trailer, p_trangthai, p_dienvien, p_daodien, p_poster
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $params = [
                $data['p_maphim'],
                $data['p_tenphim'],
                $data['p_theloai'],
                $data['p_thoiluong'],
                $data['p_phathanh'],
                $data['p_mota'] ?? '',
                $data['p_trailer'] ?? '',
                $data['p_trangthai'] ?? 'active',
                $data['p_dienvien'] ?? '',
                $data['p_daodien'] ?? '',
                $data['p_poster'] ?? '' // Trực tiếp lưu đường dẫn
            ];

            $stmt = $this->db->prepare($sql);
            return $stmt->execute($params);
        } catch (Exception $e) {
            error_log("Error creating phim: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Cập nhật phim - đơn giản hóa
     */
    public function updatePhim($id, $data)
    {
        try {
            $sql = "UPDATE {$this->table} SET 
                p_tenphim = ?, p_theloai = ?, p_thoiluong = ?, p_phathanh = ?, 
                p_mota = ?, p_trailer = ?, p_trangthai = ?, p_dienvien = ?, 
                p_daodien = ?, p_poster = ?
                WHERE {$this->primaryKey} = ?";

            $params = [
                $data['p_tenphim'],
                $data['p_theloai'],
                $data['p_thoiluong'],
                $data['p_phathanh'],
                $data['p_mota'] ?? '',
                $data['p_trailer'] ?? '',
                $data['p_trangthai'] ?? 'active',
                $data['p_dienvien'] ?? '',
                $data['p_daodien'] ?? '',
                $data['p_poster'] ?? '',
                $id
            ];

            $stmt = $this->db->prepare($sql);
            return $stmt->execute($params);
        } catch (Exception $e) {
            error_log("Error updating phim: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Thay đổi trạng thái phim
     */
    public function updateStatus($id, $status)
    {
        try {
            $allowedStatuses = ['active', 'inactive', 'coming_soon', 'ended', 'suspended'];
            if (!in_array($status, $allowedStatuses)) {
                return false;
            }

            $sql = "UPDATE {$this->table} SET p_trangthai = ? WHERE {$this->primaryKey} = ?";
            
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$status, $id]);
        } catch (Exception $e) {
            error_log("Error updating phim status: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Kiểm tra mã phim đã tồn tại
     */
    public function checkPhimCodeExists($maphim, $excludeId = null)
    {
        try {
            $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE p_maphim = ?";
            $params = [$maphim];

            if ($excludeId) {
                $sql .= " AND {$this->primaryKey} != ?";
                $params[] = $excludeId;
            }

            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return ($result['count'] ?? 0) > 0;
        } catch (Exception $e) {
            error_log("Error checking phim code exists: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Kiểm tra tên phim đã tồn tại
     */
    public function checkPhimNameExists($tenphim, $excludeId = null)
    {
        try {
            $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE p_tenphim = ?";
            $params = [$tenphim];

            if ($excludeId) {
                $sql .= " AND {$this->primaryKey} != ?";
                $params[] = $excludeId;
            }

            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return ($result['count'] ?? 0) > 0;
        } catch (Exception $e) {
            error_log("Error checking phim name exists: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Lấy phim đang chiếu
     */
    public function getActivePhim()
    {
        return $this->getPhimByStatus('active');
    }

    /**
     * Lấy phim sắp chiếu
     */
    public function getComingSoonPhim()
    {
        return $this->getPhimByStatus('coming_soon');
    }

    /**
     * Tìm phim theo slug (dựa trên tên phim)
     */
    public function getPhimBySlug($slug)
    {
        try {
            error_log("Searching for slug: " . $slug);
            
            // Tạo slug từ tên phim trong database để so sánh
            $sql = "SELECT * FROM {$this->table} ORDER BY p_maphim";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $allPhim = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            foreach ($allPhim as $phim) {
                $phimSlug = $this->createSlug($phim['p_tenphim']);
                error_log("Comparing: '$phimSlug' with '$slug' for movie: " . $phim['p_tenphim']);
                
                if ($phimSlug === $slug) {
                    error_log("Found match for slug: " . $slug);
                    return $phim;
                }
            }
            
            error_log("No match found for slug: " . $slug);
            return null;
        } catch (Exception $e) {
            error_log("Error getting phim by slug: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Tạo slug từ tên phim
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
     * Loại bỏ dấu tiếng Việt
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
     * Tìm lịch chiếu theo slug
     */
    public function getLichChieuBySlug($slug)
    {
        try {
            error_log("Searching for lichChieu slug: " . $slug);
            
            // Lấy tất cả lịch chiếu với thông tin phim
            $sql = "SELECT 
                    lc.lc_malichchieu,
                    lc.lc_ngaychieu,
                    lc.lc_giobatdau,
                    lc.lc_trangthai,
                    lc.p_maphim,
                    lc.pc_maphongchieu,
                    p.p_tenphim,
                    p.p_poster,
                    p.p_theloai,
                    p.p_thoiluong,
                    p.p_daodien,
                    p.p_dienvien,
                    p.p_mota,
                    pc.pc_tenphong,
                    pc.pc_loaiphong
                FROM lich_chieu lc
                INNER JOIN phim p ON lc.p_maphim = p.p_maphim
                INNER JOIN phong_chieu pc ON lc.pc_maphongchieu = pc.pc_maphongchieu
                WHERE lc.lc_ngaychieu >= CURDATE() - INTERVAL 1 DAY";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $allLichChieu = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            foreach ($allLichChieu as $lichChieu) {
                // ✅ Tạo slug cho từng lịch chiếu và so sánh
                $generatedSlug = $this->createLichChieuSlug($lichChieu);
                
                error_log("Comparing: '{$generatedSlug}' with '{$slug}' for showtime: {$lichChieu['lc_malichchieu']}");
                
                if ($generatedSlug === $slug) {
                    error_log("Found match for slug: " . $slug);
                    return $lichChieu;
                }
            }
            
            error_log("No match found for slug: " . $slug);
            return null;
            
        } catch (Exception $e) {
            error_log("Error getting lich chieu by slug: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Tạo slug từ thông tin lịch chiếu
     */
    public function createLichChieuSlug($lichChieu) 
    {
        // ✅ Lấy tên phim và làm sạch
        $phimName = $lichChieu['p_tenphim'] ?? 'phim';
        $phimSlug = $this->removeVietnameseAccents($phimName);
        $phimSlug = strtolower(preg_replace('/[^a-z0-9]+/', '-', $phimSlug));
        $phimSlug = trim($phimSlug, '-');
        
        // ✅ Tạo date-time slug
        $ngayChieu = date('Y-m-d', strtotime($lichChieu['lc_ngaychieu']));
        
        // Handle both timestamp and time-only formats
        if (strpos($lichChieu['lc_giobatdau'], ' ') !== false) {
            // Full timestamp: "2025-07-19 10:45:00"
            $gioChieu = date('H-i', strtotime($lichChieu['lc_giobatdau']));
        } else {
            // Time only: "10:45:00"
            $gioChieu = date('H-i', strtotime($lichChieu['lc_giobatdau']));
        }
        
        // ✅ Tạo slug: phim-name-YYYY-MM-DD-HH-mm
        $finalSlug = $phimSlug . '-' . $ngayChieu . '-' . $gioChieu;
        
        error_log("Generated slug: '{$finalSlug}' from movie: '{$phimName}', date: '{$ngayChieu}', time: '{$gioChieu}'");
        
        return $finalSlug;
    }
}