{{-- filepath: d:\Server\ct27501-project-BanhNhatKhang\app\Views\admin-views\QuanLyNhanVien\ThemNhanVien.blade.php --}}
@extends('layouts.admin.master')

@section('title', 'Thêm Nhân Viên')

@section('page-css')
    <link rel="stylesheet" href="/static/css/admin/QuanLyNhanVien.css" />
@endsection

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col">
            <div class="admin-container">
                <div class="admin-header">
                    <h2 class="admin-title">THÊM NHÂN VIÊN MỚI</h2>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/admin">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="/admin/quan-ly-nhan-vien">Quản lý nhân viên</a></li>
                            <li class="breadcrumb-item active">Thêm nhân viên</li>
                        </ol>
                    </nav>
                </div>
                
                <div class="admin-content">
                    @if(isset($_SESSION['error_message']))
                        <div class="alert alert-danger">{{ $_SESSION['error_message'] }}</div>
                    @endif
                    @if(isset($_SESSION['success_message']))
                        <div class="alert alert-success">{{ $_SESSION['success_message'] }}</div>
                        <div class="text-center">
                            <a href="/admin/quan-ly-nhan-vien" class="btn btn-primary">Quay lại danh sách</a>
                        </div>
                    @else
                        <form method="post" action="/admin/them-nhan-vien">
                            <input type="hidden" name="csrf_token" value="{{ $_SESSION['csrf_token'] ?? '' }}">
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Họ và tên (*)</label>
                                    <input type="text" class="form-control" name="ho_ten" 
                                           value="{{ $_POST['ho_ten'] ?? '' }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Email (*)</label>
                                    <input type="email" class="form-control" name="email" 
                                           value="{{ $_POST['email'] ?? '' }}" required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Số điện thoại (*)</label>
                                    <input type="tel" class="form-control" name="so_dien_thoai" 
                                           value="{{ $_POST['so_dien_thoai'] ?? '' }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Chức vụ (*)</label>
                                    <select class="form-select" name="chuc_vu" required>
                                        <option value="">Chọn chức vụ</option>
                                        <option value="Nhân viên" {{ (($_POST['chuc_vu'] ?? '') == 'Nhân viên') ? 'selected' : '' }}>Nhân viên</option>
                                        <option value="Quản lý" {{ (($_POST['chuc_vu'] ?? '') == 'Quản lý') ? 'selected' : '' }}>Quản lý</option>
                                        <option value="Admin" {{ (($_POST['chuc_vu'] ?? '') == 'Admin') ? 'selected' : '' }}>Admin</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Địa chỉ</label>
                                    <input type="text" class="form-control" name="dia_chi" 
                                           value="{{ $_POST['dia_chi'] ?? '' }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Tên đăng nhập (*)</label>
                                    <input type="text" class="form-control" name="username" 
                                           value="{{ $_POST['username'] ?? '' }}" required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Mật khẩu (*)</label>
                                    <input type="password" class="form-control" name="mat_khau" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Xác nhận mật khẩu (*)</label>
                                    <input type="password" class="form-control" name="xac_nhan_mat_khau" required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Trạng thái</label>
                                    <select class="form-select" name="trang_thai">
                                        <option value="1" {{ (($_POST['trang_thai'] ?? '1') == '1') ? 'selected' : '' }}>Hoạt động</option>
                                        <option value="0" {{ (($_POST['trang_thai'] ?? '') == '0') ? 'selected' : '' }}>Ngưng hoạt động</option>
                                    </select>
                                </div>
                            </div>

                            <div class="text-center mt-4">
                                <button type="submit" class="btn btn-success px-4 me-2">THÊM NHÂN VIÊN</button>
                                <a href="/admin/quan-ly-nhan-vien" class="btn btn-secondary px-4">HỦY</a>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('page-js')
    <script src="/static/js/admin/QuanLyNhanVien.js"></script>
@endsection