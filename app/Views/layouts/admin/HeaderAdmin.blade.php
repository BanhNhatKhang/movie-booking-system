
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
                <li><span class="dropdown-item-text">
                    <i class="bi bi-envelope me-2"></i>{{ $_SESSION['user_email'] ?? 'admin@cinema.com' }}
                </span></li>
                <li><hr class="dropdown-divider"></li>
                
                {{-- THÊM: Role switching --}}
                <li><h6 class="dropdown-header">Chuyển đổi tài khoản</h6></li>
                <li><a class="dropdown-item" href="/admin/switch-to-user">
                    <i class="bi bi-person me-2 text-info"></i>Chuyển sang tài khoản User
                </a></li>
                
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item text-danger" href="/dang-xuat">
                    <i class="bi bi-box-arrow-right me-2"></i>Đăng xuất
                </a></li>
            </ul>
        </div>
    </div>
</div>

{{-- Thông báo auto-hide --}}
@if(isset($_SESSION['success_message']))
    <div class="alert alert-success alert-dismissible fade show mt-2 mx-3" role="alert" id="successAlert">
        <i class="bi bi-check-circle me-2"></i>{{ $_SESSION['success_message'] }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @php unset($_SESSION['success_message']); @endphp
@endif

@if(isset($_SESSION['error_message']))
    <div class="alert alert-danger alert-dismissible fade show mt-2 mx-3" role="alert" id="errorAlert">
        <i class="bi bi-exclamation-triangle me-2"></i>{{ $_SESSION['error_message'] }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @php unset($_SESSION['error_message']); @endphp
@endif

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