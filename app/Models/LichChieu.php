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
     * Lấy lịch chiếu theo phim
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
                    lc.p_maphim,
                    pc.pc_tenphong,
                    p.p_tenphim,
                    p.p_poster,
                    p.p_theloai,
                    p.p_thoiluong,
                    p.p_daodien,
                    p.p_dienvien,
                    p.p_mota
                FROM lich_chieu lc  
                LEFT JOIN phong_chieu pc ON lc.pc_maphongchieu = pc.pc_maphongchieu
                INNER JOIN phim p ON lc.p_maphim = p.p_maphim 
                WHERE lc.p_maphim = :phim_id 
                    AND lc.lc_ngaychieu >= CURRENT_DATE
                    AND lc.lc_trangthai IN ('Đang chiếu', 'Sắp chiếu')
                ORDER BY lc.lc_ngaychieu ASC, lc.lc_giobatdau ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['phim_id' => $phimId]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        error_log("getLichChieuByPhim result: " . count($result) . " rows for phim " . $phimId);
        if (!empty($result)) {
            error_log("Sample result: " . json_encode($result[0]));
        }
        
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
                    TO_CHAR(lc.lc_giobatdau, 'HH24:MI') as gio_chieu,
                    lc.lc_trangthai,
                    p.p_tenphim,
                    p.p_thoiluong,
                    COALESCE(pc.pc_tenphong, 'Phòng ' || lc.pc_maphongchieu) as pc_tenphong,
                    COALESCE(pc.pc_loaiphong, 'Không xác định') as pc_loaiphong
                FROM {$this->table} lc
                LEFT JOIN phim p ON lc.p_maphim = p.p_maphim  
                LEFT JOIN phong_chieu pc ON lc.pc_maphongchieu = pc.pc_maphongchieu
                WHERE lc.lc_ngaychieu >= CURRENT_DATE
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

    /**
     * Tạo slug từ thông tin lịch chiếu
     */
    public function createLichChieuSlug($lichChieu) 
    {
        error_log("=== CREATE SLUG DEBUG ===");
        error_log("Input lichChieu: " . json_encode($lichChieu));
        
        //  Lấy tên phim từ data
        $phimName = $lichChieu['p_tenphim'] ?? 'phim';
        error_log("Original phim name: " . $phimName);
        
        // Loại bỏ dấu và ký tự đặc biệt
        $phimSlug = $this->removeVietnameseAccents($phimName);
        error_log("After remove accents: " . $phimSlug);
        
        $phimSlug = strtolower($phimSlug);
        error_log("After lowercase: " . $phimSlug);
        
        $phimSlug = preg_replace('/[^a-z0-9\s]+/', '', $phimSlug); 
        error_log("After remove special chars: " . $phimSlug);
        
        $phimSlug = preg_replace('/\s+/', '-', trim($phimSlug));
        error_log("After convert spaces: " . $phimSlug);
        
        $phimSlug = trim($phimSlug, '-');
        error_log("Final phim slug: " . $phimSlug);
        
        // Tạo date-time slug
        $ngayChieu = date('Y-m-d', strtotime($lichChieu['lc_ngaychieu']));
        
        // Handle different time formats properly
        $gioChieu = '';
        if (isset($lichChieu['lc_giobatdau'])) {
            if (strpos($lichChieu['lc_giobatdau'], ' ') !== false) {
                // Full datetime: "2025-07-19 10:45:00" -> extract time part
                $timePart = explode(' ', $lichChieu['lc_giobatdau'])[1];
                $gioChieu = date('H-i', strtotime($timePart));
            } else {
                // Time only: "10:45:00"
                $gioChieu = date('H-i', strtotime($lichChieu['lc_giobatdau']));
            }
        }
        
        error_log("Date: " . $ngayChieu);
        error_log("Time: " . $gioChieu);
        
        // Tạo slug cuối cùng
        $finalSlug = $phimSlug . '-' . $ngayChieu . '-' . $gioChieu;
        error_log("Final slug: " . $finalSlug);
        error_log("=== END CREATE SLUG DEBUG ===");
        
        return $finalSlug;
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
                WHERE lc.lc_trangthai IN ('Sắp chiếu', 'Đang chiếu')
                ORDER BY lc.lc_ngaychieu ASC, lc.lc_giobatdau ASC";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $allLichChieu = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            foreach ($allLichChieu as $lichChieu) {
                $lichChieuSlug = $this->createLichChieuSlug($lichChieu);
                error_log("Comparing: '$lichChieuSlug' with '$slug' for showtime: " . $lichChieu['lc_malichchieu']);
                
                if ($lichChieuSlug === $slug) {
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
     * Loại bỏ dấu tiếng Việt
     */
    private function removeVietnameseAccents($str) 
    {
        // Thêm uppercase variants
        $accents = [
            // Lowercase
            'à', 'á', 'ạ', 'ả', 'ã', 'â', 'ầ', 'ấ', 'ậ', 'ẩ', 'ẫ', 'ă', 'ằ', 'ắ', 'ặ', 'ẳ', 'ẵ',
            'è', 'é', 'ẹ', 'ẻ', 'ẽ', 'ê', 'ề', 'ế', 'ệ', 'ể', 'ễ',
            'ì', 'í', 'ị', 'ỉ', 'ĩ',
            'ò', 'ó', 'ọ', 'ỏ', 'õ', 'ô', 'ồ', 'ố', 'ộ', 'ổ', 'ỗ', 'ơ', 'ờ', 'ớ', 'ợ', 'ở', 'ỡ',
            'ù', 'ú', 'ụ', 'ủ', 'ũ', 'ư', 'ừ', 'ứ', 'ự', 'ử', 'ữ',
            'ỳ', 'ý', 'ỵ', 'ỷ', 'ỹ',
            'đ',
            // Uppercase  
            'À', 'Á', 'Ạ', 'Ả', 'Ã', 'Â', 'Ầ', 'Ấ', 'Ậ', 'Ẩ', 'Ẫ', 'Ă', 'Ằ', 'Ắ', 'Ặ', 'Ẳ', 'Ẵ',
            'È', 'É', 'Ẹ', 'Ẻ', 'Ẽ', 'Ê', 'Ề', 'Ế', 'Ệ', 'Ể', 'Ễ',
            'Ì', 'Í', 'Ị', 'Ỉ', 'Ĩ',
            'Ò', 'Ó', 'Ọ', 'Ỏ', 'Õ', 'Ô', 'Ồ', 'Ố', 'Ộ', 'Ổ', 'Ỗ', 'Ơ', 'Ờ', 'Ớ', 'Ợ', 'Ở', 'Ỡ',
            'Ù', 'Ú', 'Ụ', 'Ủ', 'Ũ', 'Ư', 'Ừ', 'Ứ', 'Ự', 'Ử', 'Ữ',
            'Ỳ', 'Ý', 'Ỵ', 'Ỷ', 'Ỹ',
            'Đ'
        ];
        
        $noAccents = [
            // Lowercase replacements
            'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a',
            'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e',
            'i', 'i', 'i', 'i', 'i',
            'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o',
            'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u',
            'y', 'y', 'y', 'y', 'y',
            'd',
            // Uppercase replacements
            'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A',
            'E', 'E', 'E', 'E', 'E', 'E', 'E', 'E', 'E', 'E', 'E',
            'I', 'I', 'I', 'I', 'I',
            'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O',
            'U', 'U', 'U', 'U', 'U', 'U', 'U', 'U', 'U', 'U', 'U',
            'Y', 'Y', 'Y', 'Y', 'Y',
            'D'
        ];
        
        return str_replace($accents, $noAccents, $str);
    }

    /**
     * Lấy lịch chiếu chỉ trong ngày hôm nay
     */
    public function getLichChieuToday()
    {
        try {
            $sql = "SELECT 
                    lc.lc_malichchieu,
                    lc.p_maphim,
                    lc.pc_maphongchieu,
                    lc.lc_ngaychieu,
                    lc.lc_giobatdau,
                    TO_CHAR(lc.lc_giobatdau, 'HH24:MI') as gio_chieu,
                    lc.lc_trangthai,
                    p.p_tenphim,
                    p.p_thoiluong,
                    COALESCE(pc.pc_tenphong, CONCAT('Phòng ', lc.pc_maphongchieu)) as pc_tenphong,
                    COALESCE(pc.pc_loaiphong, 'Không xác định') as pc_loaiphong
                FROM {$this->table} lc
                LEFT JOIN phim p ON lc.p_maphim = p.p_maphim  
                LEFT JOIN phong_chieu pc ON lc.pc_maphongchieu = pc.pc_maphongchieu
                WHERE lc.lc_ngaychieu = CURRENT_DATE
                AND lc.lc_trangthai IN ('Sắp chiếu', 'Đang chiếu')
                ORDER BY lc.lc_giobatdau ASC";

            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            
            // error_log("Found " . count($results) . " showtimes for today");
            
            return $results;
            
        } catch (Exception $e) {
            error_log(" Error in getLichChieuToday: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Lấy lịch chiếu theo phim chỉ trong ngày hôm nay - SỬA CHO POSTGRESQL  
     */
    public function getLichChieuByPhimToday($phimId)
    {
        try {
            $sql = "SELECT 
                    lc.lc_malichchieu,
                    lc.lc_ngaychieu,
                    lc.lc_giobatdau,
                    TO_CHAR(lc.lc_giobatdau, 'HH24:MI') as gio_chieu,
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
                    COALESCE(pc.pc_tenphong, CONCAT('Phòng ', lc.pc_maphongchieu)) as pc_tenphong,
                    COALESCE(pc.pc_loaiphong, 'Không xác định') as pc_loaiphong
                FROM lich_chieu lc  
                LEFT JOIN phong_chieu pc ON lc.pc_maphongchieu = pc.pc_maphongchieu
                INNER JOIN phim p ON lc.p_maphim = p.p_maphim 
                WHERE lc.p_maphim = :phim_id 
                    AND lc.lc_ngaychieu = CURRENT_DATE
                    AND lc.lc_trangthai IN ('Đang chiếu', 'Sắp chiếu')
                ORDER BY lc.lc_giobatdau ASC";

            $stmt = $this->db->prepare($sql);
            $stmt->execute(['phim_id' => $phimId]);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // error_log("Found " . count($result) . " showtimes today for movie " . $phimId);
            
            return $result;
            
        } catch (Exception $e) {
            error_log("Error in getLichChieuByPhimToday: " . $e->getMessage());
            return [];
        }
    }
}