{{-- filepath: d:\Server\ct27501-project-BanhNhatKhang\app\Views\admin-views\QuanLyPhim\QuanLyPhim.blade.php --}}
@extends('layouts.admin.master')

@section('title', 'Quản lý Phim')

@section('page-css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/static/css/admin/LayoutAdmin.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        .movie-poster {
            width: 60px;
            height: 80px;
            object-fit: cover;
            border-radius: 4px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .action-btn {
            margin: 2px;
        }
        .table-responsive {
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .movie-info {
            min-width: 200px;
        }
        .status-badge {
            font-size: 0.85em;
            padding: 4px 8px;
        }
    </style>
@endsection

@section('content')
<div class="container py-4 content" style="background-color: white;">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="mb-3">
                <i class="bi bi-film text-primary"></i> Quản lý Phim
            </h1>
            
            <!-- Success/Error Messages -->
            @if(isset($_GET['success']))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle"></i>
                    @if($_GET['success'] == 'add_success')
                        Thêm phim mới thành công!
                    @elseif($_GET['success'] == 'update_success')
                        Cập nhật phim thành công!
                    @elseif($_GET['success'] == 'status_updated')
                        Đổi trạng thái phim thành công!
                    @else
                        Thao tác thành công!
                    @endif
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(isset($_GET['error']))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle"></i>
                    @if($_GET['error'] == 'invalid_id')
                        ID phim không hợp lệ!
                    @elseif($_GET['error'] == 'phim_not_found')
                        Không tìm thấy phim!
                    @elseif($_GET['error'] == 'system_error')
                        Có lỗi hệ thống xảy ra!
                    @else
                        Có lỗi xảy ra!
                    @endif
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(isset($error))
                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle"></i> {{ $error }}
                </div>
            @endif
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <!-- Add New Movie Button -->
                <a href="/them-phim" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Thêm phim mới
                </a>

                <!-- Search and Filter Form -->
                <form class="d-flex gap-2 flex-wrap" method="GET" style="min-width: 400px;">
                    <div class="input-group" style="min-width: 200px;">
                        <span class="input-group-text">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" class="form-control" name="q" 
                               placeholder="Tìm tên phim, đạo diễn, diễn viên..." 
                               value="{{ $search ?? '' }}">
                    </div>
                    
                    <select class="form-select" name="status" style="max-width: 160px;">
                        <option value="">Tất cả trạng thái</option>
                        <option value="showing" {{ ($statusFilter ?? '') == 'showing' ? 'selected' : '' }}>
                            Đang chiếu
                        </option>
                        <option value="coming" {{ ($statusFilter ?? '') == 'coming' ? 'selected' : '' }}>
                            Sắp chiếu
                        </option>
                        <option value="ended" {{ ($statusFilter ?? '') == 'ended' ? 'selected' : '' }}>
                            Đã kết thúc
                        </option>
                    </select>
                    
                    <button class="btn btn-outline-primary" type="submit">
                        <i class="bi bi-funnel"></i> Lọc
                    </button>
                    
                    @if(!empty($search) || !empty($statusFilter))
                        <a href="/quan-ly-phim" class="btn btn-outline-secondary">
                            <i class="bi bi-x-circle"></i> Xóa bộ lọc
                        </a>
                    @endif
                </form>
            </div>
        </div>
    </div>

    <!-- Statistics -->
    @if(!empty($movies))
        <div class="row mb-3">
            <div class="col-12">
                <div class="alert alert-info mb-0">
                    <i class="bi bi-info-circle"></i>
                    Hiển thị <strong>{{ count($movies) }}</strong> phim trong tổng số <strong>{{ $totalPhim ?? 0 }}</strong> phim
                    @if(!empty($search))
                        với từ khóa "<strong>{{ $search }}</strong>"
                    @endif
                    @if(!empty($statusFilter))
                        @php
                            $statusNames = [
                                'showing' => 'Đang chiếu',
                                'coming' => 'Sắp chiếu', 
                                'ended' => 'Đã kết thúc'
                            ];
                        @endphp
                        có trạng thái "<strong>{{ $statusNames[$statusFilter] ?? $statusFilter }}</strong>"
                    @endif
                </div>
            </div>
        </div>
    @endif

    <!-- Movies Table -->
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th style="width: 80px;">Poster</th>
                    <th style="min-width: 250px;">Thông tin phim</th>
                    <th style="width: 120px;">Thể loại</th>
                    <th style="width: 100px;">Thời lượng</th>
                    <th style="width: 120px;">Ngày phát hành</th>
                    <th style="width: 100px;">Trạng thái</th>
                    <th style="width: 140px;">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($movies ?? [] as $movie)
                    <tr>
                        <!-- Poster -->
                        <td>
                            @if(!empty($movie['poster']) && $movie['poster'] != '/static/imgs/no-poster.jpg')
                                <img src="{{ $movie['poster'] }}" class="movie-poster" alt="Poster {{ $movie['name'] }}">
                            @else
                                <div class="movie-poster bg-light d-flex align-items-center justify-content-center">
                                    <i class="bi bi-image text-muted"></i>
                                </div>
                            @endif
                        </td>

                        <!-- Movie Info -->
                        <td class="movie-info">
                            <div>
                                <strong class="text-primary">{{ $movie['name'] ?? 'N/A' }}</strong>
                                <small class="text-muted d-block">ID: {{ $movie['id'] ?? 'N/A' }}</small>
                            </div>
                            
                            @if(!empty($movie['director']))
                                <small class="text-secondary">
                                    <i class="bi bi-person-badge"></i> {{ $movie['director'] }}
                                </small>
                            @endif
                            
                            @if(!empty($movie['trailer']))
                                <div class="mt-1">
                                    <a href="{{ $movie['trailer'] }}" target="_blank" 
                                       class="btn btn-outline-primary btn-sm">
                                        <i class="bi bi-play-circle"></i> Trailer
                                    </a>
                                </div>
                            @endif
                        </td>

                        <!-- Genre -->
                        <td>
                            <span class="badge bg-secondary">{{ $movie['genre'] ?? 'N/A' }}</span>
                        </td>

                        <!-- Duration -->
                        <td>
                            <span class="fw-bold">{{ $movie['duration'] ?? 0 }}</span> phút
                        </td>

                        <!-- Release Date -->
                        <td>
                            @if(!empty($movie['release']))
                                {{ date('d/m/Y', strtotime($movie['release'])) }}
                            @else
                                <span class="text-muted">N/A</span>
                            @endif
                        </td>

                        <!-- Status -->
                        <td>
                            @php
                                $statusConfig = [
                                    'showing' => ['bg-success', 'Đang chiếu'],
                                    'coming' => ['bg-warning text-dark', 'Sắp chiếu'],
                                    'ended' => ['bg-danger', 'Đã kết thúc'],
                                    'suspended' => ['bg-secondary', 'Tạm ngưng'],
                                    'inactive' => ['bg-dark', 'Không hoạt động']
                                ];
                                $status = $movie['status'] ?? 'ended';
                                $config = $statusConfig[$status] ?? ['bg-secondary', 'Không xác định'];
                            @endphp
                            <span class="badge {{ $config[0] }} status-badge">
                                {{ $config[1] }}
                            </span>
                        </td>

                        <!-- Actions -->
                        <td>
                            <div class="btn-group" role="group">
                                <a href="/sua-phim?id={{ $movie['id'] }}" 
                                   class="btn btn-warning btn-sm action-btn" 
                                   title="Sửa phim">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                
                                <a href="/doi-trang-thai-phim?id={{ $movie['id'] }}" 
                                   class="btn btn-info btn-sm action-btn" 
                                   title="Đổi trạng thái"
                                   onclick="return confirm('Bạn có chắc muốn đổi trạng thái phim này?')">
                                    <i class="bi bi-arrow-repeat"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <div class="text-muted">
                                <i class="bi bi-film display-4 d-block mb-3"></i>
                                <h5>Không tìm thấy phim nào</h5>
                                <p class="mb-3">
                                    @if(!empty($search) || !empty($statusFilter))
                                        Thử thay đổi điều kiện tìm kiếm hoặc <a href="/quan-ly-phim">xem tất cả phim</a>
                                    @else
                                        Chưa có phim nào trong hệ thống
                                    @endif
                                </p>
                                <a href="/them-phim" class="btn btn-primary">
                                    <i class="bi bi-plus-circle"></i> Thêm phim đầu tiên
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if(($totalPages ?? 1) > 1)
        <div class="row mt-4">
            <div class="col-12">
                <nav aria-label="Phân trang phim">
                    <ul class="pagination justify-content-center">
                        <!-- Previous Page -->
                        @if(($currentPage ?? 1) > 1)
                            <li class="page-item">
                                <a class="page-link" href="?page={{ ($currentPage ?? 1) - 1 }}{{ !empty($search) ? '&q=' . urlencode($search) : '' }}{{ !empty($statusFilter) ? '&status=' . $statusFilter : '' }}">
                                    <i class="bi bi-chevron-left"></i> Trước
                                </a>
                            </li>
                        @endif

                        <!-- Page Numbers -->
                        @php
                            $currentPage = $currentPage ?? 1;
                            $totalPages = $totalPages ?? 1;
                            $startPage = max(1, $currentPage - 2);
                            $endPage = min($totalPages, $currentPage + 2);
                        @endphp

                        @for($i = $startPage; $i <= $endPage; $i++)
                            <li class="page-item {{ $i == $currentPage ? 'active' : '' }}">
                                <a class="page-link" href="?page={{ $i }}{{ !empty($search) ? '&q=' . urlencode($search) : '' }}{{ !empty($statusFilter) ? '&status=' . $statusFilter : '' }}">
                                    {{ $i }}
                                </a>
                            </li>
                        @endfor

                        <!-- Next Page -->
                        @if($currentPage < $totalPages)
                            <li class="page-item">
                                <a class="page-link" href="?page={{ $currentPage + 1 }}{{ !empty($search) ? '&q=' . urlencode($search) : '' }}{{ !empty($statusFilter) ? '&status=' . $statusFilter : '' }}">
                                    Sau <i class="bi bi-chevron-right"></i>
                                </a>
                            </li>
                        @endif
                    </ul>
                </nav>

                <!-- Page Info -->
                <div class="text-center text-muted">
                    Trang {{ $currentPage }} / {{ $totalPages }} 
                    (Tổng: {{ $totalPhim ?? 0 }} phim)
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

@section('page-js')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-dismiss alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert-dismissible');
    alerts.forEach(alert => {
        setTimeout(() => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000);
    });

    // Confirm status change
    const statusButtons = document.querySelectorAll('a[href*="doi-trang-thai-phim"]');
    statusButtons.forEach(btn => {
        btn.addEventListener('click', function(e) {
            const movieName = this.closest('tr').querySelector('.text-primary').textContent;
            if (!confirm(`Bạn có chắc muốn đổi trạng thái phim "${movieName}"?`)) {
                e.preventDefault();
            }
        });
    });

    // Enhanced search with enter key
    const searchInput = document.querySelector('input[name="q"]');
    if (searchInput) {
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                this.form.submit();
            }
        });
    }
});
</script>
@endsection