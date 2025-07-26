<?php

namespace App\Models;

use App\Core\BaseModel;
use Exception;
use PDO;

class UuDai extends BaseModel
{
    protected $table = 'uu_dai';
    protected $primaryKey = 'ud_mauudai';

            /**
     * Lấy ưu đãi hiển thị trên trang chủ
     */
    public function getUuDaiTrangChu()
    {
        try {
            $sql = "SELECT * FROM {$this->table} ORDER BY udtc_mauudai ASC LIMIT 8";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            return $result;
        } catch (Exception $e) {
            error_log("Error in getUuDaiTrangChu: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Lấy tất cả ưu đãi
     */
    public function getAllUuDai()
    {
        try {
            $sql = "SELECT * FROM {$this->table} ORDER BY ud_thoigianbatdau DESC";
            
            // DEBUG: Log SQL
            // error_log("SQL Query: " . $sql);
            // error_log("Table name: " . $this->table);
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // DEBUG: Log kết quả
            // error_log("Query result count: " . count($result));
            // error_log("Query result: " . print_r($result, true));
            
            return $result;
        } catch (Exception $e) {
            error_log("Error getting all uu dai: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Lấy ưu đãi theo ID
     */
    public function getUuDaiById($id)
    {
        try {
            $sql = "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error getting uu dai by ID: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Thêm ưu đãi mới
     */
    public function createUuDai($data)
    {
        try {
            // Generate mã ưu đãi
            $maUuDai = $this->generateMaUuDai();
            
            $sql = "INSERT INTO {$this->table} (ud_mauudai, ud_tenuudai, ud_anhuudai, ud_loaiuudai, ud_noidung, ud_thoigianbatdau, ud_thoigianketthuc, ud_trangthai) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            
            $stmt = $this->db->prepare($sql);
            $result = $stmt->execute([
                $maUuDai,
                $data['ud_tenuudai'],
                $data['ud_anhuudai'],
                $data['ud_loaiuudai'],
                $data['ud_noidung'],
                $data['ud_thoigianbatdau'],
                $data['ud_thoigianketthuc'],
                $data['ud_trangthai']
            ]);

            return $result ? $maUuDai : false;
        } catch (Exception $e) {
            error_log("Error creating uu dai: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Cập nhật ưu đãi
     */
    public function updateUuDai($id, $data)
    {
        try {
            $sql = "UPDATE {$this->table} SET 
                    ud_tenuudai = ?, 
                    ud_anhuudai = ?, 
                    ud_loaiuudai = ?, 
                    ud_noidung = ?, 
                    ud_thoigianbatdau = ?, 
                    ud_thoigianketthuc = ?, 
                    ud_trangthai = ? 
                    WHERE {$this->primaryKey} = ?";
            
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                $data['ud_tenuudai'],
                $data['ud_anhuudai'],
                $data['ud_loaiuudai'],
                $data['ud_noidung'],
                $data['ud_thoigianbatdau'],
                $data['ud_thoigianketthuc'],
                $data['ud_trangthai'],
                $id
            ]);
        } catch (Exception $e) {
            error_log("Error updating uu dai: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Xóa ưu đãi
     */
    public function deleteUuDai($id)
    {
        try {
            $sql = "DELETE FROM {$this->table} WHERE {$this->primaryKey} = ?";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$id]);
        } catch (Exception $e) {
            error_log("Error deleting uu dai: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Tìm kiếm ưu đãi
     */
    public function searchUuDai($filters = [])
    {
        try {
            $sql = "SELECT * FROM {$this->table} WHERE 1=1";
            $params = [];

            if (!empty($filters['ten_uu_dai'])) {
                $sql .= " AND ud_tenuudai LIKE ?";
                $params[] = '%' . $filters['ten_uu_dai'] . '%';
            }

            if (!empty($filters['loai_uu_dai'])) {
                $sql .= " AND ud_loaiuudai = ?";
                $params[] = $filters['loai_uu_dai'];
            }

            if (!empty($filters['trang_thai'])) {
                $sql .= " AND ud_trangthai = ?";
                $params[] = $filters['trang_thai'];
            }

            $sql .= " ORDER BY ud_thoigianbatdau DESC";

            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error searching uu dai: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Generate mã ưu đãi tự động
     */
    private function generateMaUuDai()
    {
        try {
            $sql = "SELECT MAX(CAST(SUBSTRING({$this->primaryKey} FROM 3) AS INTEGER)) as max_id FROM {$this->table}";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            $maxId = $result['max_id'] ? $result['max_id'] + 1 : 1;
            return 'UD' . str_pad($maxId, 3, '0', STR_PAD_LEFT);
        } catch (Exception $e) {
            error_log("Error generating ma uu dai: " . $e->getMessage());
            return 'UD001';
        }
    }

    /**
     * Lấy ưu đãi theo trạng thái
     */
    public function getUuDaiByStatus($status)
    {
        try {
            $sql = "SELECT * FROM {$this->table} WHERE ud_trangthai = ? ORDER BY ud_thoigianbatdau DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$status]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error getting uu dai by status: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Cập nhật trạng thái ưu đãi theo thời gian
     */
    public function updateStatusByDate()
    {
        try {
            $today = date('Y-m-d');
            
            // Cập nhật trạng thái "Đang diễn ra" cho các ưu đãi trong thời gian hiệu lực
            $sql1 = "UPDATE {$this->table} SET ud_trangthai = 'Đang diễn ra' 
                     WHERE ud_thoigianbatdau <= ? AND ud_thoigianketthuc >= ? AND ud_trangthai != 'Kết thúc'";
            $stmt1 = $this->db->prepare($sql1);
            $stmt1->execute([$today, $today]);

            // Cập nhật trạng thái "Kết thúc" cho các ưu đãi đã hết hạn
            $sql2 = "UPDATE {$this->table} SET ud_trangthai = 'Kết thúc' 
                     WHERE ud_thoigianketthuc < ?";
            $stmt2 = $this->db->prepare($sql2);
            $stmt2->execute([$today]);

            // Cập nhật trạng thái "Sắp diễn ra" cho các ưu đãi chưa bắt đầu
            $sql3 = "UPDATE {$this->table} SET ud_trangthai = 'Sắp diễn ra' 
                     WHERE ud_thoigianbatdau > ?";
            $stmt3 = $this->db->prepare($sql3);
            $stmt3->execute([$today]);

            return true;
        } catch (Exception $e) {
            error_log("Error updating status by date: " . $e->getMessage());
            return false;
        }
    }
}