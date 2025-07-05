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
            // bắt đầu transaction
            $this->db->beginTransaction();
            
            // xóa các ghế đang tồn tại
            $this->deleteSeatsbyRoom($maPhong);
            
            // chèn các ghế mới
            $sql = "INSERT INTO {$this->table} (g_maghe, g_loaighe, g_trangthai, pc_maphongchieu) 
                    VALUES (:ma_ghe, :loai_ghe, :trang_thai, :ma_phong)";
            
            $stmt = $this->db->prepare($sql);
            
            foreach ($seatData as $seat) {
                // đảm bảo mã số duy nhất với prefix phòng
                $seatCode = isset($seat['code']) ? $seat['code'] : $maPhong . '_' . $seat['display'];
                
                $stmt->execute([
                    'ma_ghe' => $seatCode,
                    'loai_ghe' => $seat['type'],
                    'trang_thai' => 'available',
                    'ma_phong' => $maPhong
                ]);
            }
            
            // Commit transaction
            $this->db->commit();
            error_log("tạo thành công " . count($seatData) . " ghế cho phòng: " . $maPhong);
            return true;
            
        } catch (Exception $e) {
            // Rollback transaction
            $this->db->rollback();
            error_log("xảy ra lỗi khi tạo ghế: " . $e->getMessage());
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
            $sql = "SELECT * FROM {$this->table} WHERE pc_maphongchieu = :ma_phong ORDER BY g_maghe ASC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['ma_phong' => $maPhong]);
            
            return $stmt->fetchAll();
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
}