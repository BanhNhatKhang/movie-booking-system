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
}