<?php

namespace App\Models;

use App\Core\BaseModel;
use Exception;
use PDO;

class UuDaiTrangChu extends BaseModel
{
    protected $table = 'uu_dai_trang_chu';
    protected $primaryKey = 'udtc_mauudai';

    /**
     * Tạo ID mới cho ưu đãi
     */
    public function generateNewId()
    {
        try {
            $sql = "SELECT udtc_mauudai FROM {$this->table} ORDER BY udtc_mauudai DESC LIMIT 1";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                $lastId = $result['udtc_mauudai'];
                $number = (int)substr($lastId, 2); // Remove "UD" prefix
                $newNumber = $number + 1;
                $newId = 'UD' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
            } else {
                $newId = 'UD001';
            }

            return $newId;
        } catch (Exception $e) {
            error_log("Error generating new ID: " . $e->getMessage());
            return 'UD' . str_pad(rand(100, 999), 3, '0', STR_PAD_LEFT);
        }
    }

    /**
     * ✅ Tạo ưu đãi mới - chỉ cần 2 trường
     */
    public function createUuDai($data)
    {
        try {
            $sql = "INSERT INTO {$this->table} (udtc_mauudai, udtc_anhuudai) VALUES (?, ?)";
            
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                $data['udtc_mauudai'],
                $data['udtc_anhuudai']
            ]);
        } catch (Exception $e) {
            error_log("Error creating uu dai: " . $e->getMessage());
            return false;
        }
    }

    /**
     * ✅ Cập nhật ưu đãi - chỉ cập nhật ảnh
     */
    public function updateUuDai($id, $data)
    {
        try {
            $sql = "UPDATE {$this->table} SET udtc_anhuudai = ? WHERE udtc_mauudai = ?";
            
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                $data['udtc_anhuudai'],
                $id
            ]);
        } catch (Exception $e) {
            error_log("Error updating uu dai: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Lấy ưu đãi theo ID
     */
    public function getUuDaiById($id)
    {
        try {
            $sql = "SELECT * FROM {$this->table} WHERE udtc_mauudai = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error getting uu dai by ID: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Lấy tất cả ưu đãi
     */
    public function getAllUuDai()
    {
        try {
            $sql = "SELECT * FROM {$this->table} ORDER BY udtc_mauudai ASC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error getting all uu dai: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Xóa ưu đãi
     */
    public function deleteUuDai($id)
    {
        try {
            $sql = "DELETE FROM {$this->table} WHERE udtc_mauudai = ?";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$id]);
        } catch (Exception $e) {
            error_log("Error deleting uu dai: " . $e->getMessage());
            return false;
        }
    }

    /**
     * ✅ Loại bỏ method checkNameExists vì không còn trường tên
     */
}