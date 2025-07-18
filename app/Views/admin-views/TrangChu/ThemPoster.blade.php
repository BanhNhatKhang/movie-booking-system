
@extends('layouts.admin.master')

@section('title', 'Thêm poster')

@section('page-css')
    <link href="/static/css/admin/LayoutAdmin.css" rel="stylesheet">
@endsection

@section('content')
<div class="container bg-white shadow-sm p-4 rounded mt-4">
    <h4 class="mb-4">Thêm poster mới</h4>
    <form method="POST" enctype="multipart/form-data" action="/luu-poster">
        <div class="mb-3">
            <label for="pt_maposter" class="form-label">Mã poster</label>
            <input type="text" class="form-control" id="pt_maposter" name="pt_maposter" value="{{ $newId }}" readonly required />
        </div>
        <div class="mb-3">
            <label for="anhPoster" class="form-label">Ảnh poster</label>
            <input type="file" class="form-control" id="pt_anhposter" name="anhPoster" accept="image/*" required />
            <div class="mt-2">
                <img id="previewImg" src="#" alt="Xem trước ảnh" style="display:none;max-width:150px;max-height:150px;border:1px solid #ccc;" />
            </div>
        </div>
        <div class="text-end">
            <button type="submit" class="btn btn-success">Thêm poster</button>
            <a href="/quan-ly-trang-chu" class="btn btn-secondary ms-2">Hủy</a>
        </div>
    </form>
</div>
@endsection

@section('page-js')
<script src="/static/js/admin/ThemPoster.js"></script>
@endsection