<?php

namespace App\Controllers;

use Jenssegers\Blade\Blade;
use App\Models\ThanhToan;
use App\Models\NguoiDung;
use App\Models\Ve;
use App\Helpers\AuthHelper;
class QuanLyThanhToanController
{


    public function quanLyThanhToan()
    {
        $search = $_GET['search'] ?? '';
        $paymentMethod = $_GET['payment_method'] ?? '';
        $today = date('Y-m-d');

        $thanhToanModel = new \App\Models\ThanhToan();

        // Lấy danh sách có lọc
        $page = max(1, intval($_GET['page'] ?? 1));
        $itemsPerPage = 10;
        $offset = ($page - 1) * $itemsPerPage;
        $list = $thanhToanModel->getAllThanhToanFiltered($search, $paymentMethod, $itemsPerPage, $offset);

        // Thống kê tổng
        $stats = [
            'total' => $thanhToanModel->countThanhToan($search, $paymentMethod),
            'total_amount' => $thanhToanModel->sumThanhToan($search, $paymentMethod),
            // Thống kê trong ngày
            'total_today' => $thanhToanModel->countThanhToan($search, $paymentMethod, $today),
            'total_amount_today' => $thanhToanModel->sumThanhToan($search, $paymentMethod, $today),
        ];
        $month = date('Y-m');
        $stats['total_amount_month'] = $thanhToanModel->sumThanhToanByMonth($month);
        $stats['total_month'] = $thanhToanModel->countThanhToanByMonth($month);

        $totalItems = $thanhToanModel->countThanhToan($search, $paymentMethod);
        $totalPages = ceil($totalItems / $itemsPerPage);

        $blade = new \Jenssegers\Blade\Blade(
            realpath(__DIR__ . '/../Views'),
            realpath(__DIR__ . '/../../cache')
        );
        echo $blade->render('admin-views.ThanhToan.QuanLyThanhToan', [
            'list' => $list,
            'stats' => $stats,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalItems' => $totalItems,
            'itemsPerPage' => $itemsPerPage,
            'itemName' => 'giao dịch',
            'activePage' => 'pay',
        ]);
    }

    public function chiTietThanhToan()
    {
        $maGD = $_GET['id'] ?? '';
        $thanhToanModel = new \App\Models\ThanhToan();
        $veModel = new \App\Models\Ve();
        $nguoiDungModel = new \App\Models\NguoiDung();

        $thanhToan = $thanhToanModel->getThanhToanById($maGD);
        $user = $nguoiDungModel->getById($thanhToan['nd_id']);
        $veList = $veModel->findByMaThanhToan($maGD);

        $blade = new \Jenssegers\Blade\Blade(
            realpath(__DIR__ . '/../Views'),
            realpath(__DIR__ . '/../../cache')
        );
        echo $blade->render('admin-views.ThanhToan.ChiTietThanhToan', [
            'thanhToan' => $thanhToan,
            'user' => $user,
            'veList' => $veList,
            'activePage' => 'pay',
        ]);
    }
}