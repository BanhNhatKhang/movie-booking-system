<?php

namespace App\Controllers;
use App\Helpers\AuthHelper;

use Jenssegers\Blade\Blade;

class DashboardController
{

    public function dashboard()
    {
        AuthHelper::checkAccess('admin_only');

        // Lấy số liệu thực tế
        $veModel = new \App\Models\Ve();
        $nguoiDungModel = new \App\Models\NguoiDung();
        $phimModel = new \App\Models\Phim();

        $month = date('m');
        $year = date('Y');

        $totalTickets = $veModel->countAllTickets(); // Tổng vé đã bán
        $monthlyRevenue = $veModel->getMonthlyRevenue($month, $year); // Doanh thu tháng này
        $totalUsers = $nguoiDungModel->countAllUsers(); // Tổng người dùng

        // Thống kê vé theo phim (cho biểu đồ)
        $ticketsByMovie = $veModel->getTicketsByMovieInMonth($month, $year); // Trả về mảng ['Endgame'=>2430, 'Spider-Man'=>1980, ...]

        // Thống kê doanh thu theo tháng (cho biểu đồ)
        $revenueByMonth = $veModel->getRevenueByMonth($year); // Trả về mảng ['T1'=>320, 'T2'=>420, ...]

        $blade = new Blade(
            realpath(__DIR__ . '/../Views'),
            realpath(__DIR__ . '/../../cache')
        );
        echo $blade->render('admin-views.Dashboard.index', [
            'activePage' => 'dashboard',
            'totalTickets' => $totalTickets,
            'monthlyRevenue' => $monthlyRevenue,
            'totalUsers' => $totalUsers,
            'ticketsByMovie' => $ticketsByMovie,
            'revenueByMonth' => $revenueByMonth,
            'month' => $month,
            'year' => $year,
        ]);
    }

}