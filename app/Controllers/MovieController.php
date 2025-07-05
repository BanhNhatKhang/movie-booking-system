<?php

namespace App\Controllers;
use App\Models\Phim;
use Jenssegers\Blade\Blade;
use Exception;

class MovieController
{
    private $phimModel;
    private $blade;

    public function __construct()
    {
        $this->phimModel = new Phim();
        $this->blade = new Blade(
            realpath(__DIR__ . '/../Views'),
            cachePath: realpath(__DIR__ . '/../../cache')
        );
    }

    /**
     * Hiển thị trang phim đang chiếu
     */
    public function phimDangChieu()
    {
        try {
            // Debug: Log method call
            error_log("MovieController@phimDangChieu called");
            
            // Lấy phim có trạng thái 'active' (đang chiếu)
            $phimList = $this->phimModel->getActivePhim();
            
            // Debug: Log số lượng phim
            error_log("Found " . count($phimList) . " active movies");
            
            // Format dữ liệu cho view
            $phimDangChieu = [];
            foreach ($phimList as $phim) {
                // ✅ Sửa cách format poster path
                $posterPath = '';
                if (!empty($phim['pt_anhposter'])) {
                    // Nếu đã có đường dẫn đầy đủ
                    if (strpos($phim['pt_anhposter'], '/static/') === 0) {
                        $posterPath = $phim['pt_anhposter'];
                    } else {
                        // Nếu chỉ có tên file, thêm đường dẫn đầy đủ
                        $posterPath = '/static/uploads/posters/' . $phim['pt_anhposter'];
                    }
                } else {
                    $posterPath = '/static/imgs/no-poster.jpg';
                }

                $phimDangChieu[] = [
                    'id' => $phim['p_maphim'],
                    'name' => $phim['p_tenphim'],
                    'genre' => $phim['p_theloai'],
                    'duration' => $phim['p_thoiluong'],
                    'release' => $phim['p_phathanh'],
                    'desc' => $phim['p_mota'],
                    'trailer' => $phim['p_trailer'],
                    'poster' => $posterPath, // ✅ Sử dụng đường dẫn đã format
                    'status' => $phim['p_trangthai'],
                    'director' => $phim['p_daodien'],
                    'actors' => $phim['p_dienvien']
                ];
            }

            // Debug: Log formatted data
            error_log("Formatted movies: " . json_encode($phimDangChieu));

            echo $this->blade->render('users-views.Phim.PhimDangChieu', [
                'phimDangChieu' => $phimDangChieu,
                'activePage' => 'phim-dang-chieu'
            ]);
        } catch (Exception $e) {
            error_log("Error in phimDangChieu: " . $e->getMessage());
            echo $this->blade->render('users-views.Phim.PhimDangChieu', [
                'phimDangChieu' => [],
                'activePage' => 'phim-dang-chieu',
                'error' => 'Không thể tải danh sách phim'
            ]);
        }
    }

    /**
     * Hiển thị trang phim sắp chiếu
     */
    public function phimSapChieu()
    {
        try {
            // Lấy phim có trạng thái 'coming_soon' (sắp chiếu)
            $phimList = $this->phimModel->getComingSoonPhim();
            
            // Format dữ liệu cho view
            $phimSapChieu = [];
            foreach ($phimList as $phim) {
                $phimSapChieu[] = [
                    'id' => $phim['p_maphim'],
                    'name' => $phim['p_tenphim'],
                    'genre' => $phim['p_theloai'],
                    'duration' => $phim['p_thoiluong'],
                    'release' => $phim['p_phathanh'],
                    'desc' => $phim['p_mota'],
                    'trailer' => $phim['p_trailer'],
                    'poster' => $phim['pt_anhposter'] ?? '/static/imgs/no-poster.jpg',
                    'status' => $phim['p_trangthai'],
                    'director' => $phim['p_daodien'],
                    'actors' => $phim['p_dienvien']
                ];
            }

            echo $this->blade->render('users-views.Phim.PhimSapChieu', [
                'phimSapChieu' => $phimSapChieu,
                'activePage' => 'phim-sap-chieu'
            ]);
        } catch (Exception $e) {
            error_log("Error in phimSapChieu: " . $e->getMessage());
            echo $this->blade->render('users-views.Phim.PhimSapChieu', [
                'phimSapChieu' => [],
                'activePage' => 'phim-sap-chieu',
                'error' => 'Không thể tải danh sách phim'
            ]);
        }
    }

    /**
     * Hiển thị chi tiết phim
     */
    public function chiTietPhim()
    {
        try {
            $id = $_GET['id'] ?? '';
            if (empty($id)) {
                header('Location: /phim-dang-chieu');
                exit;
            }

            $phim = $this->phimModel->getPhimById($id);
            if (!$phim) {
                header('Location: /phim-dang-chieu?error=not_found');
                exit;
            }

            // Format dữ liệu cho view
            $phimDetail = [
                'id' => $phim['p_maphim'],
                'name' => $phim['p_tenphim'],
                'genre' => $phim['p_theloai'],
                'duration' => $phim['p_thoiluong'],
                'release' => $phim['p_phathanh'],
                'desc' => $phim['p_mota'],
                'trailer' => $phim['p_trailer'],
                'poster' => $phim['pt_anhposter'] ?? '/static/imgs/no-poster.jpg',
                'status' => $phim['p_trangthai'],
                'director' => $phim['p_daodien'],
                'actors' => $phim['p_dienvien']
            ];

            echo $this->blade->render('users-views.Phim.ChiTietPhim', [
                'phim' => $phimDetail,
                'activePage' => 'movies'
            ]);
        } catch (Exception $e) {
            error_log("Error in chiTietPhim: " . $e->getMessage());
            header('Location: /phim-dang-chieu?error=system_error');
            exit;
        }
    }

    /**
     * Hiển thị trang chọn ghế
     */
    public function chonGhe()
    {
        echo $this->blade->render('users-views.Phim.ChonGhe', [
            'activePage' => 'movies'
        ]);
    }
}