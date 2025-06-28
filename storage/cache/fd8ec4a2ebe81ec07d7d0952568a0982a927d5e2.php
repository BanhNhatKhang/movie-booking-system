
<header>
    <div class="container">
        <div class="row align-items-center">
            <div class="col">
                <!-- Header section -->
                <div class="bg-header text-white py-2">
                    <div class="container">
                        <div class="d-flex justify-content-between align-items-center flex-wrap">
                            <a class="navbar-brand" href="/">
                                <div class="khf-logo">
                                    <div class="logo-icon"></div>
                                    <div class="logo-text">
                                        <span class="cinema">CINEMA</span>
                                        <span class="khf">KHF</span>
                                    </div>
                                </div>
                            </a>
                            
                            
                            <?php if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true): ?>
                                
                                <div class="dropdown">
                                    <a href="#" class="d-flex align-items-center user-dropdown-link text-decoration-none" 
                                       id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="outline: none;">
                                        <i class="bi bi-person-circle fs-4 me-2"></i>
                                        <span class="fw-semibold user-name"><?php echo e($_SESSION['user_name'] ?? 'User'); ?></span>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end mt-0 shadow-sm" aria-labelledby="userDropdown">
                                        <li>
                                            <a class="dropdown-item hover-red-nav" href="/thong-tin-ca-nhan">Thông tin cá nhân</a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item hover-red-nav" href="/lich-su-dat-ve">Lịch sử đặt vé</a>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <a class="dropdown-item hover-red-nav" href="/dang-xuat">Đăng xuất</a>
                                        </li>
                                    </ul>
                                </div>
                            <?php else: ?>
                                
                                <div class="d-flex align-items-center gap-4 auth-links">
                                    <a href="/dang-nhap" class="<?php echo e(isset($activePage) && $activePage == 'dangnhap' ? 'active' : ''); ?>">Đăng nhập</a>
                                    <span class="text-white-50 opacity-75">|</span>
                                    <a href="/dang-ky" class="<?php echo e(isset($activePage) && $activePage == 'dangky' ? 'active' : ''); ?>">Đăng ký</a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Navigation menu -->
                <nav class="navbar navbar-expand-lg custom-navbar">
                    <div class="container px-0">
                        <button
                            class="navbar-toggler"
                            type="button"
                            data-bs-toggle="collapse"
                            data-bs-target="#navbarNav"
                        >
                            <span class="navbar-toggler-icon"></span>
                        </button>

                        <div class="collapse navbar-collapse" id="navbarNav">
                            <ul class="navbar-nav mx-auto">
                                <li class="nav-item">
                                    <a class="nav-link <?php echo e(isset($activePage) && $activePage == 'home' ? 'active' : ''); ?>" href="/">LỊCH CHIẾU</a>
                                </li>
                                <li class="nav-item dropdown">
                                    <a class="nav-link <?php echo e(isset($activePage) && $activePage == 'movies' ? 'active' : ''); ?>" href="#" id="phimDropdown"
                                    role="button" data-bs-toggle="dropdown" aria-expanded="false">PHIM</a>
                                    <ul class="dropdown-menu" aria-labelledby="phimDropdown">
                                        <li>
                                            <a class="dropdown-item hover-red-nav" href="/phim-dang-chieu">Phim đang chiếu</a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item hover-red-nav" href="/phim-sap-chieu">Phim sắp chiếu</a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link <?php echo e(isset($activePage) && $activePage == 'offers' ? 'active' : ''); ?>" href="/uu-dai">ƯU ĐÃI</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link <?php echo e(isset($activePage) && $activePage == 'member' ? 'active' : ''); ?>" href="/thanh-vien">THÀNH VIÊN</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </nav>
            </div>
        </div>
    </div>
</header>

<style>
    .user-dropdown-link:hover,
    .user-dropdown-link:focus {
        color: #fff;
        text-decoration: none;
        background: none;
        box-shadow: none;
    }
    .dropdown-menu .dropdown-item {
        color: #212529 !important;
        background-color: transparent;
    }
    .user-dropdown-link {
        color: white;
    }
    .auth-links a {
        color: white;
        text-decoration: none;
    }
    .auth-links a:hover {
        color: #dc3545;
    }
</style><?php /**PATH C:\mysites\ct27501-project-BanhNhatKhang-1\app\Views/layouts/users/Header.blade.php ENDPATH**/ ?>