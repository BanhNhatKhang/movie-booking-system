<?php
session_start();
if (isset($_SESSION['user'])) {
    include __DIR__ . '/../../../layouts/users/HeaderLogin.php';
} else {
    include __DIR__ . '/../../../layouts/users/Header.php';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thành viên KHF</title>
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css"
    />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <link rel="stylesheet" href="/static/css/users/HeaderFooter.css">
    <link rel="stylesheet" href="/static/css/users/ThanhVien.css">
</head>
<body style="background-color: rgb(40, 40, 40)">
    <main >
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="row bg-body m-0">
                        <div class="col-lg-3">
                            <ul class="py-3 px-2 m-0">
                                <div class="p-2 mb-3 rounded-start-pill shadow-sm" style="background-color: #ff4444;">
                                    <li class="list-unstyled "><a href="#" class="text-decoration-none text-white fw-bold">THÀNH VIÊN KHF CINEMA</a></li>
                                </div>
                                <div class="bg-secondary-subtle p-2 mb-3 rounded-start-pill shadow-sm member-hover">
                                    <li class="list-unstyled"><a href="DiemThuong.php" class="text-decoration-none text-dark fw-bold">ĐIỂM THƯỞNG TÍCH LŨY</a></li>
                                </div>
                                <div class="bg-secondary-subtle p-2 mb-3 rounded-start-pill shadow-sm  member-hover">
                                    <li class="list-unstyled"><a href="CapDo.php" class="text-decoration-none text-dark fw-bold">CẤP ĐỘ THÀNH VIÊN</a></li>                
                                </div>
                                <div class="bg-secondary-subtle p-2 mb-3 rounded-start-pill shadow-sm  member-hover">
                                    <li class="list-unstyled"><a href="QuaTang.php" class="text-decoration-none text-dark fw-bold">QUÀ TẶNG SINH NHẬT</a></li>
                                </div>
                            </ul>
                        </div>
                        <div class="col-lg-9">
                            <div class="p-3">
                                <p class="fw-bold fs-4">Chào mừng bạn đến với KHF Cinema Membership – chương trình thành viên đặc biệt dành cho những tín đồ điện ảnh tại hệ thống rạp KHF Cinema!</p>
                                <hr>
                                <p class="fw-bold">1. Hướng dẫn đăng ký thành viên KHF Cinema Membership</p>
                                <p>Tham gia KHF Cinema Membership thật dễ dàng! Bạn có thể đăng ký trực tuyến ngay trên website chính thức của chúng tôi tại www.khfcinema.vn hoặc đến trực tiếp các rạp KHF Cinema để được hỗ trợ đăng ký nhanh chóng.</p>

                                <p class="fw-bold">2. Các hạng thành viên tại KHF Cinema Membership</p>
                                <p>Chúng tôi có 3 hạng thành viên: Thành viên thường (Member), Thành viên Vàng (VIP) và Thành viên Kim Cương (VVIP), mỗi hạng đều được nhận những phần quà và ưu đãi riêng biệt, hấp dẫn.</p>
                                <p class="fw-bold">3. Ưu đãi hấp dẫn khi trở thành thành viên KHF Cinema Membership</p>
                                <p>
                                Khi tham gia chương trình, bạn sẽ được hưởng hàng loạt đặc quyền:
                                <ul>
                                    <li>Tích điểm mua vé và bắp nước nhanh chóng, dễ dàng.</li>
                                    <li>Đổi điểm lấy vé xem phim, combo bắp nước hoặc các món quà độc quyền chỉ dành riêng cho thành viên KHF Cinema.</li>
                                    <li>Nhận quà tặng sinh nhật đầy ý nghĩa từ KHF Cinema.</li>
                                    <li>Tham gia các chương trình khuyến mãi và sự kiện riêng biệt, dành riêng cho hội viên của chúng tôi.</p></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>

</body>
</html>
    
    <?php
        include __DIR__ . '/../../../layouts/users/Footer.php';
    ?>