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

            $uuDaiModel = new \App\Models\UuDaiTrangChu();
            $uuDaiList = $uuDaiModel->getAllUuDai();
            
            $phimModel = new \App\Models\Phim();
            $phimDangChieu = $phimModel->getPhimByStatus('active');

            $phimSapChieu = $phimModel->getPhimByStatus('coming_soon');
            
            $posterModel = new \App\Models\Poster();
            $posterList = $posterModel->getAll(); // Lấy tất cả poster
            
            echo $this->blade->render('index', [
                'activePage' => 'home',
                'uuDaiList' => $uuDaiList,
                'phimDangChieu' => $phimDangChieu,
                'phimSapChieu' => $phimSapChieu,
                'posterList' => $posterList,
            ]);
            
        } catch (Exception $e) {
            error_log("Error in HomeController: " . $e->getMessage());
            
            // ✅ Fallback với mảng rỗng nếu lỗi
            echo $this->blade->render('index', [
                'activePage' => 'home',
                'uuDaiList' => [],
                'phimDangChieu' => [],
                'phimSapChieu' => []
            ]);
        }
    }
}