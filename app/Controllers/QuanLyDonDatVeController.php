<?php

namespace App\Controllers;

use Jenssegers\Blade\Blade;
use App\Models\Ve;
use App\Helpers\AuthHelper;
use Exception;

class QuanLyDonDatVeController
{
    private $veModel;
    private $blade;

    public function __construct()
    {
        $this->veModel = new Ve();
        $this->blade = new Blade(
            realpath(__DIR__ . '/../Views'),
            realpath(__DIR__ . '/../../cache')
        );
    }

    public function quanLyDonDatVe()
    {
        AuthHelper::checkAccess('admin_only');
        
        try {
            $search = $_GET['search'] ?? '';
            $paymentMethod = $_GET['payment_method'] ?? '';
            $date = $_GET['date'] ?? '';
            $printStatus = $_GET['print_status'] ?? ''; // Thêm filter trạng thái in
            $page = max(1, (int)($_GET['page'] ?? 1));
            $limit = 10;
            $offset = ($page - 1) * $limit;
            
            $filters = [
                'search' => $search,
                'payment_method' => $paymentMethod,
                'date' => $date,
                'print_status' => $printStatus
            ];
            
            $stats = $this->veModel->getVeStatsByTime();
            $orders = $this->veModel->getAllVeWithDetails($limit, $offset, $filters);
            $totalOrders = $this->veModel->countVe($filters);
            $totalPages = max(1, ceil($totalOrders / $limit));
            
            echo $this->blade->render('admin-views.QuanLyDonDatVe.QuanLyDonDatVe', [
                'activePage' => 'admin-orders',
                'orders' => $orders,
                'currentPage' => $page,
                'totalPages' => $totalPages,
                'totalOrders' => $totalOrders,
                'filters' => $filters,
                'stats' => $stats
            ]);
            
        } catch (Exception $e) {
            error_log("Error in quanLyDonDatVe: " . $e->getMessage());
            
            echo $this->blade->render('admin-views.QuanLyDonDatVe.QuanLyDonDatVe', [
                'activePage' => 'admin-orders',
                'orders' => [],
                'currentPage' => 1,
                'totalPages' => 1,
                'totalOrders' => 0,
                'filters' => [],
                'stats' => []
            ]);
        }
    }

    public function chiTietDonDatVe()
    {
        AuthHelper::checkAccess('admin_only');
        
        try {
            $veMaVe = $_GET['id'] ?? '';
            
            if (empty($veMaVe)) {
                $_SESSION['error_message'] = 'Mã vé không hợp lệ';
                header('Location: /quan-ly-don-dat-ve');
                exit;
            }
            
            $order = $this->veModel->getVeById($veMaVe);
            
            echo $this->blade->render('admin-views.QuanLyDonDatVe.ChiTietDon', [
                'activePage' => 'admin-orders',
                'order' => $order
            ]);
            
        } catch (Exception $e) {
            error_log("Error in chiTietDonDatVe: " . $e->getMessage());
            $_SESSION['error_message'] = 'Có lỗi xảy ra: ' . $e->getMessage();
            header('Location: /quan-ly-don-dat-ve');
            exit;
        }
    }

    public function xuatVeWord()
    {
        AuthHelper::checkAccess('admin_only');
        
        try {
            $veMaVe = $_GET['id'] ?? '';
            
            if (empty($veMaVe)) {
                $_SESSION['error_message'] = 'Mã vé không hợp lệ';
                header('Location: /quan-ly-don-dat-ve');
                exit;
            }
            
            $order = $this->veModel->getVeById($veMaVe);
            
            if (!$order) {
                $_SESSION['error_message'] = 'Không tìm thấy vé';
                header('Location: /quan-ly-don-dat-ve');
                exit;
            }
            
            // CHỈ HIỂN THỊ - KHÔNG TỰ ĐỘNG CẬP NHẬT
            echo $this->blade->render('admin-views.QuanLyDonDatVe.XuatVeWord', [
                'order' => $order
            ]);
            
        } catch (Exception $e) {
            error_log("Error in xuatVeWord: " . $e->getMessage());
            $_SESSION['error_message'] = 'Có lỗi xảy ra khi xuất vé';
            header('Location: /quan-ly-don-dat-ve');
            exit;
        }
    }
    
    public function processVePrint()
    {
        AuthHelper::checkAccess('admin_only');
        
        try {
            $veMaVe = $_POST['ve_id'] ?? '';
            
            if (empty($veMaVe)) {
                $_SESSION['error_message'] = 'Mã vé không hợp lệ';
                header('Location: /quan-ly-don-dat-ve');
                exit;
            }
            
            $order = $this->veModel->getVeById($veMaVe);
            
            if (!$order) {
                $_SESSION['error_message'] = 'Không tìm thấy vé';
                header('Location: /quan-ly-don-dat-ve');
                exit;
            }
            
            // CẬP NHẬT trạng thái in vé
            $currentStatus = $order['v_trangthai'] ?? 'chua_in';
            $printSuccess = false;
            
            if ($currentStatus == 'chua_in') {
                $updateResult = $this->veModel->updatePrintStatus($veMaVe, 'da_in');
                if ($updateResult) {
                    $printSuccess = true;
                    $order['v_trangthai'] = 'da_in';
                }
            }
            
            // Hiển thị lại trang với thông báo
            echo $this->blade->render('admin-views.QuanLyDonDatVe.XuatVeWord', [
                'order' => $order,
                'print_success' => $printSuccess
            ]);
            
        } catch (Exception $e) {
            error_log("Error in processVePrint: " . $e->getMessage());
            $_SESSION['error_message'] = 'Có lỗi xảy ra khi in vé';
            header('Location: /quan-ly-don-dat-ve');
            exit;
        }
    }
}