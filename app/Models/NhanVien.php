<?php
namespace App\Models;

use App\Core\BaseModel;

class NhanVien extends BaseModel
{
    protected $table = 'nhan_vien';
    
    public function getByUsername($username)
    {
        $sql = "SELECT * FROM {$this->table} WHERE username = :username";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':username', $username);
        $stmt->execute();
        return $stmt->fetch();
    }
    
    public function getByEmail($email)
    {
        $sql = "SELECT * FROM {$this->table} WHERE email = :email";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':email', $email);
        $stmt->execute();
        return $stmt->fetch();
    }
    
    public function searchNhanVien($keyword)
    {
        return $this->search(['ho_ten', 'email', 'so_dien_thoai'], $keyword);
    }
}