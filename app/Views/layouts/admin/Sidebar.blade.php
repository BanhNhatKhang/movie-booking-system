<div class="collapse d-lg-block sidebar" id="mobileSidebar">
    <div class="khf-logo">
        <div class="logo-icon"></div>
        <div class="logo-text">
            <span class="cinema">CINEMA</span>
            <span class="khf">KHF</span>
        </div>
    </div>
    <br>
    <a class="nav-link <?= ($activePage == 'dashboard') ? 'active' : '' ?>" href="/dashboard"><i class="bi bi-speedometer2"></i> Dashboard</a>
    <a class="nav-link <?= ($activePage == 'home') ? 'active' : '' ?>" href="/quan-ly-trang-chu"><i class="bi bi-house-door"></i> Quản lý trang chủ</a>
    <a class="nav-link <?= ($activePage == 'admin-movies') ? 'active' : '' ?>" href="/quan-ly-phim"><i class="bi bi-film"></i> Quản lý phim</a>
    <a class="nav-link <?= ($activePage == 'schedule') ? 'active' : '' ?>" href="/quan-ly-lich-chieu"><i class="bi bi-calendar-event"></i> Quản lý lịch chiếu</a>
    <a class="nav-link <?= ($activePage == 'PhongGhe') ? 'active' : '' ?>" href="/quan-ly-phong-ghe"><i class="bi bi-door-closed"></i> Phòng & Ghế</a>
    <a class="nav-link <?= ($activePage == 'admin-orders') ? 'active' : '' ?>" href="/quan-ly-don-dat-ve"><i class="bi bi-ticket-detailed"></i> Quản lý đơn đặt vé</a>
    <a class="nav-link <?= ($activePage == 'book-ticket') ? 'active' : '' ?>" href="/dat-ve-tai-quay"><i class="bi bi-ticket-perforated"></i> Đặt vé tại quầy</a>
    <a class="nav-link <?= ($activePage == 'uudai') ? 'active' : '' ?>" href="/quan-ly-uu-dai"><i class="bi bi-gift"></i> Ưu đãi</a>
    <a class="nav-link <?= ($activePage == 'user') ? 'active' : '' ?>" href="/quan-ly-nguoi-dung"><i class="bi bi-people"></i> Người dùng</a>
    <a class="nav-link <?= ($activePage == 'pay') ? 'active' : '' ?>" href="/quan-ly-thanh-toan"><i class="bi bi-credit-card"></i> Thanh toán</a>
    <a class="nav-link <?= ($activePage == 'NhanVien') ? 'active' : '' ?>" href="/quan-ly-nhan-vien"><i class="bi bi-person-badge"></i> Quản lý nhân viên</a>
</div>