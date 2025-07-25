<?php

namespace App\Models;

use App\Core\BaseModel;
use Exception;

class Ghe extends BaseModel
{ 
    protected $table = 'ghe';
    protected $primaryKey = 'g_maghe';
    
    // lấy tất cả chỗ ngồi với thông tin phòng
    public function getAllGhe()
    {
        try {
            $sql = "SELECT g.*, pc.pc_tenphong 
                    FROM {$this->table} g
                    LEFT JOIN phong_chieu pc ON g.pc_maphongchieu = pc.pc_maphongchieu
                    ORDER BY g.pc_maphongchieu ASC, g.g_maghe ASC";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $results = $stmt->fetchAll();
            
            error_log("getAllGhe tìm được " . count($results) . " seats");
            
            return $results;
        } catch (Exception $e) {
            error_log("lỗi xảy ra khi lấy tất cả ghế: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * tạo nhiều ghế cho một phòng
     * @param string $maPhong 
     * @param array $seatData - mảng dữ liệu ghế
     * @return bool
     */
    public function createMultipleSeats($maPhong, $seatData)
    {
        try {
            $this->db->beginTransaction();
            
            // Xóa ghế cũ
            $this->deleteSeatsbyRoom($maPhong);
            
            // ✅ KIỂM TRA TRÙNG LẶP:
            $uniqueSeats = [];
            
            foreach ($seatData as $seat) {
                $seatCode = $seat['code']; // Chỉ lấy code, không tự tạo
                
                // ✅ Chỉ thêm nếu chưa tồn tại:
                if (!isset($uniqueSeats[$seatCode])) {
                    $uniqueSeats[$seatCode] = [
                        'code' => $seatCode,
                        'type' => $seat['type'],
                        'display' => $seat['display']
                    ];
                }
            }
            
            $sql = "INSERT INTO {$this->table} (g_maghe, g_loaighe, g_trangthai, pc_maphongchieu) 
                    VALUES (:ma_ghe, :loai_ghe, :trang_thai, :ma_phong)";
            
            $stmt = $this->db->prepare($sql);
            
            foreach ($uniqueSeats as $seat) {
                $stmt->execute([
                    'ma_ghe' => $seat['code'],
                    'loai_ghe' => $seat['type'],
                    'trang_thai' => 'available',
                    'ma_phong' => $maPhong
                ]);
            }
            
            $this->db->commit();
            error_log("Tạo thành công " . count($uniqueSeats) . " ghế unique cho phòng: " . $maPhong);
            return true;
            
        } catch (Exception $e) {
            $this->db->rollback();
            error_log("Lỗi tạo ghế: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * xóa tất cả ghế cho phòng chỉ định
     */
    public function deleteSeatsbyRoom($maPhong)
    {
        try {
            $sql = "DELETE FROM {$this->table} WHERE pc_maphongchieu = :ma_phong";
            $stmt = $this->db->prepare($sql);
            $result = $stmt->execute(['ma_phong' => $maPhong]);
            
            error_log("xóa ghế cho phòng: {$maPhong}");
            return $result;
        } catch (Exception $e) {
            error_log("xảy ra lỗi khi xóa ghế: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * lấy ghế theo phòng
     */
    public function getByPhongChieu($maPhong)
    {
        try {
            $sql = "SELECT g_maghe, g_loaighe, g_trangthai, g_giaghe, pc_maphongchieu 
                    FROM {$this->table} 
                    WHERE pc_maphongchieu = ? 
                    ORDER BY g_maghe";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$maPhong]);
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("xảy ra lỗi khi lấy ghế theo phòng: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * cập nhật trạng thái ghế
     */
    public function updateSeatStatus($maGhe, $trangThai)
    {
        try {
            $sql = "UPDATE {$this->table} SET g_trangthai = :trang_thai WHERE g_maghe = :ma_ghe";
            $stmt = $this->db->prepare($sql);
            
            return $stmt->execute([
                'ma_ghe' => $maGhe,
                'trang_thai' => $trangThai
            ]);
        } catch (Exception $e) {
            error_log("xảy ra lỗi khi cập nhật trạng thái ghế: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * cập nhật loại ghế
     */
    public function updateSeatType($maGhe, $loaiGhe)
    {
        try {
            $sql = "UPDATE {$this->table} SET g_loaighe = :loai_ghe WHERE g_maghe = :ma_ghe";
            $stmt = $this->db->prepare($sql);
            
            return $stmt->execute([
                'ma_ghe' => $maGhe,
                'loai_ghe' => $loaiGhe
            ]);
        } catch (Exception $e) {
            error_log("xảy ra lỗi khi cập nhật ghế: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * kiểm ghế đã tồn tại chưa
     */
    public function checkSeatExists($maGhe)
    {
        try {
            $sql = "SELECT COUNT(*) FROM {$this->table} WHERE g_maghe = :ma_ghe";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['ma_ghe' => $maGhe]);
            
            return $stmt->fetchColumn() > 0;
        } catch (Exception $e) {
            error_log("xảy ra lỗi khi kiểm tra ghế: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * lấy số liệu thống kê chỗ ngồi cho một phòng
     */
    public function getSeatStatistics($maPhong = null)
    {
        try {
            $whereClause = $maPhong ? "WHERE pc_maphongchieu = :ma_phong" : "";
            $sql = "SELECT 
                        g_loaighe,
                        g_trangthai,
                        COUNT(*) as count
                    FROM {$this->table} 
                    {$whereClause}
                    GROUP BY g_loaighe, g_trangthai";
            
            $stmt = $this->db->prepare($sql);
            if ($maPhong) {
                $stmt->execute(['ma_phong' => $maPhong]);
            } else {
                $stmt->execute();
            }
            
            return $stmt->fetchAll();
        } catch (Exception $e) {
            error_log("xảy ra lỗi khi lấy thống kê: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Cập nhật hàng loạt loại chỗ ngồi (để chỉnh sửa sơ đồ chỗ ngồi)
     */
    public function bulkUpdateSeatTypes($updates)
    {
        try {
            $this->db->beginTransaction();
            
            $sql = "UPDATE {$this->table} SET g_loaighe = :loai_ghe WHERE g_maghe = :ma_ghe";
            $stmt = $this->db->prepare($sql);
            
            $successCount = 0;
            foreach ($updates as $update) {
                $result = $stmt->execute([
                    'ma_ghe' => $update['ma_ghe'],
                    'loai_ghe' => $update['loai_ghe']
                ]);
                if ($result) $successCount++;
            }
            
            $this->db->commit();
            error_log("Cập nhật hàng loạt  {$successCount} loại chỗ ngồi");
            
            return $successCount > 0;
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log("Cập nhật hàng loạt loại chỗ ngồi: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Kiểm tra xem mã chỗ ngồi đã tồn tại trên toàn cầu chưa
     */
    public function checkSeatCodeExists($seatCode)
    {
        try {
            $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE g_maghe = :seat_code";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['seat_code' => $seatCode]);
            
            $result = $stmt->fetch();
            return $result['count'] > 0;
            
        } catch (Exception $e) {
            error_log(" xảy ra kiểm tra xem mã chỗ ngồi đã tồn tại trên toàn cầu chưa: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Lấy thông tin ghế theo mã ghế
     */
    public function getGheByMaGhe($maGhe)
    {
        try {
            $sql = "SELECT g_maghe, g_loaighe, g_trangthai, g_giaghe, pc_maphongchieu 
                    FROM {$this->table} 
                    WHERE g_maghe = ? 
                    LIMIT 1";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$maGhe]);
            return $stmt->fetch(\PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("xảy ra lỗi khi lấy ghế theo mã: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Cập nhật hàng loạt giá ghế theo loại - TOÀN HỆ THỐNG
     */
    public function bulkUpdateAllSeatPrices($loaiGhe, $giaGhe)
    {
        try {
            // ✅ CẬP NHẬT TẤT CẢ GHẾ CÙNG LOẠI TRONG TOÀN HỆ THỐNG
            $sql = "UPDATE {$this->table} SET g_giaghe = :gia_ghe WHERE g_loaighe = :loai_ghe";
            $params = [
                'loai_ghe' => $loaiGhe,
                'gia_ghe' => $giaGhe
            ];
            
            error_log("🔄 Executing SQL: " . $sql);
            error_log("🔄 With params: " . json_encode($params));
            
            $stmt = $this->db->prepare($sql);
            $result = $stmt->execute($params);
            
            if ($result) {
                $affectedRows = $stmt->rowCount();
                error_log("✅ Updated seat prices GLOBALLY - Type: {$loaiGhe}, Price: {$giaGhe}, Affected: {$affectedRows}");
                return $affectedRows;
            }
            
            error_log("❌ Update failed - no rows affected");
            return false;
            
        } catch (Exception $e) {
            error_log("💥 Error updating global seat prices: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Lấy thống kê giá ghế theo loại - TOÀN HỆ THỐNG (CẢI THIỆN)
     */
    public function getPriceStatsByType()
    {
        try {
            $sql = "SELECT 
                    g_loaighe,
                    COALESCE(MIN(g_giaghe), 0) as min_price,
                    COALESCE(MAX(g_giaghe), 0) as max_price,
                    COALESCE(AVG(g_giaghe), 0) as avg_price,
                    COUNT(*) as total_seats,
                    COUNT(CASE WHEN g_trangthai = 'available' THEN 1 END) as available_seats,
                    COUNT(CASE WHEN g_trangthai = 'booked' THEN 1 END) as booked_seats,
                    COUNT(CASE WHEN g_trangthai = 'locked' THEN 1 END) as locked_seats
                FROM {$this->table} 
                GROUP BY g_loaighe 
                ORDER BY 
                    CASE g_loaighe 
                        WHEN 'normal' THEN 1 
                        WHEN 'vip' THEN 2 
                        WHEN 'luxury' THEN 3 
                        ELSE 4
                    END";
        
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        
            error_log("📊 Price stats loaded: " . count($results) . " seat types");
        
            return $results;
        
        } catch (Exception $e) {
            error_log("💥 Error getting price stats: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Lấy tổng số ghế theo loại (để validation)
     */
    public function getTotalSeatsByType($loaiGhe)
    {
        try {
            $sql = "SELECT COUNT(*) as total FROM {$this->table} WHERE g_loaighe = :loai_ghe";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['loai_ghe' => $loaiGhe]);
        
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);
            return (int)($result['total'] ?? 0);
        
        } catch (Exception $e) {
            error_log("Error getting total seats by type: " . $e->getMessage());
            return 0;
        }
    }
}