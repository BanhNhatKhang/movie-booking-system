<?php
// filepath: c:\mysites\ct27501-project-BanhNhatKhang-1\app\Models\NguoiDung.php

namespace App\Models;

use App\Core\BaseModel;
use Exception;

class NguoiDung extends BaseModel
{
    protected $table = 'nguoi_dung';
    protected $primaryKey = 'nd_id';

    // Sinh id ngẫu nhiên 7 số, đảm bảo không trùng
    public function generateRandomId()
    {
        do {
            $id = str_pad(strval(mt_rand(1000000, 9999999)), 7, '0', STR_PAD_LEFT);  // ✅ Sửa: bắt đầu từ 1000000
            $exists = $this->getById($id);
        } while ($exists);
        return $id;
    }

    public function create($data) {
        $sql = "INSERT INTO {$this->table} 
            (nd_id, nd_hoten, nd_ngaysinh, nd_gioitinh, nd_sdt, nd_cccd, nd_email, nd_tendangnhap, nd_matkhau, nd_ngaydangky, nd_role, nd_trangthai)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['nd_id'],
            $data['nd_hoten'] ?? null,
            $data['nd_ngaysinh'] ?? null,
            $data['nd_gioitinh'] ?? null,
            $data['nd_sdt'] ?? null,
            $data['nd_cccd'] ?? null,
            $data['nd_email'],
            $data['nd_tendangnhap'] ?? null,
            $data['nd_matkhau'],
            $data['nd_ngaydangky'] ?? date('Y-m-d'),
            $data['nd_role'] ?? 'user',
            $data['nd_trangthai'] ?? 'active'  
        ]);
    }

    public function getAll() {
        $sql = "SELECT * FROM {$this->table} ORDER BY nd_id DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $sql = "SELECT * FROM {$this->table} WHERE nd_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function update($id, $data) {
        $sql = "UPDATE {$this->table} SET 
            nd_hoten = ?, nd_ngaysinh = ?, nd_gioitinh = ?, nd_sdt = ?, nd_cccd = ?, nd_email = ?, nd_tendangnhap = ?, nd_matkhau = ?, nd_ngaydangky = ?, nd_role = ?, nd_trangthai = ?
            WHERE nd_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['nd_hoten'],
            $data['nd_ngaysinh'],
            $data['nd_gioitinh'],
            $data['nd_sdt'],
            $data['nd_cccd'],
            $data['nd_email'],
            $data['nd_tendangnhap'],
            $data['nd_matkhau'],
            $data['nd_ngaydangky'],
            $data['nd_role'],
            $data['nd_trangthai'] ?? 'active', 
            $id
        ]);
    }

    // DELETE
    public function delete($id) {
        $sql = "DELETE FROM {$this->table} WHERE nd_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }

    // Kiểm tra tồn tại theo cột
    public function checkExists($column, $value) {
        $sql = "SELECT COUNT(*) FROM {$this->table} WHERE {$column} = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$value]);
        return $stmt->fetchColumn() > 0;
    }

    public function findByUsernameOrEmail($username) {
        $sql = "SELECT * FROM {$this->table} WHERE nd_tendangnhap = ? OR nd_email = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$username, $username]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function updatePassword($id, $newPassword) {
        $sql = "UPDATE {$this->table} SET nd_matkhau = ? WHERE {$this->primaryKey} = ?";
        try {
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$newPassword, $id]);
        } catch (Exception $e) {
            error_log("Update password error: " . $e->getMessage());
            return false;
        }
    }

    public function getAllWithFilter($search = '', $role = '', $status = '')
    {
        $sql = "SELECT * FROM {$this->table} WHERE 1=1";
        $params = [];
        
        if (!empty($search)) {
            $sql .= " AND (nd_hoten LIKE ? OR nd_email LIKE ? OR nd_tendangnhap LIKE ?)";
            $searchParam = "%{$search}%";
            $params[] = $searchParam;
            $params[] = $searchParam;
            $params[] = $searchParam;
        }
        
        if (!empty($role)) {
            $sql .= " AND nd_role = ?";
            $params[] = $role;
        }
        
        if (!empty($status)) {
            $sql .= " AND nd_trangthai = ?";
            $params[] = $status;
        }
        
        $sql .= " ORDER BY nd_ngaydangky DESC";
        
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(\PDO::FETCH_ASSOC); 
        } catch (Exception $e) {
            error_log("Get users with filter error: " . $e->getMessage());
            return [];
        }
    }
    public function updateStatus($id, $status)
    {
        $sql = "UPDATE {$this->table} SET nd_trangthai = ? WHERE {$this->primaryKey} = ?";
        try {
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$status, $id]);
        } catch (Exception $e) {
            error_log("Update status error: " . $e->getMessage());
            return false;
        }
    }
    public function updatePartial($id, $data) {
        // Danh sách field được phép update
        $allowedFields = [
            'nd_hoten', 'nd_ngaysinh', 'nd_gioitinh', 'nd_sdt', 
            'nd_cccd', 'nd_email', 'nd_tendangnhap', 'nd_matkhau', 
            'nd_ngaydangky', 'nd_role', 'nd_trangthai'  
        ];
        
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
        
        $values[] = $id;
        
        $sql = "UPDATE {$this->table} SET " . implode(', ', $fields) . " WHERE {$this->primaryKey} = ?";
        
        try {
            $stmt = $this->db->prepare($sql);
            return $stmt->execute($values);
        } catch (Exception $e) {
            error_log("Update partial error: " . $e->getMessage());
            return false;
        }
    }
    
    public function findByEmail($email) {
        $sql = "SELECT * FROM {$this->table} WHERE nd_email = ?";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$email]);
            return $stmt->fetch(\PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Find by email error: " . $e->getMessage());
            return null;
        }
    }

    public function toggleStatus($id)
    {
        $user = $this->getById($id);
        if (!$user) return false;
        
        $newStatus = ($user['nd_trangthai'] === 'active') ? 'locked' : 'active';
        return $this->updateStatus($id, $newStatus);
    }

    public function getUserStats()
    {
        $sql = "SELECT 
                    COUNT(*) as total_users,
                    SUM(CASE WHEN nd_trangthai = 'active' THEN 1 ELSE 0 END) as active_users,
                    SUM(CASE WHEN nd_trangthai = 'locked' THEN 1 ELSE 0 END) as locked_users,
                    SUM(CASE WHEN nd_role = 'admin' THEN 1 ELSE 0 END) as admin_users,
                    SUM(CASE WHEN nd_role = 'user' THEN 1 ELSE 0 END) as regular_users
                FROM {$this->table}";
        
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetch(\PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Get stats error: " . $e->getMessage());
            return [
                'total_users' => 0,
                'active_users' => 0,
                'locked_users' => 0,
                'admin_users' => 0,
                'regular_users' => 0
            ];
        }
    }

    public function getActiveUsers()
    {
        $sql = "SELECT * FROM {$this->table} WHERE nd_trangthai = 'active' ORDER BY nd_ngaydangky DESC";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Get active users error: " . $e->getMessage());
            return [];
        }
    }
    public function getAllWithFilterPaginated($search = '', $role = '', $status = '', $page = 1, $limit = 15)
    {
        $offset = ($page - 1) * $limit;
        
        $sql = "SELECT * FROM {$this->table} WHERE 1=1";
        $params = [];
        
        if (!empty($search)) {
            $sql .= " AND (nd_hoten LIKE ? OR nd_email LIKE ?)";
            $params[] = "%{$search}%";
            $params[] = "%{$search}%";
        }
        
        if (!empty($role)) {
            $sql .= " AND nd_role = ?";
            $params[] = $role;
        }
        
        if (!empty($status)) {
            $sql .= " AND nd_trangthai = ?";
            $params[] = $status;
        }
        
        $sql .= " ORDER BY nd_ngaydangky DESC LIMIT ? OFFSET ?";
        $params[] = $limit;
        $params[] = $offset;
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getTotalCount($search = '', $role = '', $status = '')
    {
        $sql = "SELECT COUNT(*) FROM {$this->table} WHERE 1=1";
        $params = [];
        
        if (!empty($search)) {
            $sql .= " AND (nd_hoten LIKE ? OR nd_email LIKE ?)";
            $params[] = "%{$search}%";
            $params[] = "%{$search}%";
        }
        
        if (!empty($role)) {
            $sql .= " AND nd_role = ?";
            $params[] = $role;
        }
        
        if (!empty($status)) {
            $sql .= " AND nd_trangthai = ?";
            $params[] = $status;
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn();
    }
}