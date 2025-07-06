<?php

namespace App\Models;

use App\Core\BaseModel;
use PDO;
use PDOException;
use Exception;

class Ve extends BaseModel
{
    protected $table = 've';
    protected $primaryKey = 'v_mave';
    
    public function getAllVeWithDetails($limit = 10, $offset = 0, $filters = [])
    {
        try {
            $sql = "SELECT 
                        v.v_mave,
                        v.v_ngaydat,
                        v.v_tongtien,
                        v.v_trangthai,
                        COALESCE(nd.nd_hoten, 'Khách vãng lai') as nd_hoten,
                        COALESCE(nd.nd_email, '') as nd_email,
                        COALESCE(nd.nd_sdt, '') as nd_sdt,
                        COALESCE(p.p_tenphim, 'Phim demo') as p_tenphim,
                        lc.lc_ngaychieu,
                        lc.lc_giobatdau,
                        v.g_maghe,
                        COALESCE(pc.pc_tenphong, 'Phòng demo') as pc_tenphong,
                        COALESCE(lv.lv_tenloaive, 'Vé Standard') as lv_tenloaive,
                        COALESCE(lv.lv_giatien, 0) as lv_giatien,
                        COALESCE(tt.tt_phuongthuc, 'Tiền mặt') as tt_phuongthuc,
                        COALESCE(tt.tt_sotien, 0) as tt_sotien,
                        tt.tt_thoigianthanhtoan,
                        tt.tt_mathanhtoan
                    FROM ve v
                    LEFT JOIN nguoi_dung nd ON v.nd_id = nd.nd_id
                    LEFT JOIN lich_chieu lc ON v.lc_malichchieu = lc.lc_malichchieu
                    LEFT JOIN phim p ON lc.p_maphim = p.p_maphim
                    LEFT JOIN phong_chieu pc ON lc.pc_maphongchieu = pc.pc_maphongchieu
                    LEFT JOIN loai_ve lv ON v.lv_maloaive = lv.lv_maloaive
                    LEFT JOIN thanh_toan tt ON v.tt_mathanhtoan = tt.tt_mathanhtoan";
            
            $conditions = [];
            $params = [];
            
            if (!empty($filters['search'])) {
                $conditions[] = "(COALESCE(nd.nd_hoten, '') ILIKE ? OR COALESCE(p.p_tenphim, '') ILIKE ? OR v.v_mave ILIKE ?)";
                $searchParam = '%' . $filters['search'] . '%';
                $params[] = $searchParam;
                $params[] = $searchParam;
                $params[] = $searchParam;
            }
            
            if (!empty($filters['payment_method'])) {
                $conditions[] = "COALESCE(tt.tt_phuongthuc, 'Tiền mặt') = ?";
                $params[] = $filters['payment_method'];
            }
            
            if (!empty($filters['date'])) {
                $conditions[] = "v.v_ngaydat = ?";
                $params[] = $filters['date'];
            }
            
            // Thêm filter theo trạng thái in
            if (!empty($filters['print_status'])) {
                $conditions[] = "v.v_trangthai = ?";
                $params[] = $filters['print_status'];
            }
            
            if (!empty($conditions)) {
                $sql .= " WHERE " . implode(" AND ", $conditions);
            }
            
            $sql .= " ORDER BY v.v_ngaydat DESC LIMIT ? OFFSET ?";
            $params[] = $limit;
            $params[] = $offset;
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            error_log("Error in getAllVeWithDetails: " . $e->getMessage());
            return [];
        }
    }
    
