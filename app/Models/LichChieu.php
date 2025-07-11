<?php

namespace App\Models;

use App\Core\BaseModel;
use Exception;
use PDO;

class LichChieu extends BaseModel
{
    protected $table = 'lich_chieu';
    protected $primaryKey = 'lc_malichchieu';

    /**
     * Lấy tất cả lịch chiếu với thông tin phim
     */
    public function getAllLichChieu()
    {
        try {
            $sql = "SELECT lc.*, p.p_tenphim, p.p_theloai, p.p_thoiluong
                    FROM {$this->table} lc
                    LEFT JOIN phim p ON lc.p_maphim = p.p_maphim
                    ORDER BY lc.lc_ngaychieu DESC, lc.lc_giobatdau DESC";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error getting all lich chieu: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Lấy lịch chiếu theo ID
     */
    public function getLichChieuById($id)
    {
        try {
            $sql = "SELECT lc.*, p.p_tenphim, p.p_theloai, p.p_thoiluong
                    FROM {$this->table} lc
                    LEFT JOIN phim p ON lc.p_maphim = p.p_maphim
                    WHERE lc.{$this->primaryKey} = ?";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error getting lich chieu by ID: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Tạo lịch chiếu mới
     */
    public function createLichChieu($data)
    {
        try {
            $maLichChieu = $this->generateMaLichChieu();
            
            $sql = "INSERT INTO {$this->table} (
                        lc_malichchieu, lc_ngaychieu, lc_giobatdau, 
                        lc_trangthai, p_maphim
                    ) VALUES (?, ?, ?, ?, ?)";
            
            $stmt = $this->db->prepare($sql);
            $result = $stmt->execute([
                $maLichChieu,
                $data['lc_ngaychieu'],
                $data['lc_giobatdau'],
                $data['lc_trangthai'],
                $data['p_maphim']
            ]);

            return $result ? $maLichChieu : false;
        } catch (Exception $e) {
            error_log("Error creating lich chieu: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Kiểm tra duplicate bằng composite key
     */
    public function checkDuplicateByComposite($compositeKey)
    {
        try {
            error_log("Checking duplicate for composite key: " . $compositeKey);
            
            $sql = "SELECT COUNT(*) as count FROM {$this->table} 
                    WHERE lc_malichchieu = ?";
        
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$compositeKey]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
            $exists = $result['count'] > 0;
            error_log("Duplicate check result: " . ($exists ? 'EXISTS' : 'NOT_EXISTS'));
        
            return $exists;
        } catch (Exception $e) {
            error_log("Error checking duplicate composite: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Tạo lịch chiếu với composite key từ frontend
     */
    public function createLichChieuWithComposite($data)
    {
        try {
            error_log("Creating lich chieu with data: " . print_r($data, true));
    
            $sql = "INSERT INTO {$this->table} (
                        lc_malichchieu, lc_ngaychieu, lc_giobatdau, 
                        lc_trangthai, p_maphim, pc_maphongchieu
                    ) VALUES (?, ?, ?, ?, ?, ?)";
    
            $stmt = $this->db->prepare($sql);
            $result = $stmt->execute([
                $data['lc_malichchieu'],
                $data['lc_ngaychieu'],
                $data['lc_giobatdau'],
                $data['lc_trangthai'],
                $data['p_maphim'],
                $data['pc_maphongchieu'] // Thêm phòng chiếu
            ]);

            error_log("Insert result: " . ($result ? 'SUCCESS' : 'FAILED'));
    
            if (!$result) {
                error_log("SQL Error Info: " . print_r($stmt->errorInfo(), true));
            }

            return $result;
        } catch (Exception $e) {
            error_log("Error creating lich chieu with composite: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Cập nhật lịch chiếu
     */
    public function updateLichChieu($id, $data)
    {
        try {
            $sql = "UPDATE {$this->table} SET 
                    lc_ngaychieu = ?, 
                    lc_giobatdau = ?, 
                    lc_trangthai = ?, 
                    p_maphim = ?,
                    pc_maphongchieu = ?
                    WHERE {$this->primaryKey} = ?";
            
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                $data['lc_ngaychieu'],
                $data['lc_giobatdau'],
                $data['lc_trangthai'],
                $data['p_maphim'],
                $data['pc_maphongchieu'], // Thêm phòng chiếu
                $id
            ]);
        } catch (Exception $e) {
            error_log("Error updating lich chieu: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Xóa lịch chiếu
     */
    public function deleteLichChieu($id)
    {
        try {
            $sql = "DELETE FROM {$this->table} WHERE {$this->primaryKey} = ?";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$id]);
        } catch (Exception $e) {
            error_log("Error deleting lich chieu: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Lấy danh sách phim để chọn
     */
    public function getAllPhim()
    {
        try {
            // Kiểm tra bảng phim có tồn tại
            $checkTable = "SELECT to_regclass('public.phim')";
            $stmt = $this->db->prepare($checkTable);
            $stmt->execute();
            $tableExists = $stmt->fetchColumn();
            
            if (!$tableExists) {
                error_log("Table 'phim' does not exist");
                return [];
            }

            $sql = "SELECT p_maphim, p_tenphim, p_trangthai, p_thoiluong
                    FROM phim 
                    WHERE p_trangthai IN ('active', 'coming_soon')
                    ORDER BY p_tenphim";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            error_log("getAllPhim result count: " . count($result));
            return $result;
            
        } catch (Exception $e) {
            error_log("Error getting all phim: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Tìm kiếm lịch chiếu
     */
    public function searchLichChieu($filters = [])
    {
        try {
            $sql = "SELECT lc.*, p.p_tenphim, p.p_theloai, p.p_thoiluong
                    FROM {$this->table} lc
                    LEFT JOIN phim p ON lc.p_maphim = p.p_maphim
                    WHERE 1=1";
            $params = [];

            if (!empty($filters['ngay_chieu'])) {
                $sql .= " AND lc.lc_ngaychieu = ?";
                $params[] = $filters['ngay_chieu'];
            }

            if (!empty($filters['trang_thai'])) {
                $sql .= " AND lc.lc_trangthai = ?";
                $params[] = $filters['trang_thai'];
            }

            if (!empty($filters['ma_phim'])) {
                $sql .= " AND lc.p_maphim = ?";
                $params[] = $filters['ma_phim'];
            }

            $sql .= " ORDER BY lc.lc_ngaychieu DESC, lc.lc_giobatdau DESC";

            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error searching lich chieu: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Kiểm tra trùng lịch chiếu
     */
    public function checkConflict($ngayChieu, $gioBatDau, $maPhim, $excludeId = null)
    {
        try {
            $sql = "SELECT COUNT(*) as count 
                    FROM {$this->table} lc
                    JOIN phim p ON lc.p_maphim = p.p_maphim
                    WHERE lc.lc_ngaychieu = ? 
                    AND ABS(EXTRACT(EPOCH FROM (lc.lc_giobatdau::time - ?::time))) < (p.p_thoiluong * 60 + 30 * 60)";
            $params = [$ngayChieu, $gioBatDau];
            
            if ($excludeId) {
                $sql .= " AND lc.{$this->primaryKey} != ?";
                $params[] = $excludeId;
            }
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return $result['count'] > 0;
        } catch (Exception $e) {
            error_log("Error checking conflict: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Kiểm tra trùng lịch chiếu theo phòng
     */
    public function checkConflictByRoom($phongChieuId, $ngayChieu, $gioBatDau, $excludeId = null)
    {
        try {
            error_log("=== CHECKING ROOM CONFLICT ===");
            error_log("Room: $phongChieuId");
            error_log("Date: $ngayChieu"); 
            error_log("Time: $gioBatDau");
            error_log("Exclude ID: " . ($excludeId ?? 'NONE'));
            
            // Lấy danh sách tất cả lịch chiếu trong phòng cùng ngày
            $sql = "SELECT lc.lc_malichchieu, lc.lc_giobatdau, p.p_tenphim, p.p_thoiluong,
                       EXTRACT(EPOCH FROM (lc.lc_giobatdau::time - ?::time)) / 60 as time_diff_minutes
                FROM {$this->table} lc
                JOIN phim p ON lc.p_maphim = p.p_maphim
                WHERE lc.pc_maphongchieu = ? 
                AND lc.lc_ngaychieu = ?
                AND lc.lc_trangthai NOT IN ('Hủy', 'Đã chiếu')";
        
            $params = [$gioBatDau, $phongChieuId, $ngayChieu];
        
            // Loại trừ bản ghi hiện tại khi update
            if ($excludeId) {
                $sql .= " AND lc.{$this->primaryKey} != ?";
                $params[] = $excludeId;
            }
            
            $sql .= " ORDER BY lc.lc_giobatdau";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            $existingSchedules = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            error_log("Found " . count($existingSchedules) . " existing schedules in room:");
            
            foreach ($existingSchedules as $schedule) {
                error_log("- Schedule: {$schedule['lc_malichchieu']} at " . date('H:i', strtotime($schedule['lc_giobatdau'])) . " ({$schedule['p_tenphim']})");
                
                $timeDiffMinutes = abs($schedule['time_diff_minutes']);
                $movieDuration = (int)$schedule['p_thoiluong'];
                $bufferTime = 30; // 30 phút buffer
                $requiredMinutes = $movieDuration + $bufferTime;
                
                error_log("  Time diff: {$timeDiffMinutes} minutes");
                error_log("  Movie duration: {$movieDuration} minutes");
                error_log("  Required gap: {$requiredMinutes} minutes");
                
                if ($timeDiffMinutes < $requiredMinutes) {
                    error_log("  *** CONFLICT DETECTED ***");
                    return true;
                } else {
                    error_log("  OK - No conflict");
                }
            }
            
            error_log("=== NO CONFLICTS FOUND ===");
            return false;
            
        } catch (Exception $e) {
            error_log("Error checking room conflict: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Cập nhật trạng thái lịch chiếu theo thời gian
     */
    public function updateStatusByTime()
    {
        try {
            // comment để tránh lỗi SQL
            error_log("updateStatusByTime method called - temporarily disabled");
            return true;
            
            // TODO: Fix PostgreSQL INTERVAL syntax later
            /*
            $sql = "UPDATE {$this->table} 
                    SET lc_trangthai = CASE 
                        WHEN lc_giobatdau <= NOW() 
                        THEN 'Đang chiếu'
                        WHEN lc_giobatdau + MAKE_INTERVAL(mins => p.p_thoiluong) <= NOW() 
                        THEN 'Đã chiếu'
                        ELSE lc_trangthai
                    END
                    FROM phim p 
                    WHERE {$this->table}.p_maphim = p.p_maphim
                    AND {$this->table}.lc_trangthai IN ('Sắp chiếu', 'Đang chiếu')";
            
            $stmt = $this->db->prepare($sql);
            $result = $stmt->execute();
            
            error_log("Status updated successfully");
            return $result;
            */
            
        } catch (Exception $e) {
            error_log("Error updating status by time: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Generate unique schedule ID
     */
    private function generateMaLichChieu()
    {
        try {
            // Get the latest schedule ID
            $sql = "SELECT lc_malichchieu FROM {$this->table} 
                    WHERE lc_malichchieu LIKE 'LC%' 
                    ORDER BY lc_malichchieu DESC 
                    LIMIT 1";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($result) {
                // Extract number from existing ID (e.g., LC001 -> 001)
                $lastNumber = (int)substr($result['lc_malichchieu'], 2);
                $newNumber = $lastNumber + 1;
            } else {
                // First schedule ID
                $newNumber = 1;
            }
            
            // Format with leading zeros (LC001, LC002, etc.)
            return 'LC' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
            
        } catch (Exception $e) {
            error_log("Error generating schedule ID: " . $e->getMessage());
            // Fallback to timestamp-based ID
            return 'LC' . date('ymd') . rand(100, 999);
        }
    }

    /**
     * Lấy lịch chiếu theo mã phim
     */
    public function getLichChieuByPhimId($phimId)
    {
        try {
            $sql = "SELECT lc.*, p.p_tenphim, p.p_thoiluong
                    FROM {$this->table} lc
                    LEFT JOIN phim p ON lc.p_maphim = p.p_maphim
                    WHERE lc.p_maphim = ? 
                    AND lc.lc_trangthai IN ('Sắp chiếu', 'Đang chiếu')
                    AND lc.lc_ngaychieu >= CURRENT_DATE
                    ORDER BY lc.lc_ngaychieu ASC, lc.lc_giobatdau ASC";
        
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$phimId]);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
            error_log("Found " . count($result) . " lich chieu for phim " . $phimId);
            return $result;
        
        } catch (Exception $e) {
            error_log("Error getting lich chieu by phim ID: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Lấy lịch chiếu theo phim (từ hôm nay trở đi)
     */
    public function getLichChieuByPhim($phimId)
    {
        try {
            
            $sql = "SELECT 
                    lc.lc_malichchieu,
                    lc.lc_ngaychieu,
                    lc.lc_giobatdau,
                    lc.lc_trangthai,
                    lc.pc_maphongchieu,
                    pc.pc_tenphong
                FROM lich_chieu lc  
                LEFT JOIN phong_chieu pc ON lc.pc_maphongchieu = pc.pc_maphongchieu  -- ✅ Kiểm tra tên table phong_chieu
                WHERE lc.p_maphim = :phim_id 
                    AND lc.lc_ngaychieu >= CURRENT_DATE
                    AND lc.lc_trangthai IN ('Đang chiếu', 'Sắp chiếu')
                ORDER BY lc.lc_ngaychieu ASC, lc.lc_giobatdau ASC";
    
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['phim_id' => $phimId]);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            error_log("getLichChieuByPhim result: " . count($result) . " rows for phim " . $phimId);
            
            return $result;
            
        } catch (Exception $e) {
            error_log("Error in getLichChieuByPhim: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Lấy lịch chiếu với thông tin phòng chiếu (cho chọn ghế)  
     */
    public function getLichChieuWithPhongChieu($lichChieuId)
    {
        try {
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
                WHERE lc.lc_malichchieu = ?";
        
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$lichChieuId]);
        
            return $stmt->fetch(PDO::FETCH_ASSOC);
        
        } catch (Exception $e) {
            error_log("Error in getLichChieuWithPhongChieu: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Debug method - Lấy thông tin chi tiết về conflicts
     */
    public function getConflictDetails($phongChieuId, $ngayChieu, $gioBatDau, $excludeId = null)
    {
        try {
            $sql = "SELECT lc.lc_malichchieu, lc.lc_giobatdau, p.p_tenphim, p.p_thoiluong,
                       lc.lc_trangthai,
                       EXTRACT(EPOCH FROM (lc.lc_giobatdau::time - ?::time)) / 60 as time_diff_minutes
                FROM {$this->table} lc
                JOIN phim p ON lc.p_maphim = p.p_maphim
                WHERE lc.pc_maphongchieu = ? 
                AND lc.lc_ngaychieu = ?
                AND lc.lc_trangthai NOT IN ('Hủy', 'Đã chiếu')";
        
            $params = [$gioBatDau, $phongChieuId, $ngayChieu];
        
            if ($excludeId) {
                $sql .= " AND lc.{$this->primaryKey} != ?";
                $params[] = $excludeId;
            }
            
            $sql .= " ORDER BY lc.lc_giobatdau";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (Exception $e) {
            error_log("Error getting conflict details: " . $e->getMessage());
            return [];
        }
    }

    // Lấy danh sách lịch chiếu có phân trang và filter
    public function getAllLichChieuFiltered($filters = [], $limit = 10, $offset = 0)
    {
        $sql = "SELECT lc.*, p.p_tenphim, pc.pc_tenphong
                FROM lich_chieu lc
                LEFT JOIN phim p ON lc.p_maphim = p.p_maphim
                LEFT JOIN phong_chieu pc ON lc.pc_maphongchieu = pc.pc_maphongchieu
                WHERE 1=1";
        $params = [];

        if (!empty($filters['ngay_chieu'])) {
            $sql .= " AND lc.lc_ngaychieu = ?";
            $params[] = $filters['ngay_chieu'];
        }
        if (!empty($filters['trang_thai'])) {
            $sql .= " AND lc.lc_trangthai = ?";
            $params[] = $filters['trang_thai'];
        }
        if (!empty($filters['ma_phim'])) {
            $sql .= " AND lc.p_maphim = ?";
            $params[] = $filters['ma_phim'];
        }

        $sql .= " ORDER BY lc.lc_ngaychieu DESC, lc.lc_giobatdau DESC LIMIT ? OFFSET ?";
        $params[] = (int)$limit;
        $params[] = (int)$offset;

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    // Đếm tổng số lịch chiếu (có filter)
    public function countLichChieu($filters = [])
    {
        $sql = "SELECT COUNT(*) FROM lich_chieu lc WHERE 1=1";
        $params = [];

        if (!empty($filters['ngay_chieu'])) {
            $sql .= " AND lc.lc_ngaychieu = ?";
            $params[] = $filters['ngay_chieu'];
        }
        if (!empty($filters['trang_thai'])) {
            $sql .= " AND lc.lc_trangthai = ?";
            $params[] = $filters['trang_thai'];
        }
        if (!empty($filters['ma_phim'])) {
            $sql .= " AND lc.p_maphim = ?";
            $params[] = $filters['ma_phim'];
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn();
    }

    /**
     * Lấy lịch chiếu với thông tin đầy đủ (JOIN)
     */
    public function getLichChieuWithDetails()
    {
        try {
            $sql = "SELECT 
                    lc.*,
                    p.p_tenphim,
                    p.p_thoiluong,
                    pc.pc_tenphong,
                    pc.pc_soluongghe
                FROM {$this->table} lc
                INNER JOIN phim p ON lc.p_maphim = p.p_maphim  
                INNER JOIN phong_chieu pc ON lc.pc_maphongchieu = pc.pc_maphongchieu
                WHERE lc.lc_ngaychieu >= CURDATE()
                ORDER BY lc.lc_ngaychieu ASC, lc.lc_giobatdau ASC";
    
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            
            error_log("🎬 Loaded " . count($results) . " showtimes with details");
            if (!empty($results)) {
                error_log("🔍 Sample data: " . json_encode($results[0]));
            }
            
            return $results;
            
        } catch (Exception $e) {
            error_log("💥 Error in getLichChieuWithDetails: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Lấy lịch chiếu với thông tin đầy đủ - XỬ LÝ TIMESTAMP
     */
    public function getLichChieuWithFullDetails()
    {
        try {
            // ✅ SỬA TÊN BẢNG: phongchieu → phong_chieu
            $sql = "SELECT 
                    lc.lc_malichchieu,
                    lc.p_maphim,
                    lc.pc_maphongchieu,
                    lc.lc_ngaychieu,
                    lc.lc_giobatdau,
                    TIME_FORMAT(lc.lc_giobatdau, '%H:%i') as gio_chieu,
                    lc.lc_trangthai,
                    p.p_tenphim,
                    p.p_thoiluong,
                    COALESCE(pc.pc_tenphong, CONCAT('Phòng ', lc.pc_maphongchieu)) as pc_tenphong,
                    COALESCE(pc.pc_loaiphong, 'Không xác định') as pc_loaiphong
                FROM {$this->table} lc
                LEFT JOIN phim p ON lc.p_maphim = p.p_maphim  
                LEFT JOIN phong_chieu pc ON lc.pc_maphongchieu = pc.pc_maphongchieu
                WHERE lc.lc_ngaychieu >= CURDATE()
                ORDER BY lc.lc_ngaychieu ASC, lc.lc_giobatdau ASC";
    
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            
            error_log("🎬 Query result count: " . count($results));
            
            // ✅ DEBUG TỪNG RECORD
            foreach ($results as $index => $result) {
                error_log("=== RECORD {$index} ===");
                error_log("ID: " . ($result['lc_malichchieu'] ?? 'NULL'));
                error_log("Movie ID: " . ($result['p_maphim'] ?? 'NULL'));
                error_log("Room ID: " . ($result['pc_maphongchieu'] ?? 'NULL'));
                error_log("Room Name: " . ($result['pc_tenphong'] ?? 'NULL'));
                error_log("Date: " . ($result['lc_ngaychieu'] ?? 'NULL'));
                error_log("Time: " . ($result['gio_chieu'] ?? 'NULL'));
                error_log("Raw timestamp: " . ($result['lc_giobatdau'] ?? 'NULL'));
                error_log("Movie name: " . ($result['p_tenphim'] ?? 'NULL'));
            }
            
            return $results;
            
        } catch (Exception $e) {
            error_log("💥 SQL Error: " . $e->getMessage());
            return [];
        }
    }
}