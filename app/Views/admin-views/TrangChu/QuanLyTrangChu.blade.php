{{-- filepath: c:\Servers\test\app\Views\admin-view\TrangChu\QuanLyTrangChu.blade.php --}}
@extends('layouts.admin.master')

@section('title', 'Quản lý trang chủ')

@section('page-css')
    <link href="/static/css/admin/LayoutAdmin.css" rel="stylesheet">
    <link href="/static/css/admin/ModalXoa.css" rel="stylesheet">
@endsection

@section('content')
<div class="container py-4 content">
    <h1>Quản lý Poster</h1><hr>
    @if(isset($_GET['error']) && $_GET['error'] == 'poster_in_use')
        <div class="alert alert-danger">Không thể xóa poster này vì đang được sử dụng cho phim!</div>
    @endif
    <div class="d-flex justify-content-between align-items-center mb-3">
        <button class="btn btn-primary">
            <a href="/them-poster" class="text-white text-decoration-none">
                <i class="bi bi-plus-circle"></i> Thêm poster mới
            </a>
        </button>
        <form class="d-flex gap-2 w-75 justify-content-end flex-wrap">
            <select class="form-select w-auto" style="min-width:140px;">
                <option value="">Tất cả poster phim</option>
                <option>Avengers: Endgame</option>
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
    @forelse($posters as $i => $p)
    <tr>
        <td>{{ $i+1 }}</td>
        <td>
            @if($p['pt_anhposter'])
            <img src="{{ $p['pt_anhposter'] }}" alt="Poster" style="width:100px; height:140px; object-fit:cover;">            @else
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
    <hr><br>

    <h1>Quản lý ưu đãi</h1><hr>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <button class="btn btn-primary">
            <a href="/them-uu-dai" class="text-white text-decoration-none">
                <i class="bi bi-plus-circle"></i> Thêm ưu đãi mới
            </a>
        </button>
        <form class="d-flex gap-2 w-75 justify-content-end flex-wrap">
            <select class="form-select w-auto" style="min-width:140px;">
                <option value="">Tất cả ưu đãi</option>
                <option>Thứ 4 vui vẻ</option>
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
                    <th>Ảnh ưu đãi</th>
                    <th>Tên ưu đãi</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td><img src="poster1.jpg" alt="Poster" width="100"></td>
                    <td>Thứ 4 vui vẻ</td>
                    <td>
                        <a href="/sua-uu-dai" class="btn btn-sm btn-warning" title="Sửa">
                            <i class="bi bi-pencil-square"></i>
                        </a>
                        <button 
                            class="btn btn-sm btn-danger btn-delete" 
                            title="Xóa"
                            data-title="ưu đãi 'Thứ 4 vui vẻ'"
                            data-url="XoaUuDai.php?id=1">
                            <i class="bi bi-trash"></i>
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('page-js')
    <script src="/static/js/admin/ModalXoa.js"></script>
    @include('admin-views.ModalXoa.ModalXoa')
@endsection