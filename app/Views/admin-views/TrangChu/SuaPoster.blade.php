@extends('layouts.admin.master')

@section('title', 'Sửa poster')

@section('page-css')
    <link href="/static/css/admin/LayoutAdmin.css" rel="stylesheet">
@endsection

@section('content')
<div class="container bg-white shadow-sm p-4 rounded mt-4">
    <h4 class="mb-4">Sửa poster</h4>
    <form method="POST" enctype="multipart/form-data" action="">
        <input type="hidden" name="pt_maposter" value="{{ $poster['pt_maposter'] }}">
        <input type="hidden" name="old_img" value="{{ $poster['pt_anhposter'] }}">
        <div class="mb-3">
            <label for="anhPoster" class="form-label">Ảnh poster</label>
            @if($poster['pt_anhposter'])
                <img src="{{ $poster['pt_anhposter'] }}" id="previewImg" style="max-width:150px;max-height:150px;border:1px solid #ccc;margin-bottom:10px;">
            @endif
            <input type="file" class="form-control" id="pt_anhposter" name="anhPoster" />
        </div>
        <div class="text-end">
            <button type="submit" class="btn btn-success">Sửa poster</button>
            <a href="/quan-ly-trang-chu" class="btn btn-secondary ms-2">Hủy</a>
        </div>
    </form>
</div>
@endsection

@section('page-js')
<script src="/static/js/admin/SuaPoster.js"></script>
@endsection