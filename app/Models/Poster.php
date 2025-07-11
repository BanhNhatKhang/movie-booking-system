<?php
namespace App\Models;

use App\Core\BaseModel;

class Poster extends BaseModel
{
    protected $table = 'poster';
    protected $primaryKey = 'pt_maposter';

    // CREATE
    public function create($data) {
        $sql = "INSERT INTO {$this->table} (pt_maposter, pt_anhposter) VALUES (?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$data['pt_maposter'], $data['pt_anhposter']]);
    }

    // READ ALL
    public function getAll() {
        $sql = "SELECT * FROM {$this->table} ORDER BY pt_maposter DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // READ BY ID
    public function getById($id) {
        $sql = "SELECT * FROM {$this->table} WHERE pt_maposter = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // UPDATE
    public function update($id, $data) {
        $sql = "UPDATE {$this->table} SET pt_anhposter = ? WHERE pt_maposter = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$data['pt_anhposter'], $id]);
    }

    // DELETE
    public function delete($id) {
        $sql = "DELETE FROM {$this->table} WHERE pt_maposter = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }
    public function generateNewId()
    {
        $sql = "SELECT pt_maposter FROM {$this->table} ORDER BY pt_maposter DESC LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $last = $stmt->fetchColumn();
        if ($last) {
            $num = intval(substr($last, 2)) + 1;
            return 'PT' . str_pad($num, 2, '0', STR_PAD_LEFT);
        }
        return 'PT01';
    }
    public function getAllLimit($limit, $offset)
    {
        $sql = "SELECT * FROM poster ORDER BY pt_maposter DESC LIMIT ? OFFSET ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$limit, $offset]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function countAll()
    {
        $sql = "SELECT COUNT(*) FROM poster";
        return $this->db->query($sql)->fetchColumn();
    }
}