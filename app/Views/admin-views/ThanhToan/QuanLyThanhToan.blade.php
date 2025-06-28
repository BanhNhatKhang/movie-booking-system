@extends('layouts.admin.master')

@section('title', 'Quản lý Thanh toán')

@section('page-css')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="/static/css/admin/LayoutAdmin.css" rel="stylesheet">
@endsection

@section('content')
<div class="container py-4 content">
    <h1>Quản lý Thanh toán</h1>
    <hr>
    <form class="row g-2 mb-3">
        <div class="col-md-2">
            <input type="date" class="form-control" placeholder="Từ ngày">
        </div>
        <div class="col-md-2">
            <input type="date" class="form-control" placeholder="Đến ngày">
        </div>
        <div class="col-md-2">
            <select class="form-select">
                <option value="">Tất cả phương thức</option>
                <option>VNPay</option>
                <option>Momo</option>
                <option>Tiền mặt</option>
            </select>
        </div>
        <div class="col-md-2">
            <input type="text" class="form-control" placeholder="Tìm mã vé, mã GD">
        </div>
        <div class="col-md-2">
            <button class="btn btn-outline-primary"><i class="bi bi-search"></i></button>
        </div>
    </form>
    <div class="row mb-3">
        <div class="col">
            <div class="bg-success text-white rounded p-2 text-center">
                Tổng doanh thu ngày: <b>30,000,000đ</b>
            </div>
        </div>
        <div class="col">
            <div class="bg-info text-white rounded p-2 text-center">
                Giao dịch thành công: <b>210</b>
            </div>
        </div>
    </div>
    <div class="table-responsive p-3">
        <table class="table align-middle table-hover">
            <thead class="table-dark">
                <tr>
                    <th>STT</th>
                    <th>Mã GD</th>
                    <th>Mã vé</th>
                    <th>Khách hàng</th>
                    <th>Số tiền</th>
                    <th>Phương thức</th>
                    <th>Thời gian</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>GD123456</td>
                    <td>VE4567</td>
                    <td>Nguyễn Văn A</td>
                    <td>120.000đ</td>
                    <td>VNPay</td>
                    <td>2025-06-15 09:30</td>
                    <td>
                        <a href="/chi-tiet-thanh-toan?id=GD123456" class="btn btn-info btn-sm" title="Chi tiết">
                            <i class="bi bi-eye"></i>
                        </a>
                    </td>
                </tr>
                {{-- Thêm các dòng dữ liệu khác tại đây --}}
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('page-js')
@endsection