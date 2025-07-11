{{-- filepath: d:\Server\project\app\Views\admin-views\TrangChu\QuanLyTrangChu.blade.php --}}
@extends('layouts.admin.master')

@section('title', 'Quản lý trang chủ')

@section('page-css')
    <link href="/static/css/admin/LayoutAdmin.css" rel="stylesheet">
    <link href="/static/css/admin/ModalXoa.css" rel="stylesheet">
@endsection

@section('content')
<div class="container py-4 content">
    <!-- thông báo -->
    @if(isset($_GET['success']))
        @if($_GET['success'] == 'add_poster')
            <div class="alert alert-success">Thêm poster thành công!</div>
        @elseif($_GET['success'] == 'update_poster')
            <div class="alert alert-success">Cập nhật poster thành công!</div>
        @elseif($_GET['success'] == 'delete_poster')
            <div class="alert alert-success">Xóa poster thành công!</div>
        @elseif($_GET['success'] == 'add_uudai')
            <div class="alert alert-success">Thêm ưu đãi thành công!</div>
        @elseif($_GET['success'] == 'update_uudai')
            <div class="alert alert-success">Cập nhật ưu đãi thành công!</div>
        @elseif($_GET['success'] == 'delete_uudai')
            <div class="alert alert-success">Xóa ưu đãi thành công!</div>
        @endif
    @endif

    @if(isset($_GET['error']))
        @if($_GET['error'] == 'poster_in_use')
            <div class="alert alert-danger">Không thể xóa poster này vì đang được sử dụng cho phim!</div>
        @elseif($_GET['error'] == 'not_found')
            <div class="alert alert-danger">Không tìm thấy ưu đãi!</div>
        @elseif($_GET['error'] == 'delete_failed')
            <div class="alert alert-danger">Xóa ưu đãi thất bại!</div>
        @elseif($_GET['error'] == 'system_error')
            <div class="alert alert-danger">Lỗi hệ thống, vui lòng thử lại!</div>
        @endif
    @endif

    <!-- Quản lý Poster -->
    <h1>Quản lý Poster</h1><hr>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <button class="btn btn-primary">
            <a href="/them-poster" class="text-white text-decoration-none">
                <i class="bi bi-plus-circle"></i> Thêm poster mới
            </a>
        </button>
        <form class="d-flex gap-2 w-75 justify-content-end flex-wrap">
            <select class="form-select w-auto" style="min-width:140px;">
                <option value="">Tất cả poster phim</option>
                @forelse($posters ?? [] as $poster)
                    <option value="{{ $poster['pt_maposter'] }}">{{ $poster['ten_phim'] ?? $poster['pt_maposter'] }}</option>
                @empty
                    <option disabled>Chưa có poster</option>
                @endforelse
            </select>
            <button class="btn btn-outline-primary" type="submit">
                <i class="bi bi-search"></i>
            </button>
        </form>
    </div>
    <div class="table-responsive p-3">
        <table class="table align-middle table-hover">
            <thead class="table-dark">
                <tr>
                    <th>STT</th>
                    <th>Ảnh poster</th>
                    <th>Tên phim</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse($posters ?? [] as $i => $p)
                <tr>
                    <td>{{ $i+1 }}</td>
                    <td>
                        @if($p['pt_anhposter'])
                            <img src="{{ $p['pt_anhposter'] }}" alt="Poster" style="width:100px; height:60px; object-fit:cover; border-radius: 4px;">
                        @else
                            <span class="text-muted">Không có ảnh</span>
                        @endif
                    </td>
                    <td>
                        {{-- Nếu có tên phim liên kết thì hiển thị, không thì hiển thị mã poster --}}
                        {{ $p['ten_phim'] ?? $p['pt_maposter'] }}
                    </td>
                    <td>
                        <a href="/sua-poster?id={{ $p['pt_maposter'] }}" class="btn btn-sm btn-warning" title="Sửa">
                            <i class="bi bi-pencil-square"></i>
                        </a>
                        <button 
                            class="btn btn-sm btn-danger btn-delete" 
                            title="Xóa"
                            data-title="poster {{ $p['pt_maposter'] }}"
                            data-url="/xoa-poster?id={{ $p['pt_maposter'] }}">
                            <i class="bi bi-trash"></i>
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center text-muted">Chưa có poster nào</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @include('layouts.admin.Pagination', [
        'currentPage' => $currentPage ?? 1,
        'totalPages' => $totalPages ?? 1,
        'totalItems' => $totalItems ?? 0,
        'itemsPerPage' => $itemsPerPage ?? 5,
        'itemName' => 'poster'
    ])
    <hr><br>

    <!-- Quản lý ưu đãi -->
    <h1>Quản lý ưu đãi</h1><hr>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <button class="btn btn-primary">
            <a href="/them-uu-dai-home" class="text-white text-decoration-none">
                <i class="bi bi-plus-circle"></i> Thêm ưu đãi mới
            </a>
        </button>
        {{-- ✅ Loại bỏ select box tìm kiếm theo tên ưu đãi --}}
    </div>
    <div class="table-responsive p-3">
        <table class="table align-middle table-hover">
            <thead class="table-dark">
                <tr>
                    <th>STT</th>
                    <th>Mã ưu đãi</th> {{-- ✅ Thêm cột mã ưu đãi --}}
                    <th>Ảnh ưu đãi</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse($uuDaiList ?? [] as $i => $uuDai)
                <tr>
                    <td>{{ $i+1 }}</td>
                    <td>
                        <span class="badge bg-secondary">{{ $uuDai['udtc_mauudai'] }}</span> {{-- ✅ Hiển thị mã ưu đãi --}}
                    </td>
                    <td>
                        @if($uuDai['udtc_anhuudai'])
                            <img src="{{ $uuDai['udtc_anhuudai'] }}" alt="Ưu đãi" style="width:100px; height:60px; object-fit:cover; border-radius: 4px;">
                        @else
                            <span class="text-muted">Không có ảnh</span>
                        @endif
                    </td>
                    <td>
                        <a href="/sua-uu-dai-home?id={{ $uuDai['udtc_mauudai'] }}" class="btn btn-sm btn-warning" title="Sửa">
                            <i class="bi bi-pencil-square"></i>
                        </a>
                        <button 
                            class="btn btn-sm btn-danger btn-delete" 
                            title="Xóa"
                            data-title="ưu đãi {{ $uuDai['udtc_mauudai'] }}" {{-- ✅ Sửa data-title dùng mã ưu đãi --}}
                            data-url="/xoa-uu-dai-home?id={{ $uuDai['udtc_mauudai'] }}">
                            <i class="bi bi-trash"></i>
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center text-muted py-4"> {{-- ✅ Sửa colspan từ 4 thành 4 --}}
                        <i class="bi bi-gift display-6 d-block mb-2 opacity-25"></i>
                        <strong>Chưa có ưu đãi nào</strong>
                        <br>
                        <small>Nhấn "Thêm ưu đãi mới" để bắt đầu</small>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('page-js')
    <script src="/static/js/admin/ModalXoa.js"></script>
    @include('admin-views.ModalXoa.ModalXoa')
@endsection