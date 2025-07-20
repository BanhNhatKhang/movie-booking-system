<?php
namespace App\Controllers;

use Jenssegers\Blade\Blade;
use Exception;

class HomeController
{
    private $blade;
    
    public function __construct()
    {

        $viewsPath = __DIR__ . '/../Views';
        $cachePath = __DIR__ . '/../../cache';
        
        // Tạo thư mục cache nếu chưa tồn tại
        if (!is_dir($cachePath)) {
            mkdir($cachePath, 0755, true);
        }
        
        $this->blade = new Blade($viewsPath, $cachePath);
    }
    
    /**
     * Hiển thị trang chủ
     */
    public function index()
    {
        try {
            error_log('Bắt đầu lấy ưu đãi');
            $uuDaiModel = new \App\Models\UuDaiTrangChu();
            $uuDaiList = $uuDaiModel->getAllUuDai();
            error_log('Lấy ưu đãi xong');
            
            error_log('Bắt đầu lấy phim đang chiếu');
            $phimModel = new \App\Models\Phim();
            $phimDangChieuRaw = $phimModel->getPhimByStatus('active');
            $phimSapChieuRaw = $phimModel->getPhimByStatus('coming_soon');
            error_log('Lấy phim đang chiếu xong');
            
            // Thêm slug cho phim đang chiếu
            $phimDangChieu = [];
            foreach ($phimDangChieuRaw as $phim) {
                $phim['slug'] = $phimModel->createSlug($phim['p_tenphim']);
                $phimDangChieu[] = $phim;
            }
            
            // Thêm slug cho phim sắp chiếu
            $phimSapChieu = [];
            foreach ($phimSapChieuRaw as $phim) {
                $phim['slug'] = $phimModel->createSlug($phim['p_tenphim']);
                $phimSapChieu[] = $phim;
            }
            
            error_log('Bắt đầu lấy poster');
            $posterModel = new \App\Models\Poster();
            $posterList = $posterModel->getAll();
            error_log('Lấy poster xong');
            
            echo $this->blade->render('index', [
                'activePage' => 'home',
                'uuDaiList' => $uuDaiList,
                'phimDangChieu' => $phimDangChieu,
                'phimSapChieu' => $phimSapChieu,
                'posterList' => $posterList,
            ]);
            
        } catch (Exception $e) {
            error_log("Error in HomeController: " . $e->getMessage());
            
            echo $this->blade->render('index', [
                'activePage' => 'home',
                'uuDaiList' => [],
                'phimDangChieu' => [],
                'phimSapChieu' => []
            ]);
        }
    }
}