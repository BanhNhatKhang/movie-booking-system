
<div class="topbar d-flex justify-content-between align-items-center">
    <div class="d-flex align-items-center">
        <button class="btn btn-light d-md-none me-2" type="button" data-bs-toggle="collapse" data-bs-target="#mobileSidebar">
            <i class="bi bi-list"></i>
        </button>
        <span class="fw-bold text-uppercase">HỆ THỐNG QUẢN LÝ RẠP PHIM</span>
    </div>
    <div class="user-info d-flex align-items-center">
        <div class="dropdown">
            <a class="d-flex align-items-center text-decoration-none text-dark dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-person-circle me-2"></i>
                <span class="text-dark">{{ $_SESSION['user_name'] ?? 'Admin' }}</span>
                <small class="text-muted ms-1">({{ $_SESSION['user_role'] ?? 'admin' }})</small>
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><h6 class="dropdown-header">Tài khoản Admin</h6></li>
                <li><span class="dropdown-item-text"><i class="bi bi-envelope me-2"></i>{{ $_SESSION['user_email'] ?? 'admin@cinema.com' }}</span></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item text-danger" href="/dang-xuat"><i class="bi bi-box-arrow-right me-2"></i>Đăng xuất</a></li>
            </ul>
        </div>
    </div>
</div>

{{-- Thông báo --}}
@if(isset($_SESSION['success_message']))
    <div class="alert alert-success alert-dismissible fade show mt-2 mx-3" role="alert">
        <i class="bi bi-check-circle me-2"></i>{{ $_SESSION['success_message'] }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @php unset($_SESSION['success_message']); @endphp
@endif

@if(isset($_SESSION['error_message']))
    <div class="alert alert-danger alert-dismissible fade show mt-2 mx-3" role="alert">
        <i class="bi bi-exclamation-triangle me-2"></i>{{ $_SESSION['error_message'] }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @php unset($_SESSION['error_message']); @endphp
@endif