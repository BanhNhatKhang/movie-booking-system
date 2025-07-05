<?php
// filepath: d:\Server\ct27501-project-BanhNhatKhang\app\Models\Phim.php

namespace App\Models;

use App\Core\BaseModel;
use Exception;
use PDO;

class Phim extends BaseModel
{
    protected $table = 'phim';
    protected $primaryKey = 'p_maphim';

    /**
     * Lấy tất cả phim với thông tin poster
     */
    public function getAllPhim($search = '', $limit = 10, $offset = 0, $status = '')
    {
        try {
            $sql = "SELECT p.*, pt.pt_anhposter 
                    FROM {$this->table} p 
                    LEFT JOIN poster pt ON p.p_maposter = pt.pt_maposter 
                    WHERE 1=1";
            $params = [];

            // Search functionality
            if (!empty($search)) {
                $sql .= " AND (p.p_tenphim ILIKE ? OR p.p_theloai ILIKE ? OR p.p_daodien ILIKE ? OR p.p_dienvien ILIKE ?)";
                $searchTerm = "%{$search}%";
                $params[] = $searchTerm;
                $params[] = $searchTerm;
                $params[] = $searchTerm;
                $params[] = $searchTerm;
            }

            // Filter by status
            if (!empty($status)) {
                $sql .= " AND p.p_trangthai = ?";
                $params[] = $status;
            }

            $sql .= " ORDER BY p.p_phathanh DESC, p.p_maphim DESC LIMIT ? OFFSET ?";
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
            $sql = "SELECT COUNT(*) as total FROM {$this->table} p WHERE 1=1";
            $params = [];

            if (!empty($search)) {
                $sql .= " AND (p.p_tenphim ILIKE ? OR p.p_theloai ILIKE ? OR p.p_daodien ILIKE ? OR p.p_dienvien ILIKE ?)";
                $searchTerm = "%{$search}%";
                $params[] = $searchTerm;
                $params[] = $searchTerm;
                $params[] = $searchTerm;
                $params[] = $searchTerm;
            }

            if (!empty($status)) {
                $sql .= " AND p.p_trangthai = ?";
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
     * Lấy phim theo ID với thông tin poster
     */
    public function getPhimById($id)
    {
        try {
            $sql = "SELECT p.*, pt.pt_anhposter 
                    FROM {$this->table} p 
                    LEFT JOIN poster pt ON p.p_maposter = pt.pt_maposter 
                    WHERE p.{$this->primaryKey} = ?";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error getting phim by ID: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Tạo mã poster tự động
     */
    private function generatePosterCode()
    {
        try {
            $sql = "SELECT pt_maposter FROM poster ORDER BY pt_maposter DESC LIMIT 1";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $lastPoster = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($lastPoster) {
                // Extract number from PT001 -> 001 -> 1 -> 2 -> 002 -> PT002
                $lastNumber = intval(substr($lastPoster['pt_maposter'], 2));
                $newNumber = $lastNumber + 1;
                return 'PT' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
            } else {
                return 'PT001';
            }
        } catch (Exception $e) {
            error_log("Error generating poster code: " . $e->getMessage());
            return 'PT001';
        }
    }

    /**
     * Thêm poster mới
     */
    private function createPoster($posterPath)
    {
        try {
            $posterCode = $this->generatePosterCode();
            $sql = "INSERT INTO poster (pt_maposter, pt_anhposter) VALUES (?, ?)";
            
            $stmt = $this->db->prepare($sql);
            if ($stmt->execute([$posterCode, $posterPath])) {
                return $posterCode;
            }
            return false;
        } catch (Exception $e) {
            error_log("Error creating poster: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Cập nhật poster
     */
    private function updatePoster($posterCode, $posterPath)
    {
        try {
            $sql = "UPDATE poster SET pt_anhposter = ? WHERE pt_maposter = ?";
            
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$posterPath, $posterCode]);
        } catch (Exception $e) {
            error_log("Error updating poster: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Thêm phim mới
     */
    public function createPhim($data)
    {
        try {
            // Begin transaction
            $this->beginTransaction();

            // Handle poster first if provided
            $posterCode = null;
            if (!empty($data['poster_path'])) {
                $posterCode = $this->createPoster($data['poster_path']);
                if (!$posterCode) {
                    $this->rollBack();
                    return false;
                }
            }

            // Insert phim
            $sql = "INSERT INTO {$this->table} (
                p_maphim, p_tenphim, p_theloai, p_thoiluong, p_phathanh, 
                p_mota, p_trailer, p_trangthai, p_dienvien, p_daodien, p_maposter
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
                $posterCode
            ];

            $stmt = $this->db->prepare($sql);
            if ($stmt->execute($params)) {
                $this->commit();
                return true;
            } else {
                $this->rollBack();
                return false;
            }
        } catch (Exception $e) {
            $this->rollBack();
            error_log("Error creating phim: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Cập nhật phim
     */
    public function updatePhim($id, $data)
    {
        try {
            // Begin transaction
            $this->beginTransaction();

            // Get existing phim
            $existingPhim = $this->getPhimById($id);
            if (!$existingPhim) {
                $this->rollBack();
                return false;
            }

            // Handle poster update
            $posterCode = $existingPhim['p_maposter'];
            if (!empty($data['poster_path'])) {
                if ($posterCode) {
                    // Update existing poster
                    if (!$this->updatePoster($posterCode, $data['poster_path'])) {
                        $this->rollBack();
                        return false;
                    }
                } else {
                    // Create new poster
                    $posterCode = $this->createPoster($data['poster_path']);
                    if (!$posterCode) {
                        $this->rollBack();
                        return false;
                    }
                }
            }

            // Update phim
            $sql = "UPDATE {$this->table} SET 
                p_tenphim = ?, p_theloai = ?, p_thoiluong = ?, p_phathanh = ?, 
                p_mota = ?, p_trailer = ?, p_trangthai = ?, p_dienvien = ?, 
                p_daodien = ?, p_maposter = ?
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
                $posterCode,
                $id
            ];

            $stmt = $this->db->prepare($sql);
            if ($stmt->execute($params)) {
                $this->commit();
                return true;
            } else {
                $this->rollBack();
                return false;
            }
        } catch (Exception $e) {
            $this->rollBack();
            error_log("Error updating phim: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Thay đổi trạng thái phim (không xóa hoàn toàn)
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
        try {
            $sql = "SELECT p.*, pt.pt_anhposter 
                    FROM {$this->table} p 
                    LEFT JOIN poster pt ON p.p_maposter = pt.pt_maposter 
                    WHERE p.p_trangthai = 'active' 
                    ORDER BY p.p_phathanh DESC";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error getting active phim: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Lấy phim sắp chiếu
     */
    public function getComingSoonPhim()
    {
        try {
            $sql = "SELECT p.*, pt.pt_anhposter 
                    FROM {$this->table} p 
                    LEFT JOIN poster pt ON p.p_maposter = pt.pt_maposter 
                    WHERE p.p_trangthai = 'coming_soon' 
                    ORDER BY p.p_phathanh ASC";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error getting coming soon phim: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Lấy thống kê phim theo trạng thái
     */
    public function getPhimStatistics()
    {
        try {
            $sql = "SELECT 
                        p_trangthai,
                        COUNT(*) as count
                    FROM {$this->table} 
                    GROUP BY p_trangthai
                    ORDER BY p_trangthai";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error getting phim statistics: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Tìm kiếm phim nâng cao
     */
    public function searchPhim($keyword, $genre = '', $status = '', $year = '')
    {
        try {
            $sql = "SELECT p.*, pt.pt_anhposter 
                    FROM {$this->table} p 
                    LEFT JOIN poster pt ON p.p_maposter = pt.pt_maposter 
                    WHERE 1=1";
            $params = [];

            if (!empty($keyword)) {
                $sql .= " AND (p.p_tenphim ILIKE ? OR p.p_dienvien ILIKE ? OR p.p_daodien ILIKE ?)";
                $searchTerm = "%{$keyword}%";
                $params[] = $searchTerm;
                $params[] = $searchTerm;
                $params[] = $searchTerm;
            }

            if (!empty($genre)) {
                $sql .= " AND p.p_theloai ILIKE ?";
                $params[] = "%{$genre}%";
            }

            if (!empty($status)) {
                $sql .= " AND p.p_trangthai = ?";
                $params[] = $status;
            }

            if (!empty($year)) {
                $sql .= " AND EXTRACT(YEAR FROM p.p_phathanh) = ?";
                $params[] = $year;
            }

            $sql .= " ORDER BY p.p_phathanh DESC";

            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error searching phim: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Transaction methods
     */
    private function beginTransaction()
    {
        $this->db->beginTransaction();
    }

    private function commit()
    {
        $this->db->commit();
    }

    private function rollBack()
    {
        $this->db->rollback();
    }
}