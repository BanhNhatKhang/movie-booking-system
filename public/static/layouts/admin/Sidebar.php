<div class="collapse d-lg-block sidebar" id="mobileSidebar">
    <div class="khf-logo">
        <div class="logo-icon"></div>
        <div class="logo-text">
            <span class="cinema">CINEMA</span>
            <span class="khf">KHF</span>
        </div>
    </div>
    <br>
    <a class="nav-link <?= ($activePage == 'dashboard') ? 'active' : '' ?>" href="/public/static/html/admin/Dashboard/index.php"><i class="bi bi-speedometer2"></i> Dashboard</a>
    <a class="nav-link <?= ($activePage == 'home') ? 'active' : '' ?>" href="/public/static/html/admin/TrangChu/QuanLyTrangChu.php"><i class="bi bi-house-door"></i> Quản lý trang chủ</a>
    <a href="#"><i class="bi bi-film"></i> Quản lý phim</a>
    <a class="nav-link <?= ($activePage == 'schedule') ? 'active' : '' ?>" href="/public/static/html/admin/LichChieu/QuanLyLichChieu.php"><i class="bi bi-calendar-event"></i> Quản lý lịch chiếu</a>
    <a href="#"><i class="bi bi-door-closed"></i> Phòng & Ghế</a>
    <a href="#"><i class="bi bi-ticket-detailed"></i> Quản lý đơn đặt vé</a>
    <a class="nav-link <?= ($activePage == 'book-ticket') ? 'active' : '' ?>" href="/public/static/html/admin/DatVeTaiQuay/DatVeTaiQuay.php"><i class="bi bi-ticket-perforated"></i> Đặt vé tại quầy</a>
    <a class="nav-link <?= ($activePage == 'uudai') ? 'active' : '' ?>" href="/public/static/html/admin/UuDai/QuanLyUuDai.php"><i class="bi bi-gift"></i> Ưu đãi</a>
    <a href="#"><i class="bi bi-person"></i> Người dùng</a>
    <a class="nav-link <?= ($activePage == 'pay') ? 'active' : '' ?>" href="/public/static/html/admin/ThanhToan/QuanLyThanhToan.php"><i class="bi bi-credit-card"></i> Thanh toán</a>
    <a href="#"><i class="bi bi-shield-lock"></i> Phân quyền</a>
</div>