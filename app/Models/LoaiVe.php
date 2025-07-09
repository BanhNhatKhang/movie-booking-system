<?php

namespace App\Models;

use App\Core\BaseModel;
use Exception;

class LoaiVe extends BaseModel
{
    protected $table = 'loai_ve';
    protected $primaryKey = 'lv_maloaive';

    /**
     * Lấy tất cả loại vé với phân trang và tìm kiếm
     */
    public function getAllLoaiVe($limit = 10, $offset = 0, $filters = [])
    {
        try {
            $sql = "SELECT * FROM {$this->table} WHERE 1=1";
            $params = [];

            // Tìm kiếm theo tên loại vé hoặc mã loại vé
            if (!empty($filters['search'])) {
                $sql .= " AND (lv_tenloaive ILIKE :search OR lv_maloaive ILIKE :search)";
                $params['search'] = '%' . $filters['search'] . '%';
            }

            // Sắp xếp
            $sql .= " ORDER BY lv_maloaive ASC";

            // Phân trang
            $sql .= " LIMIT :limit OFFSET :offset";
            $params['limit'] = $limit;
            $params['offset'] = $offset;

            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll();

        } catch (Exception $e) {
            error_log("Error in getAllLoaiVe: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Đếm tổng số loại vé
     */
    public function countLoaiVe($filters = [])
    {
        try {
            $sql = "SELECT COUNT(*) as total FROM {$this->table} WHERE 1=1";
            $params = [];

            if (!empty($filters['search'])) {
                $sql .= " AND (lv_tenloaive ILIKE :search OR lv_maloaive ILIKE :search)";
                $params['search'] = '%' . $filters['search'] . '%';
            }

            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            $result = $stmt->fetch();
            return $result['total'];

        } catch (Exception $e) {
            error_log("Error in countLoaiVe: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Tạo loại vé mới
     */
    public function createLoaiVe($data)
    {
        try {
            // Sử dụng method create của BaseModel
            return $this->create([
                'lv_maloaive' => $data['ma_loai_ve'],
                'lv_tenloaive' => $data['ten_loai_ve'],
                'lv_giatien' => $data['gia_tien']
            ]);

        } catch (Exception $e) {
            error_log("Error in createLoaiVe: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Cập nhật loại vé
     */
    public function updateLoaiVe($id, $data)
    {
        try {
            // Sử dụng method update của BaseModel
            return $this->update($id, [
                'lv_tenloaive' => $data['ten_loai_ve'],
                'lv_giatien' => $data['gia_tien']
            ]);

        } catch (Exception $e) {
            error_log("Error in updateLoaiVe: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Xóa loại vé với kiểm tra ràng buộc
     */
    public function deleteLoaiVe($id)
    {
        try {
            // Kiểm tra xem loại vé có đang được sử dụng không
            $checkSql = "SELECT COUNT(*) as count FROM ve WHERE lv_maloaive = :id";
            $checkStmt = $this->db->prepare($checkSql);
            $checkStmt->execute(['id' => $id]);
            $result = $checkStmt->fetch();
            
            if ($result['count'] > 0) {
                throw new Exception("Không thể xóa loại vé này vì đang được sử dụng trong hệ thống");
            }

            // Sử dụng method delete của BaseModel
            return $this->delete($id);

        } catch (Exception $e) {
            error_log("Error in deleteLoaiVe: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Kiểm tra mã loại vé đã tồn tại
     */
    public function checkLoaiVeExists($maLoaiVe, $excludeId = null)
    {
        try {
            $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE lv_maloaive = :ma_loai_ve";
            $params = ['ma_loai_ve' => $maLoaiVe];

            if ($excludeId) {
                $sql .= " AND lv_maloaive != :exclude_id";
                $params['exclude_id'] = $excludeId;
            }

            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            $result = $stmt->fetch();
            return $result['count'] > 0;

        } catch (Exception $e) {
            error_log("Error in checkLoaiVeExists: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Tìm kiếm loại vé
     */
    public function searchLoaiVe($keyword)
    {
        // Sử dụng method search của BaseModel
        return $this->search(['lv_tenloaive', 'lv_maloaive'], $keyword);
    }

    /**
     * Lấy loại vé theo ID (override để sử dụng primary key đúng)
     */
    public function getLoaiVeById($id)
    {
        // Sử dụng method getById của BaseModel
        return $this->getById($id);
    }

    /**
     * Lấy tất cả loại vé (đơn giản)
     */
    public function getAllLoaiVeSimple()
    {
        // Sử dụng method getAll của BaseModel
        return $this->getAll();
    }
}