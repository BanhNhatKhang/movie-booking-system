{{-- filepath: d:\Server\ct27501-project-BanhNhatKhang\app\Views\admin-views\UuDai\QuanLyUuDai.blade.php --}}

@extends('layouts.admin.master')

@section('title', 'Quản lý ưu đãi')

@section('page-css')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="/static/css/admin/LayoutAdmin.css" rel="stylesheet">
    <link href="/static/css/admin/QuanLyUuDai.css" rel="stylesheet">
@endsection

@section('content')
<div class="container py-4 content">
    <h1>Quản lý ưu đãi</h1>
    <hr>
    
    {{-- Success/Error Messages --}}
    @if(isset($success))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle"></i> {{ $success }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    
    @if(isset($error))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle"></i> {{ $error }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    
    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="/them-uu-dai" class="btn btn-primary text-white text-decoration-none">
            <i class="bi bi-plus-circle"></i> Thêm ưu đãi mới
        </a>
        <form class="d-flex gap-2 w-75 justify-content-end flex-wrap" method="GET">
            <select name="ten_uu_dai" class="form-select w-auto" style="min-width:140px;">
                <option value="">Tên ưu đãi</option>
                @foreach($uuDaiList ?? [] as $uuDai)
                    <option value="{{ $uuDai['ud_tenuudai'] }}" {{ ($filters['ten_uu_dai'] ?? '') === $uuDai['ud_tenuudai'] ? 'selected' : '' }}>
                        {{ $uuDai['ud_tenuudai'] }}
                    </option>
                @endforeach
            </select>
            <select name="loai_uu_dai" class="form-select w-auto" style="min-width:140px;">
                <option value="">Loại ưu đãi</option>
                <option value="COMBO" {{ ($filters['loai_uu_dai'] ?? '') === 'COMBO' ? 'selected' : '' }}>COMBO</option>
                <option value="GIẢM GIÁ" {{ ($filters['loai_uu_dai'] ?? '') === 'GIẢM GIÁ' ? 'selected' : '' }}>GIẢM GIÁ</option>
                <option value="SINH NHẬT" {{ ($filters['loai_uu_dai'] ?? '') === 'SINH NHẬT' ? 'selected' : '' }}>SINH NHẬT</option>
                <option value="SỚM" {{ ($filters['loai_uu_dai'] ?? '') === 'SỚM' ? 'selected' : '' }}>SỚM</option>
                <option value="NGÂN HÀNG" {{ ($filters['loai_uu_dai'] ?? '') === 'NGÂN HÀNG' ? 'selected' : '' }}>NGÂN HÀNG</option>
            </select>
            <select name="trang_thai" class="form-select w-auto" style="min-width:140px;">
                <option value="">Trạng thái ưu đãi</option>
                <option value="Đang diễn ra" {{ ($filters['trang_thai'] ?? '') === 'Đang diễn ra' ? 'selected' : '' }}>Đang diễn ra</option>
                <option value="Sắp diễn ra" {{ ($filters['trang_thai'] ?? '') === 'Sắp diễn ra' ? 'selected' : '' }}>Sắp diễn ra</option>
                <option value="Kết thúc" {{ ($filters['trang_thai'] ?? '') === 'Kết thúc' ? 'selected' : '' }}>Kết thúc</option>
            </select>
            <button class="btn btn-outline-primary" type="submit">
                <i class="bi bi-search"></i>
            </button>
            @if(!empty($filters['ten_uu_dai']) || !empty($filters['loai_uu_dai']) || !empty($filters['trang_thai']))
                <a href="/quan-ly-uu-dai" class="btn btn-outline-secondary">
                    <i class="bi bi-x-circle"></i> Clear
                </a>
            @endif
        </form>
    </div>
    
    <div class="table-responsive p-3">
        <table class="table align-middle table-hover">
            <thead class="table-dark">
                <tr>
                    <th>STT</th>
                    <th>Ảnh ưu đãi</th>
                    <th>Tên ưu đãi</th>
                    <th>Loại ưu đãi</th>
                    <th style="min-width:220px; max-width:320px;">Nội dung chi tiết</th>
                    <th>Thời gian</th>
                    <th>Trạng thái</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse($uuDaiList ?? [] as $index => $uuDai)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>
                        @if($uuDai['ud_anhuudai'])
                            <img src="{{ $uuDai['ud_anhuudai'] }}" 
                                 alt="{{ $uuDai['ud_tenuudai'] }}" 
                                 width="100" 
                                 class="img-thumbnail"
                                 onerror="this.src='/static/imgs/placeholder-uudai.jpg'">
                        @else
                            <div class="bg-light d-flex align-items-center justify-content-center" style="width: 100px; height: 70px;">
                                <i class="bi bi-image text-muted"></i>
                            </div>
                        @endif
                    </td>
                    <td>{{ $uuDai['ud_tenuudai'] }}</td>
                    <td>
                        <span class="badge bg-info">{{ $uuDai['ud_loaiuudai'] }}</span>
                    </td>
                    <td class="detail-cell">
                        {{ $uuDai['ud_noidung'] }}
                    </td>
                    <td>
                        <small>
                            {{ date('d/m/Y', strtotime($uuDai['ud_thoigianbatdau'])) }} - 
                            {{ date('d/m/Y', strtotime($uuDai['ud_thoigianketthuc'])) }}
                        </small>
                    </td>
                    <td>
                        @if($uuDai['ud_trangthai'] === 'Đang diễn ra')
                            <span class="badge bg-success">Đang diễn ra</span>
                        @elseif($uuDai['ud_trangthai'] === 'Sắp diễn ra')
                            <span class="badge bg-warning text-dark">Sắp diễn ra</span>
                        @else
                            <span class="badge bg-secondary">Kết thúc</span>
                        @endif
                    </td>
                    <td>
                        <a href="/sua-uu-dai?id={{ $uuDai['ud_mauudai'] }}" class="btn btn-sm btn-warning" title="Sửa">
                            <i class="bi bi-pencil-square"></i>
                        </a>
                        <a href="/xoa-uu-dai?id={{ $uuDai['ud_mauudai'] }}"
                           onclick="return confirm('Bạn có chắc chắn muốn xóa ưu đãi \"{{ $uuDai['ud_tenuudai'] }}\" không?')"
                           class="btn btn-sm btn-danger" title="Xóa">
                            <i class="bi bi-trash"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-4">
                        <div class="text-muted">
                            <i class="bi bi-inbox" style="font-size: 3rem;"></i>
                            <h5 class="mt-3">Không có ưu đãi nào</h5>
                            <p>Hãy thêm ưu đãi mới để bắt đầu quản lý.</p>
                            <a href="/them-uu-dai" class="btn btn-primary">
                                <i class="bi bi-plus-circle"></i> Thêm ưu đãi đầu tiên
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <hr>
</div>
@endsection

@section('page-js')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@endsection