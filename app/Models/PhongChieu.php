<?php

namespace App\Models;

use App\Core\BaseModel;
use Exception;
use PDO;

class PhongChieu extends BaseModel
{
    protected $table = 'phong_chieu';
    protected $primaryKey = 'pc_maphongchieu';
    
    /**
     * lấy tất cả phòng chiếu phim
     */
    public function getAllPhongChieu()
    {
        try {
            $sql = "SELECT * FROM {$this->table} ORDER BY pc_maphongchieu ASC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $results = $stmt->fetchAll();
            
            error_log("getAllPhongChieu tìm được " . count($results) . " rooms");
            
            return $results;
        } catch (Exception $e) {
            error_log("xảy ra lỗi lấy tất cả phòng chiếu phim: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * tạo phòng chiếu phim mới
     */
    public function createPhongChieu($data)
    {
        try {
            $sql = "INSERT INTO {$this->table} (pc_maphongchieu, pc_tenphong, pc_loaiphong) 
                    VALUES (:ma_phong, :ten_phong, :loai_phong)";
            
            $stmt = $this->db->prepare($sql);
            $result = $stmt->execute([
                'ma_phong' => $data['ma_phong'],
                'ten_phong' => $data['ten_phong'],
                'loai_phong' => $data['loai_phong']
            ]);
            
            if ($result) {
                error_log("Created room: " . $data['ma_phong']);
                return true;
            }
            return false;
        } catch (Exception $e) {
            error_log("Error creating room: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Check if room code exists
     */
    public function checkPhongExists($maPhong)
    {
        try {
            $sql = "SELECT COUNT(*) FROM {$this->table} WHERE pc_maphongchieu = :ma_phong";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['ma_phong' => $maPhong]);
            
            return $stmt->fetchColumn() > 0;
        } catch (Exception $e) {
            error_log("Error checking room exists: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get room by ID
     */
    public function getPhongById($maPhong)
    {
        try {
            $sql = "SELECT * FROM {$this->table} WHERE pc_maphongchieu = :ma_phong";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['ma_phong' => $maPhong]);
            
            return $stmt->fetch();
        } catch (Exception $e) {
            error_log("Error getting room by ID: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Update room information
     */
    public function updatePhongChieu($maPhong, $data)
    {
        try {
            $sql = "UPDATE {$this->table} 
                    SET pc_tenphong = :ten_phong, pc_loaiphong = :loai_phong 
                    WHERE pc_maphongchieu = :ma_phong";
            
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                'ma_phong' => $maPhong,
                'ten_phong' => $data['ten_phong'],
                'loai_phong' => $data['loai_phong']
            ]);
        } catch (Exception $e) {
            error_log("Error updating room: " . $e->getMessage());
            return false;
        }
    }

    public function getGheByPhongChieu($phongChieuId)
    {
        try {
            $sql = "SELECT g.*, 
                           CASE 
                               WHEN v.g_maghe IS NOT NULL THEN 'Đã đặt'
                               ELSE g.g_trangthai
                           END as trangthai_hienthi
                    FROM ghe g
                    LEFT JOIN ve v ON g.g_maghe = v.g_maghe
                    WHERE g.pc_maphongchieu = ?
                    ORDER BY g.g_maghe";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$phongChieuId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error getting ghe by phong chieu: " . $e->getMessage());
            return [];
        }
    }

    public function getGheDaDatByLichChieu($lichChieuId)
    {
        try {
            $sql = "SELECT DISTINCT v.g_maghe
                    FROM ve v
                    WHERE v.lc_malichchieu = ?";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$lichChieuId]);
            $result = $stmt->fetchAll(PDO::FETCH_COLUMN);
            
            return $result ?: [];
        } catch (Exception $e) {
            error_log("Error getting ghe da dat: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Lấy thông tin ghế theo mã ghế
     */
    public function getGheByMaGhe($maGhe)
    {
        try {
            error_log("PhongChieu::getGheByMaGhe called with: " . $maGhe);
            
            $sql = "SELECT * FROM ghe WHERE g_maghe = :ma_ghe";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['ma_ghe' => $maGhe]);
            
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            error_log("PhongChieu::getGheByMaGhe result: " . json_encode($result));
            
            return $result;
            
        } catch (Exception $e) {
            error_log("Error in getGheByMaGhe: " . $e->getMessage());
            return null;
        }
    }
}