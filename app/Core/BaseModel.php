<?php

namespace App\Core;

abstract class BaseModel
{
    protected $db;
    protected $table;
    protected $primaryKey = 'id';
    
    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function getAll()
    {
        $sql = "SELECT * FROM {$this->table}";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    public function getById($id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        return $stmt->fetch();
    }
    
    public function create($data)
    {
        $fields = array_keys($data);
        $placeholders = array_map(function($field) { return ':' . $field; }, $fields);
        
        $sql = "INSERT INTO {$this->table} (" . implode(', ', $fields) . ") VALUES (" . implode(', ', $placeholders) . ") RETURNING {$this->primaryKey}";
        
        $stmt = $this->db->prepare($sql);
        foreach ($data as $field => $value) {
            $stmt->bindValue(':' . $field, $value);
        }
        
        if ($stmt->execute()) {
            $result = $stmt->fetch();
            return $result[$this->primaryKey];
        }
        return false;
    }
    
    public function update($id, $data)
    {
        $fields = [];
        foreach (array_keys($data) as $field) {
            $fields[] = "$field = :$field";
        }
        
        $sql = "UPDATE {$this->table} SET " . implode(', ', $fields) . " WHERE {$this->primaryKey} = :id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id);
        
        foreach ($data as $field => $value) {
            $stmt->bindValue(':' . $field, $value);
        }
        
        return $stmt->execute();
    }
    
    public function delete($id)
    {
        $sql = "DELETE FROM {$this->table} WHERE {$this->primaryKey} = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id);
        return $stmt->execute();
    }
    
    public function search($columns, $keyword)
    {
        if (empty($columns) || empty($keyword)) {
            return [];
        }
        
        $conditions = [];
        foreach ($columns as $column) {
            $conditions[] = "{$column} ILIKE :keyword";
        }
        $whereClause = implode(' OR ', $conditions);
        
        $sql = "SELECT * FROM {$this->table} WHERE {$whereClause}";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':keyword', '%' . $keyword . '%');
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
}