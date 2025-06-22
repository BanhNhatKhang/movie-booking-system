@extends('layouts.admin.master')

@section('title', 'Thêm ưu đãi')

@section('page-css')
    <link href="/static/css/admin/LayoutAdmin.css" rel="stylesheet">
@endsection

@section('content')
<div class="container bg-white shadow-sm p-4 rounded mt-4">
    <h4 class="mb-4">Thêm ưu đãi mới</h4>
    <form method="POST" enctype="multipart/form-data" action="">
        <div class="mb-3">
            <label for="anhUuDai" class="form-label">Ảnh ưu đãi</label>
            <input type="file" class="form-control" id="anhUuDai" name="anhUuDai" required />
        </div>
        <div class="mb-3">
            <label for="tenUuDai" class="form-label">Tên ưu đãi</label>
            <input type="text" class="form-control" id="tenUuDai" name="tenUuDai" required />
        </div>
        <div class="text-end">
            <button type="submit" class="btn btn-success">Thêm ưu đãi</button>
            <a href="/quan-ly-trang-chu" class="btn btn-secondary ms-2">Hủy</a>
        </div>
    </form>
</div>
@endsection

@section('page-js')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@endsection