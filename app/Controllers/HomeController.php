<?php

namespace App\Controllers;

use App\Models\UuDaiTrangChu;
use App\Models\Phim;
use Jenssegers\Blade\Blade;
use Exception;

class HomeController
{
    private $uuDaiModel;
    private $phimModel;
    private $blade;

    public function __construct()
    {
        $this->uuDaiModel = new UuDaiTrangChu();
        $this->phimModel = new Phim();
        $this->blade = new Blade(
            realpath(__DIR__ . '/../Views'),
            realpath(__DIR__ . '/../../cache')
        );
    }

    /**
     * Hiển thị trang chủ
     */
    public function index()
    {
        try {
            // Lấy danh sách ưu đãi từ database
            $uuDaiList = $this->uuDaiModel->getAllUuDai();
            
            // DEBUG: Thêm dòng này để kiểm tra
            error_log("UuDai count: " . count($uuDaiList));
            error_log("UuDai data: " . print_r($uuDaiList, true));
            
            // Lấy phim đang chiếu và sắp chiếu từ database
            $phimDangChieu = $this->phimModel->getActivePhim();
            $phimSapChieu = $this->phimModel->getComingSoonPhim();
            
            echo $this->blade->render('index', [
                'uuDaiList' => $uuDaiList,
                'phimDangChieu' => $phimDangChieu,
                'phimSapChieu' => $phimSapChieu
            ]);
        } catch (Exception $e) {
            error_log("Error in HomeController index: " . $e->getMessage());
            
            // Fallback nếu có lỗi
            echo $this->blade->render('index', [
                'uuDaiList' => [],
                'phimDangChieu' => [],
                'phimSapChieu' => [],
                'error' => 'Không thể tải dữ liệu'
            ]);
        }
    }
}