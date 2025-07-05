@if(isset($_SESSION['error_message']))
    <div class="alert alert-danger alert-dismissible fade show mx-3 mt-2" role="alert" id="errorAlert">
        <i class="bi bi-exclamation-triangle me-2"></i>{{ $_SESSION['error_message'] }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @php unset($_SESSION['error_message']); @endphp
@endif

@if(isset($_SESSION['success_message']))
    <div class="alert alert-success alert-dismissible fade show mx-3 mt-2" role="alert" id="successAlert">
        <i class="bi bi-check-circle me-2"></i>{{ $_SESSION['success_message'] }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @php unset($_SESSION['success_message']); @endphp
@endif
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
                            
                            {{-- THÊM: Badge khi admin đang ở user mode --}}
                            @if(isset($_SESSION['original_role']) && $_SESSION['original_role'] === 'admin')
                                <span class="badge bg-warning me-3">
                                    <i class="bi bi-person-gear me-1"></i>Admin đang xem như User
                                </span>
                            @endif
                            
                            {{-- Kiểm tra đăng nhập --}}
                            @if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true)
                                {{-- Header khi đã đăng nhập --}}
                                <div class="dropdown">
                                    <a href="#" class="d-flex align-items-center user-dropdown-link text-decoration-none" 
                                       id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="outline: none;">
                                        <i class="bi bi-person-circle fs-4 me-2"></i>
                                        <span class="fw-semibold user-name">{{ $_SESSION['user_name'] ?? 'User' }}</span>
                                        <small class="text-white-50 ms-1">({{ $_SESSION['user_role'] }})</small>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end mt-0 shadow-sm" aria-labelledby="userDropdown">
                                        <li><h6 class="dropdown-header">Tài khoản</h6></li>
                                        <li>
                                            <a class="dropdown-item hover-red-nav" href="/thong-tin-ca-nhan">
                                                <i class="bi bi-person me-2"></i>Thông tin cá nhân
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item hover-red-nav" href="/lich-su-dat-ve">
                                                <i class="bi bi-clock-history me-2"></i>Lịch sử đặt vé
                                            </a>
                                        </li>
                                        
                                        {{-- THÊM: Nếu có original_role admin thì hiển thị nút quay lại --}}
                                        @if(isset($_SESSION['original_role']) && $_SESSION['original_role'] === 'admin')
                                            <li><hr class="dropdown-divider"></li>
                                            <li><h6 class="dropdown-header">Quản trị</h6></li>
                                            <li>
                                                <a class="dropdown-item text-success hover-red-nav" href="/admin/switch-to-admin">
                                                    <i class="bi bi-gear me-2"></i>Quay lại quyền Admin
                                                </a>
                                            </li>
                                        @endif
                                        
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <a class="dropdown-item text-danger hover-red-nav" href="/dang-xuat">
                                                <i class="bi bi-box-arrow-right me-2"></i>Đăng xuất
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            @else
                                {{-- Header khi chưa đăng nhập --}}
                                <div class="d-flex align-items-center gap-4 auth-links">
                                    <a href="/dang-nhap" class="{{ isset($activePage) && $activePage == 'dangnhap' ? 'active' : '' }}">Đăng nhập</a>
                                    <span class="text-white-50 opacity-75">|</span>
                                    <a href="/dang-ky" class="{{ isset($activePage) && $activePage == 'dangky' ? 'active' : '' }}">Đăng ký</a>
                                </div>
                            @endif
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
                                <a class="nav-link {{ ($activePage == 'home') ? 'active' : '' }}" href="/">LỊCH CHIẾU</a>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link {{ ($activePage == 'movies') ? 'active' : '' }}" href="#" id="phimDropdown"
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
                                <a class="nav-link {{ ($activePage == 'offers') ? 'active' : '' }}" href="/uu-dai">ƯU ĐÃI</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ ($activePage == 'member') ? 'active' : '' }}" href="/thanh-vien">THÀNH VIÊN</a>
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
    
    /* THÊM: Style cho badge admin */
    .badge {
        font-size: 0.75rem;
        padding: 0.35em 0.65em;
    }
    .dropdown-header {
        font-size: 0.875rem;
        font-weight: 600;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const successAlert = document.getElementById('successAlert');
    if (successAlert) {
        setTimeout(function() {
            successAlert.style.transition = 'opacity 0.5s ease-out';
            successAlert.style.opacity = '0';
            setTimeout(function() {
                if (successAlert.parentNode) {
                    successAlert.parentNode.removeChild(successAlert);
                }
            }, 500);
        }, 3000);
    }

    const errorAlert = document.getElementById('errorAlert');
    if (errorAlert) {
        setTimeout(function() {
            errorAlert.style.transition = 'opacity 0.5s ease-out';
            errorAlert.style.opacity = '0';
            setTimeout(function() {
                if (errorAlert.parentNode) {
                    errorAlert.parentNode.removeChild(errorAlert);
                }
            }, 500);
        }, 5000);
    }
});
</script>