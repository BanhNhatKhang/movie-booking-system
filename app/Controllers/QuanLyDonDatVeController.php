<?php

namespace App\Controllers;

use Jenssegers\Blade\Blade;

class QuanLyDonDatVeController
{
    private function checkAdminAuth()
    {
        // Kiểm tra đăng nhập
        if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
            $_SESSION['error_message'] = 'Vui lòng đăng nhập để truy cập trang admin!';
            header('Location: /dang-nhap');
            exit;
        }
        
        // Kiểm tra role admin
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            $_SESSION['error_message'] = 'Bạn không có quyền truy cập trang admin!';
            header('Location: /'); // Chuyển về trang chủ user
            exit;
        }
    }

    public function quanLyDonDatVe()
    {
        $this->checkAdminAuth();
        $blade = new Blade(
            realpath(__DIR__ . '/../Views'),
            realpath(__DIR__ . '/../../cache')
        );
        echo $blade->render('admin-views.QuanLyDonDatVe.QuanLyDonDatVe', ['activePage' => 'admin-orders']);
    }
    public function chiTietDonDatVe()
    {
        $this->checkAdminAuth();
        $blade = new Blade(
            realpath(__DIR__ . '/../Views'),
            realpath(__DIR__ . '/../../cache')
        );
        echo $blade->render('admin-views.QuanLyDonDatVe.ChiTietDon', ['activePage' => 'admin-orders']);
    }
    public function xuatve()
    {
        $this->checkAdminAuth();
        $orders = [
            [
                'id'=>1001,
                'user'=>'Nguyễn Văn B',
                'movie'=>'Thanh Gươm Diệt Quỷ',
                'showtime'=>'08:30 25/06/2024',
                'seats'=>'G09, G10',
                'price'=>170000,
                'date'=>'2024-06-20',
                'status'=>'paid'
            ],
            [
                'id'=>1002,
                'user'=>'Trần Thị C',
                'movie'=>'Hành Trình Về Miền Đất Hứa',
                'showtime'=>'10:00 26/06/2024',
                'seats'=>'A01',
                'price'=>70000,
                'date'=>'2024-06-21',
                'status'=>'unpaid'
            ],
            [
                'id'=>1003,
                'user'=>'Lê Văn D',
                'movie'=>'Ký Ức Mùa Hè',
                'showtime'=>'14:00 22/06/2024',
                'seats'=>'B05, B06, B07',
                'price'=>210000,
                'date'=>'2024-06-19',
                'status'=>'cancelled'
            ],
        ];

        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $order = null;
        foreach($orders as $o) {
            if($o['id'] == $id) {
                $order = $o;
                break;
            }
        }

        if(!$order) {
            die('Không tìm thấy đơn đặt vé!');
        }

        header("Content-type: application/vnd.ms-word");
        header("Content-Disposition: attachment;Filename=Ve_{$order['id']}.doc");

        $blade = new Blade(
            realpath(__DIR__ . '/../Views'),
            realpath(__DIR__ . '/../../cache')
        );

        echo $blade->render('admin-views.QuanLyDonDatVe.XuatVeWord', ['order' => $order]);
    }
}