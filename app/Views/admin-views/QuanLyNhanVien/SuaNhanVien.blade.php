{{-- filepath: d:\Server\ct27501-project-BanhNhatKhang\app\Views\admin-views\QuanLyNhanVien\SuaNhanVien.blade.php --}}
@extends('layouts.admin.master')

@section('page-title', 'Sửa Nhân Viên')

@section('page-css')
    <link rel="stylesheet" href="/static/css/admin/QuanLyNhanVien.css" />
@endsection

@section('content')
<main>
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="admin-container">
                    <div class="admin-header">
                        <h2 class="admin-title">SỬA THÔNG TIN NHÂN VIÊN</h2>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="/admin">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="/admin/quan-ly-nhan-vien">Quản lý nhân viên</a></li>
                                <li class="breadcrumb-item active">Sửa nhân viên</li>
                            </ol>
                        </nav>
                    </div>
                    
                    <div class="admin-content">
                        @if(isset($_SESSION['error_message']))
                            <div class="alert alert-danger">{{ $_SESSION['error_message'] }}</div>
                        @endif
                        @if(isset($_SESSION['success_message']))
                            <div class="alert alert-success">{{ $_SESSION['success_message'] }}</div>
                        @endif

                        <form method="post" action="/admin/sua-nhan-vien">
                            <input type="hidden" name="csrf_token" value="{{ $_SESSION['csrf_token'] ?? '' }}">
                            <input type="hidden" name="id" value="{{ $nhanvien['id'] ?? '' }}">
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Họ và tên (*)</label>
                                    <input type="text" class="form-control" name="ho_ten" 
                                           value="{{ $nhanvien['ho_ten'] ?? $_POST['ho_ten'] ?? '' }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Email (*)</label>
                                    <input type="email" class="form-control" name="email" 
                                           value="{{ $nhanvien['email'] ?? $_POST['email'] ?? '' }}" required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Số điện thoại (*)</label>
                                    <input type="tel" class="form-control" name="so_dien_thoai" 
                                           value="{{ $nhanvien['so_dien_thoai'] ?? $_POST['so_dien_thoai'] ?? '' }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Chức vụ (*)</label>
                                    <select class="form-select" name="chuc_vu" required>
                                        <option value="">Chọn chức vụ</option>
                                        <option value="Nhân viên" {{ (($nhanvien['chuc_vu'] ?? $_POST['chuc_vu'] ?? '') == 'Nhân viên') ? 'selected' : '' }}>Nhân viên</option>
                                        <option value="Quản lý" {{ (($nhanvien['chuc_vu'] ?? $_POST['chuc_vu'] ?? '') == 'Quản lý') ? 'selected' : '' }}>Quản lý</option>
                                        <option value="Admin" {{ (($nhanvien['chuc_vu'] ?? $_POST['chuc_vu'] ?? '') == 'Admin') ? 'selected' : '' }}>Admin</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Địa chỉ</label>
                                    <input type="text" class="form-control" name="dia_chi" 
                                           value="{{ $nhanvien['dia_chi'] ?? $_POST['dia_chi'] ?? '' }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Trạng thái</label>
                                    <select class="form-select" name="trang_thai">
                                        <option value="1" {{ (($nhanvien['trang_thai'] ?? $_POST['trang_thai'] ?? '1') == '1') ? 'selected' : '' }}>Hoạt động</option>
                                        <option value="0" {{ (($nhanvien['trang_thai'] ?? $_POST['trang_thai'] ?? '') == '0') ? 'selected' : '' }}>Ngưng hoạt động</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Mật khẩu mới (để trống nếu không đổi)</label>
                                    <input type="password" class="form-control" name="mat_khau_moi">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Xác nhận mật khẩu mới</label>
                                    <input type="password" class="form-control" name="xac_nhan_mat_khau">
                                </div>
                            </div>

                            <div class="text-center mt-4">
                                <button type="submit" class="btn btn-primary px-4 me-2">CẬP NHẬT</button>
                                <a href="/admin/quan-ly-nhan-vien" class="btn btn-secondary px-4">HỦY</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection

@section('page-js')
    <script src="/static/js/admin/QuanLyNhanVien.js"></script>
@endsection
