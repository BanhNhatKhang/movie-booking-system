{{-- filepath: c:\Servers\test\app\Views\admin-views\QuanLyDonDatVe\ChiTietDon.blade.php --}}
@extends('layouts.admin.master')

@section('title', 'Chi tiết đơn đặt vé')

@section('page-css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/static/css/admin/LayoutAdmin.css">
    <link rel="stylesheet" href="/static/css/admin/ChiTietDon.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
@endsection

@section('content')
@php
    // Dữ liệu mẫu, thực tế lấy từ DB
    $orders = [
        [
            'id'=>1001,
            'user'=>'Nguyễn Văn B',
            'movie'=>'Thanh Gươm Diệt Quỷ',
            'showtime'=>'08:30 25/06/2024',
            'seats'=>'G09, G10',
            'price'=>170000,
            'date'=>'2024-06-20',
            'status'=>'paid'
        ],
        [
            'id'=>1002,
            'user'=>'Trần Thị C',
            'movie'=>'Hành Trình Về Miền Đất Hứa',
            'showtime'=>'10:00 26/06/2024',
            'seats'=>'A01',
            'price'=>70000,
            'date'=>'2024-06-21',
            'status'=>'unpaid'
        ],
        [
            'id'=>1003,
            'user'=>'Lê Văn D',
            'movie'=>'Ký Ức Mùa Hè',
            'showtime'=>'14:00 22/06/2024',
            'seats'=>'B05, B06, B07',
            'price'=>210000,
            'date'=>'2024-06-19',
            'status'=>'cancelled'
        ],
    ];
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    $order = null;
    foreach($orders as $o) {
        if($o['id'] == $id) {
            $order = $o;
            break;
        }
    }
@endphp

<div class="container py-4 content">
    <div>
        @if($order)
            <h1 class="mb-4">Chi tiết vé #{{ $order['id'] }}</h1>
            <div class="mb-3 row">
                <div class="order-label col-sm-4">Người dùng:</div>
                <div class="order-value col-sm-8">{{ $order['user'] }}</div>
            </div>
            <div class="mb-3 row">
                <div class="order-label col-sm-4">Tên phim:</div>
                <div class="order-value col-sm-8">{{ $order['movie'] }}</div>
            </div>
            <div class="mb-3 row">
                <div class="order-label col-sm-4">Suất chiếu:</div>
                <div class="order-value col-sm-8">{{ $order['showtime'] }}</div>
            </div>
            <div class="mb-3 row">
                <div class="order-label col-sm-4">Ghế:</div>
                <div class="order-value col-sm-8">{{ $order['seats'] }}</div>
            </div>
            <div class="mb-3 row">
                <div class="order-label col-sm-4">Giá:</div>
                <div class="order-value col-sm-8">{{ number_format($order['price'],0,',','.') }} VNĐ</div>
            </div>
            <div class="mb-3 row">
                <div class="order-label col-sm-4">Ngày đặt:</div>
                <div class="order-value col-sm-8">{{ date('d/m/Y', strtotime($order['date'])) }}</div>
            </div>
            <div class="mb-3 row">
                <div class="order-label col-sm-4">Thanh toán:</div>
                <div class="order-value col-sm-8">
                    @if($order['status']=='paid')
                        <span class="badge status-paid">Đã thanh toán</span>
                    @elseif($order['status']=='unpaid')
                        <span class="badge status-unpaid">Chưa thanh toán</span>
                    @else
                        <span class="badge status-cancelled">Đã hủy</span>
                    @endif
                </div>
            </div>
            <div class="mt-4">
                <a href="/quan-ly-don-dat-ve" class="btn btn-secondary">Quay lại</a>
                @if($order['status']!='cancelled')
                <a href="/huy-don?id={{ $order['id'] }}" class="btn btn-danger" onclick="return confirm('Bạn chắc chắn muốn hủy vé này?')">Hủy vé</a>
                @endif
                <a href="/xuat-ve-word?id={{ $order['id'] }}" class="btn btn-success">Xuất vé Word</a>
            </div>
        @else
            <div class="alert alert-danger text-center">
                Không tìm thấy đơn đặt vé!
            </div>
            <div class="mt-4 text-center">
                <a href="/quan-ly-don-dat-ve" class="btn btn-secondary">Quay lại</a>
            </div>
        @endif
    </div>
</div>
@endsection