@extends('layouts.admin.master')

@section('title', 'Thêm poster')

@section('page-css')
    <link href="/static/css/admin/LayoutAdmin.css" rel="stylesheet">
@endsection

@section('content')
<div class="container bg-white shadow-sm p-4 rounded mt-4">
    <h4 class="mb-4">Thêm poster mới</h4>
    <form method="POST" enctype="multipart/form-data" action="">
        <div class="mb-3">
            <label for="anhPoster" class="form-label">Ảnh poster</label>
            <input type="file" class="form-control" id="anhPoster" name="anhPoster" required />
        </div>
        <div class="mb-3">
            <label for="lienKetPhim" class="form-label">Liên kết đến phim</label>
            <select class="form-select" id="lienKetPhim" name="lienKetPhim" required>
                <option value="">-- Chọn phim liên kết --</option>
                <option value="1">Spider-Man: No Way Home</option>
                <option value="2">Inside Out 2</option>
                {{-- Dữ liệu từ backend --}}
            </select>
        </div>
        <div class="text-end">
            <button type="submit" class="btn btn-success">Thêm poster</button>
            <a href="/quan-ly-trang-chu" class="btn btn-secondary ms-2">Hủy</a>        </div>
    </form>
</div>
@endsection

@section('page-js')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@endsection