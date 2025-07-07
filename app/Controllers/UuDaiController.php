<?php

namespace App\Controllers;

use App\Models\UuDai;
use Jenssegers\Blade\Blade;
use Exception;

class UuDaiController
{
    private $uuDaiModel;
    private $blade;

    public function __construct()
    {
        $this->uuDaiModel = new UuDai();
        $this->blade = new Blade(
            realpath(__DIR__ . '/../Views'),
            realpath(__DIR__ . '/../../cache')
        );
    }

    public function uudai()
    {
        try {
            // ✅ DEBUG: Thêm log
            error_log("=== UU DAI CONTROLLER DEBUG ===");
            
            // Cập nhật trạng thái ưu đãi theo thời gian
            $this->uuDaiModel->updateStatusByDate();

            // Lấy tất cả ưu đãi từ database
            $allOffers = $this->uuDaiModel->getAllUuDai();
            
            // ✅ DEBUG: Kiểm tra dữ liệu
            error_log("All offers count: " . count($allOffers));
            error_log("All offers data: " . print_r($allOffers, true));

            // Phân loại ưu đãi theo trạng thái
            $ongoingOffers = [];
            $upcomingOffers = [];
            $expiredOffers = [];

            foreach ($allOffers as $offer) {
                error_log("Processing offer: " . $offer['ud_tenuudai'] . " - Status: " . $offer['ud_trangthai']);
                
                switch ($offer['ud_trangthai']) {
                    case 'Đang diễn ra':
                        $ongoingOffers[] = $offer;
                        break;
                    case 'Sắp diễn ra':
                        $upcomingOffers[] = $offer;
                        break;
                    case 'Kết thúc':
                        $expiredOffers[] = $offer;
                        break;
                }
            }
            
            error_log("Ongoing: " . count($ongoingOffers));
            error_log("Upcoming: " . count($upcomingOffers));
            error_log("Expired: " . count($expiredOffers));

            echo $this->blade->render('users-views.UuDai.UuDai', [
                'activePage' => 'uudai',
                'allOffers' => $allOffers,
                'ongoingOffers' => $ongoingOffers,
                'upcomingOffers' => $upcomingOffers,
                'expiredOffers' => $expiredOffers
            ]);

        } catch (Exception $e) {
            error_log("Error in UuDaiController uudai: " . $e->getMessage());
            error_log("Error trace: " . $e->getTraceAsString());
            
            // Fallback với dữ liệu trống
            echo $this->blade->render('users-views.UuDai.UuDai', [
                'activePage' => 'uudai',
                'allOffers' => [],
                'ongoingOffers' => [],
                'upcomingOffers' => [],
                'expiredOffers' => [],
                'error' => 'Lỗi tải dữ liệu: ' . $e->getMessage()
            ]);
        }
    }
}