<link rel="stylesheet" href="/static/css/users/HeaderFooter.css">
<div class="collapse d-lg-block sidebar" id="mobileSidebar">
    <div class="khf-logo">
        <div class="logo-icon"></div>
        <div class="logo-text">
            <span class="cinema">CINEMA</span>
            <span class="khf">KHF</span>
        </div>
    </div>
    <br>
    <a class="nav-link <?= ($activePage == 'dashboard') ? 'active' : '' ?>" href="/static/html/admin/Dashboard/index.php"><i class="bi bi-speedometer2"></i> Dashboard</a>
    <a class="nav-link <?= ($activePage == 'home') ? 'active' : '' ?>" href="/static/html/admin/TrangChu/QuanLyTrangChu.php"><i class="bi bi-house-door"></i> Quản lý trang chủ</a>
    <a class="nav-link <?= ($activePage == 'admin-movies') ? 'active' : '' ?>" href="/static/html/admin/QuanLyPhim/QuanLyPhim.php"><i class="bi bi-film"></i> Quản lý phim</a>
    <a class="nav-link <?= ($activePage == 'schedule') ? 'active' : '' ?>" href="/static/html/admin/LichChieu/QuanLyLichChieu.php"><i class="bi bi-calendar-event"></i> Quản lý lịch chiếu</a>
    <a class="nav-link <?= ($activePage == 'PhongGhe') ? 'active' : '' ?>" href="/static/html/admin/QuanLyPhongGhe/QuanLyPhongGhe.php"><i class="bi bi-door-closed"></i> Phòng & Ghế</a>
    <a class="nav-link <?= ($activePage == 'admin-orders') ? 'active' : '' ?>" href="/static/html/admin/QuanLyDonDatVe/QuanLyDonDatVe.php"><i class="bi bi-ticket-detailed"></i> Quản lý đơn đặt vé</a>
    <a class="nav-link <?= ($activePage == 'book-ticket') ? 'active' : '' ?>" href="/static/html/admin/DatVeTaiQuay/DatVeTaiQuay.php"><i class="bi bi-ticket-perforated"></i> Đặt vé tại quầy</a>
    <a class="nav-link <?= ($activePage == 'uudai') ? 'active' : '' ?>" href="/static/html/admin/UuDai/QuanLyUuDai.php"><i class="bi bi-gift"></i> Ưu đãi</a>
    <a class="nav-link <?= ($activePage == 'NguoiDung') ? 'active' : '' ?>" href="/static/html/admin/QuanLyNguoiDung/QuanLyNguoiDung.php"><i class="bi bi-people"></i> Người dùng</a>
    <a class="nav-link <?= ($activePage == 'pay') ? 'active' : '' ?>" href="/static/html/admin/ThanhToan/QuanLyThanhToan.php"><i class="bi bi-credit-card"></i> Thanh toán</a>
    <a class="nav-link <?= ($activePage == 'NhanVien') ? 'active' : '' ?>" href="/static/html/admin/QuanLyNhanVien/QuanLyNhanVien.php"><i class="bi bi-person-badge"></i> Quản lý nhân viên</a>
</div>