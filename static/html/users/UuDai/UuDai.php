<?php
    $activePage = 'offers';
    include __DIR__ . '/../../../layouts/users/Header.php';
?>
<!DOCTYPE html>
<html lang="vi">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Ưu đãi | KHF Cinema</title>
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css"
    />
    <link rel="stylesheet" href="/static/css/users/Header-Footer.css" />
    <link rel="stylesheet" href="/static/css/users/UuDai.css" />
    
  </head>
  <body style="background-color: rgb(40, 40, 40);">
    <main>
        <div class="container">
            <div class="row">
                <div class="col">
                    <section class="offers-banner">
                        <h1>KHUYẾN MÃI ĐẶC BIỆT</h1>
                        <p>
                            Khám phá các ưu đãi hấp dẫn dành riêng cho bạn tại KHF Cinema. Tiết kiệm
                            nhiều hơn với mỗi lần đặt vé!
                        </p>
                    </section>
                    <section class="mb-5">
                        <h2 class="section-title">ƯU ĐÃI NỔI BẬT</h2>
                        <div class="featured-offer">
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                <div class="featured-content">
                                    <h3>COMBO GIA ĐÌNH SIÊU TIẾT KIỆM</h3>
                                    <p>
                                    Combo 4 vé + 2 bắp lớn + 4 nước chỉ 399.000đ. Áp dụng cho tất
                                    cả các suất chiếu trong tuần. Hạn sử dụng đến 31/12/2024.
                                    </p>
                                    <button class="featured-btn">SỬ DỤNG NGAY</button>
                                </div>
                                </div>
                                <div class="col-md-6">
                                <img src="" alt="Combo gia đình" class="img-fluid rounded" />
                                </div>
                            </div>
                        </div>

                        <div class="featured-offer">
                            <div class="row align-items-center">
                                <div class="col-md-6 order-md-2">
                                <div class="featured-content">
                                    <h3>MIỄN PHÍ VÉ THỨ 3</h3>
                                    <p>
                                    Khi mua 2 vé bất kỳ, nhận ngay vé thứ 3 miễn phí. Chương trình
                                    áp dụng vào thứ 4 hàng tuần tại tất cả các rạp KHF Cinema trên
                                    toàn quốc.
                                    </p>
                                    <button class="featured-btn">SỬ DỤNG NGAY</button>
                                </div>
                                </div>
                                <div class="col-md-6 order-md-1">
                                <img
                                    src="https://images.unsplash.com/photo-1616530940355-351fabd952eb?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80"
                                    alt="Miễn phí vé"
                                    class="img-fluid rounded"
                                />
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- All Offers Section -->
                    <section class="offers-section">
                        <div class="row p-3">
                            <div class="col-md-12">
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <h2 class="section-title">TẤT CẢ ƯU ĐÃI</h2>
                                    <div class="d-flex flex-wrap">
                                        <button class="btn filter-btn active" data-filter="all">
                                        Tất cả
                                        </button>
                                        <button class="btn filter-btn" data-filter="ongoing">
                                        Đang diễn ra
                                        </button>
                                        <button class="btn filter-btn" data-filter="upcoming">
                                        Sắp diễn ra
                                        </button>
                                        <button class="btn filter-btn" data-filter="expired">
                                        Đã kết thúc
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                        <!-- Offer 1: Đang diễn ra -->
                        <div class="col-lg-4 col-md-6 offer-item" data-status="ongoing">
                            <div class="offer-card">
                                <div class="offer-img">
                                    <img src="" alt="Combo bắp nước" />
                                </div>
                                <div class="offer-content">
                                    <span class="offer-badge">COMBO</span>
                                    <h4 class="offer-title">Combo bắp nước chỉ 69k</h4>
                                    <p class="offer-desc">
                                    Combo 1 bắp lớn + 2 nước lớn chỉ với 69.000đ. Áp dụng cho tất
                                    cả các suất chiếu và rạp trên toàn quốc.
                                    </p>
                                    <div class="offer-details">
                                    <span class="offer-expiry">HSD: 30/11/2024</span>
                                    <a href="#" class="offer-btn">Chi tiết</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Offer 2: Đang diễn ra -->
                        <div class="col-lg-4 col-md-6 offer-item" data-status="ongoing">
                            <div class="offer-card">
                                <div class="offer-img">
                                    <img src="" alt="Giảm giá" />
                                </div>
                                <div class="offer-content">
                                    <span class="offer-badge">GIẢM GIÁ</span>
                                    <h4 class="offer-title">Giảm 50% cho vé thứ 2</h4>
                                    <p class="offer-desc">
                                    Mua 1 tặng 1 cho tất cả các suất chiếu vào thứ 3 hàng tuần. Áp
                                    dụng cho tất cả các rạp trên toàn quốc.
                                    </p>
                                    <div class="offer-details">
                                    <span class="offer-expiry">HSD: 31/12/2024</span>
                                    <a href="#" class="offer-btn">Chi tiết</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Offer 3: Sắp diễn ra -->
                        <div class="col-lg-4 col-md-6 offer-item" data-status="upcoming">
                            <div class="offer-card">
                                <div class="offer-img">
                                    <img src="" alt="Thẻ thành viên" />
                                </div>
                                <div class="offer-content">
                                    <span class="offer-badge">THÀNH VIÊN</span>
                                    <h4 class="offer-title">Tích điểm 2x cho thành viên VIP</h4>
                                    <p class="offer-desc">
                                    Tích điểm gấp đôi cho tất cả các giao dịch đặt vé qua ứng dụng
                                    KHF Cinema. Áp dụng từ 01/06 đến 31/12/2024.
                                    </p>
                                    <div class="offer-details">
                                    <span class="offer-expiry">HSD: 31/12/2024</span>
                                    <a href="#" class="offer-btn">Chi tiết</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Offer 4: Sắp diễn ra -->
                        <div class="col-lg-4 col-md-6 offer-item" data-status="upcoming">
                            <div class="offer-card">
                                <div class="offer-img">
                                    <img src="" alt="Sinh nhật" />
                                </div>
                                <div class="offer-content">
                                    <span class="offer-badge">SINH NHẬT</span>
                                    <h4 class="offer-title">Ưu đãi sinh nhật đặc biệt</h4>
                                    <p class="offer-desc">
                                    Nhận ngay 1 vé xem phim miễn phí trong tháng sinh nhật của
                                    bạn. Áp dụng cho thành viên từ level 2 trở lên.
                                    </p>
                                    <div class="offer-details">
                                        <span class="offer-expiry">HSD: 31/12/2024</span>
                                        <a href="#" class="offer-btn">Chi tiết</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Offer 5: Đang diễn ra -->
                        <div class="col-lg-4 col-md-6 offer-item" data-status="ongoing">
                            <div class="offer-card">
                                <div class="offer-img">
                                    <img src="" alt="Đặt vé sớm" />
                                </div>
                                <div class="offer-content">
                                    <span class="offer-badge">SỚM</span>
                                    <h4 class="offer-title">Giảm 20% khi đặt vé trước</h4>
                                    <p class="offer-desc">
                                    Giảm 20% khi đặt vé trước 3 ngày cho các phim mới. Áp dụng cho
                                    tất cả các rạp trên toàn quốc.
                                    </p>
                                    <div class="offer-details">
                                        <span class="offer-expiry">HSD: 31/12/2024</span>
                                        <a href="#" class="offer-btn">Chi tiết</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Offer 6: Đã kết thúc -->
                        <div class="col-lg-4 col-md-6 offer-item" data-status="expired">
                            <div class="offer-card">
                                <div class="offer-img">
                                    <img src="" alt="Ngân hàng" />
                                </div>
                                <div class="offer-content">
                                    <span class="offer-badge">NGÂN HÀNG</span>
                                    <h4 class="offer-title">Giảm 30% với thẻ ngân hàng</h4>
                                    <p class="offer-desc">
                                    Giảm 30% khi thanh toán bằng thẻ ngân hàng đối tác. Áp dụng
                                    cho tất cả các suất chiếu và rạp trên toàn quốc.
                                    </p>
                                    <div class="offer-details">
                                        <span class="offer-expiry">HSD: 15/05/2024</span>
                                        <a href="#" class="offer-btn">Chi tiết</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>   
        </div>
    </main>
    <?php
        include __DIR__ . '/../../../layouts/users/Footer.php';
    ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
      // Lọc ưu đãi theo trạng thái
      document.addEventListener("DOMContentLoaded", function () {
        const filterButtons = document.querySelectorAll(".filter-btn");
        const offerItems = document.querySelectorAll(".offer-item");

        // sự kiện click cho các nút lọc
        filterButtons.forEach((button) => {
          button.addEventListener("click", function () {
            const filter = this.getAttribute("data-filter");

            // Cập nhật trạng thái active
            filterButtons.forEach((btn) => btn.classList.remove("active"));
            this.classList.add("active");

            // Lọc các ưu đãi
            offerItems.forEach((item) => {
              const status = item.getAttribute("data-status");

              if (filter === "all") {
                item.style.display = "block";
              } else if (filter === status) {
                item.style.display = "block";
              } else {
                item.style.display = "none";
              }
            });
          });
        });

        // Hiệu ứng chuyển trang
        document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
          anchor.addEventListener("click", function (e) {
            e.preventDefault();
            document.querySelector(this.getAttribute("href")).scrollIntoView({
              behavior: "smooth",
            });
          });
        });
      });
    </script>
  </body>
</html>
