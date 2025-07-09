<?php

namespace App\Models;

use App\Core\BaseModel;
use PDO;
use PDOException;
use Exception;

class ThanhToan extends BaseModel
{
    protected $table = 'thanh_toan';
    protected $primaryKey = 'tt_mathanhtoan';
    
    /**
     * Tạo thanh toán mới
     */
    public function create($data)
    {
        try {
            $sql = "INSERT INTO thanh_toan (tt_mathanhtoan, tt_sotien, tt_phuongthuc, tt_thoigianthanhtoan, nd_id) 
                    VALUES (?, ?, ?, ?, ?)";
            
            $stmt = $this->db->prepare($sql);
            $result = $stmt->execute([
                $data['tt_mathanhtoan'],
                $data['tt_sotien'],
                $data['tt_phuongthuc'],
                $data['tt_thoigianthanhtoan'],
                $data['nd_id']
            ]);
            
            return $result;
            
        } catch (Exception $e) {
            error_log("Error creating thanh_toan: " . $e->getMessage());
            return false;
        }
    }
    
    // Lấy tất cả thanh toán
    public function getAllThanhToan($limit = 50, $offset = 0)
    {
        try {
            $sql = "SELECT 
                        tt.*,
                        nd.nd_hoten,
                        nd.nd_email
                    FROM thanh_toan tt
                    LEFT JOIN nguoi_dung nd ON tt.nd_id = nd.nd_id
                    ORDER BY tt.tt_thoigianthanhtoan DESC
                    LIMIT ? OFFSET ?";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$limit, $offset]);
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting thanh toan list: " . $e->getMessage());
            return [];
        }
    }
    
    // Lấy chi tiết thanh toán theo ID
    public function getThanhToanById($thanhToanId)
    {
        try {
            $sql = "SELECT 
                        tt.*,
                        nd.nd_hoten,
                        nd.nd_email,
                        nd.nd_sdt
                    FROM thanh_toan tt
                    LEFT JOIN nguoi_dung nd ON tt.nd_id = nd.nd_id
                    WHERE tt.tt_mathanhtoan = ?";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$thanhToanId]);
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting thanh toan detail: " . $e->getMessage());
            return false;
        }
    }
    
    // Tạo mã thanh toán tự động
    public function generateThanhToanCode()
    {
        try {
            $sql = "SELECT tt_mathanhtoan FROM thanh_toan ORDER BY tt_mathanhtoan DESC LIMIT 1";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($result) {
                $lastCode = $result['tt_mathanhtoan'];
                $number = intval(substr($lastCode, 2)) + 1;
                return 'TT' . str_pad($number, 8, '0', STR_PAD_LEFT);
            } else {
                return 'TT00000001';
            }
        } catch (PDOException $e) {
            error_log("Error generating thanh toan code: " . $e->getMessage());
            return 'TT' . str_pad(rand(1, 99999999), 8, '0', STR_PAD_LEFT);
        }
    }
    
    // Lấy thanh toán theo user ID
    public function getThanhToanByUserId($userId)
    {
        try {
            $sql = "SELECT * FROM thanh_toan WHERE nd_id = ? ORDER BY tt_thoigianthanhtoan DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$userId]);
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting thanh toan by user id: " . $e->getMessage());
            return [];
        }
    }
    
    // Cập nhật thanh toán
    public function updateThanhToan($thanhToanId, $data)
    {
        try {
            $allowedFields = ['tt_sotien', 'tt_phuongthuc', 'tt_thoigianthanhtoan', 'nd_id'];
            
            $fields = [];
            $values = [];
            
            foreach ($data as $key => $value) {
                if (in_array($key, $allowedFields)) {
                    $fields[] = "$key = ?";
                    $values[] = $value;
                }
            }
            
            if (empty($fields)) {
                return false;
            }
            
            $values[] = $thanhToanId;
            
            $sql = "UPDATE thanh_toan SET " . implode(', ', $fields) . " WHERE tt_mathanhtoan = ?";
            
            $stmt = $this->db->prepare($sql);
            return $stmt->execute($values);
        } catch (PDOException $e) {
            error_log("Error updating thanh toan: " . $e->getMessage());
            return false;
        }
    }
    
    // Xóa thanh toán
    public function deleteThanhToan($thanhToanId)
    {
        try {
            $sql = "DELETE FROM thanh_toan WHERE tt_mathanhtoan = ?";
            $stmt = $this->db->prepare($sql);
            
            return $stmt->execute([$thanhToanId]);
        } catch (PDOException $e) {
            error_log("Error deleting thanh toan: " . $e->getMessage());
            return false;
        }
    }
    
    // Thống kê thanh toán
    public function getThanhToanStats()
    {
        try {
            $sql = "SELECT 
                        COUNT(*) as total_payments,
                        SUM(tt_sotien) as total_amount,
                        AVG(tt_sotien) as avg_amount,
                        COUNT(DISTINCT nd_id) as unique_customers
                    FROM thanh_toan
                    WHERE tt_thoigianthanhtoan >= DATE_SUB(NOW(), INTERVAL 30 DAY)";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting thanh toan stats: " . $e->getMessage());
            return [
                'total_payments' => 0,
                'total_amount' => 0,
                'avg_amount' => 0,
                'unique_customers' => 0
            ];
        }
    }
    
    // Lấy thanh toán theo phương thức
    public function getThanhToanByMethod($method)
    {
        try {
            $sql = "SELECT * FROM thanh_toan WHERE tt_phuongthuc = ? ORDER BY tt_thoigianthanhtoan DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$method]);
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting thanh toan by method: " . $e->getMessage());
            return [];
        }
    }
    
    // Đếm tổng số thanh toán
    public function countThanhToan($filters = [])
    {
        try {
            $sql = "SELECT COUNT(*) FROM thanh_toan tt
                    LEFT JOIN nguoi_dung nd ON tt.nd_id = nd.nd_id";
            
            $conditions = [];
            $params = [];
            
            if (!empty($filters['search'])) {
                $conditions[] = "(nd.nd_hoten ILIKE ? OR tt.tt_mathanhtoan ILIKE ?)";
                $searchParam = '%' . $filters['search'] . '%';
                $params[] = $searchParam;
                $params[] = $searchParam;
            }
            
            if (!empty($filters['method'])) {
                $conditions[] = "tt.tt_phuongthuc = ?";
                $params[] = $filters['method'];
            }
            
            if (!empty($filters['date_from'])) {
                $conditions[] = "tt.tt_thoigianthanhtoan >= ?";
                $params[] = $filters['date_from'];
            }
            
            if (!empty($filters['date_to'])) {
                $conditions[] = "tt.tt_thoigianthanhtoan <= ?";
                $params[] = $filters['date_to'];
            }
            
            if (!empty($conditions)) {
                $sql .= " WHERE " . implode(" AND ", $conditions);
            }
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("Error counting thanh toan: " . $e->getMessage());
            return 0;
        }
    }
}