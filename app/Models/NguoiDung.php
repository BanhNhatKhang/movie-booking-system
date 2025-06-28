<?php


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
            $id = str_pad(strval(mt_rand(0, 9999999)), 7, '0', STR_PAD_LEFT);
            $exists = $this->getById($id);
        } while ($exists);
        return $id;
    }

    // CREATE
    public function create($data) {
        $sql = "INSERT INTO {$this->table} 
            (nd_id, nd_hoten, nd_ngaysinh, nd_gioitinh, nd_sdt, nd_cccd, nd_email, nd_tendangnhap, nd_matkhau, nd_ngaydangky, nd_role)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['nd_id'],
            $data['nd_hoten'],
            $data['nd_ngaysinh'],
            $data['nd_gioitinh'],
            $data['nd_sdt'],
            $data['nd_cccd'],
            $data['nd_email'],
            $data['nd_tendangnhap'],
            $data['nd_matkhau'],
            $data['nd_ngaydangky'],
            $data['nd_role']
        ]);
    }

    // READ ALL
    public function getAll() {
        $sql = "SELECT * FROM {$this->table} ORDER BY nd_id DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // READ BY ID
    public function getById($id) {
        $sql = "SELECT * FROM {$this->table} WHERE nd_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // UPDATE
    public function update($id, $data) {
        $sql = "UPDATE {$this->table} SET 
            nd_hoten = ?, nd_ngaysinh = ?, nd_gioitinh = ?, nd_sdt = ?, nd_cccd = ?, nd_email = ?, nd_tendangnhap = ?, nd_matkhau = ?, nd_ngaydangky = ?, nd_role = ?
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

    // Tìm user theo tên đăng nhập hoặc email
    public function findByUsernameOrEmail($username) {
        $sql = "SELECT * FROM {$this->table} WHERE nd_tendangnhap = ? OR nd_email = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$username, $username]);
        return $stmt->fetch();
    }

    public function updatePassword($id, $newPassword) {
        $sql = "UPDATE {$this->table} SET nd_matkhau = ? WHERE {$this->primaryKey} = ?";
        try {
            $stmt = $this->db->prepare($sql);
            $result = $stmt->execute([$newPassword, $id]);
            return $result;
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
            return $stmt->fetchAll();
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
        $fields = [];
        $values = [];
        
        // Chỉ update những trường được truyền vào
        foreach ($data as $key => $value) {
            $fields[] = "$key = ?";
            $values[] = $value;
        }
        
        $values[] = $id; // Thêm ID vào cuối
        
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
            return $stmt->fetch();
        } catch (Exception $e) {
            error_log("Find by email error: " . $e->getMessage());
            return null;
        }
    }
}