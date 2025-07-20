@extends('layouts.admin.master')

@section('title', 'Sửa poster')

@section('page-css')
    <link href="/static/css/admin/LayoutAdmin.css" rel="stylesheet">
@endsection

@section('content')
<div class="container bg-white shadow-sm p-4 rounded mt-4">
    <h4 class="mb-4">Sửa poster</h4>
    <form method="POST" enctype="multipart/form-data" action="/cap-nhat-poster">
        <input type="hidden" name="pt_maposter" value="{{ $poster['pt_maposter'] }}">
        <input type="hidden" name="old_img" value="{{ $poster['pt_anhposter'] }}">
        <div class="mb-3">
            <label for="anhPoster" class="form-label">Ảnh poster</label>
            @if($poster['pt_anhposter'])
                <div class="mb-2">
                    <img src="{{ $poster['pt_anhposter'] }}" 
                         id="previewImg" 
                         style="max-width:150px;max-height:150px;border:1px solid #ccc;display:block;"
                         alt="Poster hiện tại">
                    <small class="text-muted">Ảnh hiện tại</small>
                </div>
            @endif
            <input type="file" class="form-control" id="pt_anhposter" name="anhPoster" accept="image/*" />
            <div class="form-text">Để trống nếu không muốn thay đổi ảnh</div>
        </div>
        <div class="text-end">
            <button type="submit" class="btn btn-success">
                <i class="bi bi-save"></i> Cập nhật poster
            </button>
            <a href="/quan-ly-trang-chu" class="btn btn-secondary ms-2">
                <i class="bi bi-x-circle"></i> Hủy
            </a>
        </div>
    </form>
</div>
@endsection

@section('page-js')
<script src="/static/js/admin/SuaPoster.js"></script>
@endsection