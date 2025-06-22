{{-- filepath: c:\Servers\test\app\Views\admin-views\QuanLyPhim\DoiTrangThaiPhim.blade.php --}}
@extends('layouts.admin.master')

@section('title', 'Đổi trạng thái phim')

@section('content')
<div class="container mt-5">
    <div class="alert alert-info">
        Đang xử lý đổi trạng thái phim...
    </div>
</div>
<meta http-equiv="refresh" content="1;url=/quan-ly-phim?msg=status_success">
@endsection