    public function getVeStatsByTime()
    {
        try {
            $sql = "SELECT 
                        COUNT(*) as total_tickets,
                        COALESCE(SUM(v_tongtien), 0) as total_revenue,
                        COUNT(CASE WHEN v_trangthai = 'da_in' THEN 1 END) as tickets_printed,
                        COUNT(CASE WHEN v_trangthai = 'chua_in' THEN 1 END) as tickets_not_printed,
                        COUNT(CASE WHEN EXTRACT(YEAR FROM v_ngaydat) = EXTRACT(YEAR FROM CURRENT_DATE) THEN 1 END) as tickets_this_year,
                        COALESCE(SUM(CASE WHEN EXTRACT(YEAR FROM v_ngaydat) = EXTRACT(YEAR FROM CURRENT_DATE) THEN v_tongtien END), 0) as revenue_this_year,
                        COUNT(CASE WHEN EXTRACT(YEAR FROM v_ngaydat) = EXTRACT(YEAR FROM CURRENT_DATE) 
                                  AND EXTRACT(MONTH FROM v_ngaydat) = EXTRACT(MONTH FROM CURRENT_DATE) THEN 1 END) as tickets_this_month,
                        COALESCE(SUM(CASE WHEN EXTRACT(YEAR FROM v_ngaydat) = EXTRACT(YEAR FROM CURRENT_DATE) 
                                  AND EXTRACT(MONTH FROM v_ngaydat) = EXTRACT(MONTH FROM CURRENT_DATE) 
                                  THEN v_tongtien END), 0) as revenue_this_month,
                        COUNT(CASE WHEN v_ngaydat = CURRENT_DATE THEN 1 END) as tickets_today,
                        COALESCE(SUM(CASE WHEN v_ngaydat = CURRENT_DATE THEN v_tongtien END), 0) as revenue_today
                    FROM ve";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return [
                'total_tickets' => (int)($result['total_tickets'] ?? 0),
                'total_revenue' => (int)($result['total_revenue'] ?? 0),
                'tickets_printed' => (int)($result['tickets_printed'] ?? 0),
                'tickets_not_printed' => (int)($result['tickets_not_printed'] ?? 0),
                'tickets_this_year' => (int)($result['tickets_this_year'] ?? 0),
                'tickets_this_month' => (int)($result['tickets_this_month'] ?? 0),
                'tickets_today' => (int)($result['tickets_today'] ?? 0),
                'revenue_this_year' => (int)($result['revenue_this_year'] ?? 0),
                'revenue_this_month' => (int)($result['revenue_this_month'] ?? 0),
                'revenue_today' => (int)($result['revenue_today'] ?? 0)
            ];
            
        } catch (PDOException $e) {
            error_log("Error in getVeStatsByTime: " . $e->getMessage());
            return [
                'total_tickets' => 0,
                'total_revenue' => 0,
                'tickets_printed' => 0,
                'tickets_not_printed' => 0,
                'tickets_this_year' => 0,
                'tickets_this_month' => 0,
                'tickets_today' => 0,
                'revenue_this_year' => 0,
                'revenue_this_month' => 0,
                'revenue_today' => 0
            ];
        }
    }
    
    public function countVe($filters = [])
    {
        try {
            $sql = "SELECT COUNT(*) FROM ve v
                    LEFT JOIN nguoi_dung nd ON v.nd_id = nd.nd_id
                    LEFT JOIN lich_chieu lc ON v.lc_malichchieu = lc.lc_malichchieu
                    LEFT JOIN phim p ON lc.p_maphim = p.p_maphim
                    LEFT JOIN thanh_toan tt ON v.tt_mathanhtoan = tt.tt_mathanhtoan";
            
            $conditions = [];
            $params = [];
            
            if (!empty($filters['search'])) {
                $conditions[] = "(COALESCE(nd.nd_hoten, '') ILIKE ? OR COALESCE(p.p_tenphim, '') ILIKE ? OR v.v_mave ILIKE ?)";
                $searchParam = '%' . $filters['search'] . '%';
                $params[] = $searchParam;
                $params[] = $searchParam;
                $params[] = $searchParam;
            }
            
            if (!empty($filters['payment_method'])) {
                $conditions[] = "COALESCE(tt.tt_phuongthuc, 'Tiền mặt') = ?";
                $params[] = $filters['payment_method'];
            }
            
            if (!empty($filters['date'])) {
                $conditions[] = "v.v_ngaydat = ?";
                $params[] = $filters['date'];
            }
            
            if (!empty($filters['print_status'])) {
                $conditions[] = "v.v_trangthai = ?";
                $params[] = $filters['print_status'];
            }
            
            if (!empty($conditions)) {
                $sql .= " WHERE " . implode(" AND ", $conditions);
            }
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            
            return $stmt->fetchColumn();
            
        } catch (PDOException $e) {
            error_log("Error in countVe: " . $e->getMessage());
            return 0;
        }
    }
    
    public function getVeById($veMaVe)
    {
        try {
            $sql = "SELECT 
                        v.v_mave,
                        v.v_ngaydat,
                        v.v_tongtien,
                        v.v_trangthai,
                        COALESCE(nd.nd_hoten, 'Khách vãng lai') as nd_hoten,
                        COALESCE(nd.nd_email, '') as nd_email,
                        COALESCE(nd.nd_sdt, '') as nd_sdt,
                        COALESCE(p.p_tenphim, 'Phim demo') as p_tenphim,
                        lc.lc_ngaychieu,
                        lc.lc_giobatdau,
                        v.g_maghe,
                        COALESCE(pc.pc_tenphong, 'Phòng demo') as pc_tenphong,
                        COALESCE(lv.lv_tenloaive, 'Vé Standard') as lv_tenloaive,
                        COALESCE(tt.tt_phuongthuc, 'Tiền mặt') as tt_phuongthuc,
                        tt.tt_thoigianthanhtoan,
                        tt.tt_mathanhtoan
                    FROM ve v
                    LEFT JOIN nguoi_dung nd ON v.nd_id = nd.nd_id
                    LEFT JOIN lich_chieu lc ON v.lc_malichchieu = lc.lc_malichchieu
                    LEFT JOIN phim p ON lc.p_maphim = p.p_maphim
                    LEFT JOIN phong_chieu pc ON lc.pc_maphongchieu = pc.pc_maphongchieu
                    LEFT JOIN loai_ve lv ON v.lv_maloaive = lv.lv_maloaive
                    LEFT JOIN thanh_toan tt ON v.tt_mathanhtoan = tt.tt_mathanhtoan
                    WHERE v.v_mave = ?";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$veMaVe]);
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            error_log("Error in getVeById: " . $e->getMessage());
            return false;
        }
    }

    public function updatePrintStatus($veMaVe, $status = 'da_in')
    {
        try {
            $sql = "UPDATE ve SET v_trangthai = ? WHERE v_mave = ?";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$status, $veMaVe]);
        } catch (PDOException $e) {
            error_log("Error updating print status: " . $e->getMessage());
            return false;
        }
    }
    

}