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
     * Lấy tất cả ưu đãi
     */
    public function getAllUuDai()
    {
        try {
            $sql = "SELECT * FROM {$this->table} ORDER BY udtc_mauudai ASC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // ✅ DEBUG: Log để kiểm tra
            error_log("SQL Query: " . $sql);
            error_log("Result count: " . count($result));
            error_log("Result data: " . print_r($result, true));
            
            return $result;
        } catch (Exception $e) {
            error_log("Error in getAllUuDai: " . $e->getMessage());
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
     * Tạo mã ưu đãi tự động
     */
    public function generateNewId()
    {
        try {
            $sql = "SELECT udtc_mauudai FROM {$this->table} ORDER BY udtc_mauudai DESC LIMIT 1";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $last = $stmt->fetchColumn();
            
            if ($last) {
                $num = intval(substr($last, 2)) + 1;
                return 'UD' . str_pad($num, 3, '0', STR_PAD_LEFT);
            }
            return 'UD001';
        } catch (Exception $e) {
            error_log("Error generating new ID: " . $e->getMessage());
            return 'UD001';
        }
    }

    /**
     * Thêm ưu đãi mới
     */
    public function createUuDai($data)
    {
        try {
            $sql = "INSERT INTO {$this->table} (udtc_mauudai, udtc_anhuudai, udtc_tenuudai) 
                    VALUES (?, ?, ?)";
            
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                $data['udtc_mauudai'],
                $data['udtc_anhuudai'],
                $data['udtc_tenuudai']
            ]);
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
                    udtc_anhuudai = ?, 
                    udtc_tenuudai = ? 
                    WHERE {$this->primaryKey} = ?";
            
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                $data['udtc_anhuudai'],
                $data['udtc_tenuudai'],
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
     * Kiểm tra ưu đãi có tồn tại không
     */
    public function checkUuDaiExists($id)
    {
        try {
            $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE {$this->primaryKey} = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$id]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return ($result['count'] ?? 0) > 0;
        } catch (Exception $e) {
            error_log("Error checking uu dai exists: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Kiểm tra tên ưu đãi đã tồn tại
     */
    public function checkNameExists($name, $excludeId = null)
    {
        try {
            $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE udtc_tenuudai = ?";
            $params = [$name];

            if ($excludeId) {
                $sql .= " AND {$this->primaryKey} != ?";
                $params[] = $excludeId;
            }

            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return ($result['count'] ?? 0) > 0;
        } catch (Exception $e) {
            error_log("Error checking name exists: " . $e->getMessage());
            return false;
        }
    }
}