@extends('layouts.users.master')

@section('page-css')
    <link rel="stylesheet" href="/static/css/users/Login.css" />
@endsection

@section('content')
<main>
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="auth-container">
                    <div class="container">
                        <div class="auth-card">
                            <div class="auth-header">
                                <h2 class="auth-title">ĐĂNG KÝ</h2>
                            </div>
                            <div class="auth-body">
                                @if(isset($_SESSION['success_message']))
                                    <div class="alert alert-success text-center mb-3">
                                        <strong>{{ $_SESSION['success_message'] }}</strong>
                                    </div>
                                    @php
                                        unset($_SESSION['success_message']); // Xóa thông báo sau khi hiển thị
                                    @endphp
                                @endif
                                @if(isset($_SESSION['error_message']))
                                    <div class="alert alert-danger text-center mb-3">
                                        <strong>{{ $_SESSION['error_message'] }}</strong>
                                    </div>
                                    @php
                                        unset($_SESSION['error_message']);
                                    @endphp
                                @endif
                                <form method="post" action="/dang-ky">
                                    <input type="hidden" name="csrf_token" value="{{ $_SESSION['csrf_token'] ?? '' }}">
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <input type="text" class="form-control" placeholder="Họ & tên(*)" name="nd_hoten" required value="{{ $_SESSION['form_data']['nd_hoten'] ?? '' }}" />
                                        </div>
                                        <div class="col-md-6">
                                            <input type="date" class="form-control" placeholder="dd/mm/yyyy" name="nd_ngaysinh" required value="{{ $_SESSION['form_data']['nd_ngaysinh'] ?? '' }}" />
                                        </div>
                                    </div>
                                    <div class="row mb-3 align-items-center">
                                        <div class="col-md-6 d-flex align-items-center">
                                            <label class="me-3 mb-0 text-white">Giới tính:</label>
                                            <div class="form-check me-3">
                                                <input class="form-check-input" type="radio" name="nd_gioitinh" id="genderMale" value="1" {{ (($_SESSION['form_data']['nd_gioitinh'] ?? '1') == '1') ? 'checked' : '' }} />
                                                <label class="form-check-label text-white" for="genderMale">Nam</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="nd_gioitinh" id="genderFemale" value="0" {{ (($_SESSION['form_data']['nd_gioitinh'] ?? '') == '0') ? 'checked' : '' }} />
                                                <label class="form-check-label text-white" for="genderFemale">Nữ</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <input type="text" class="form-control" placeholder="CCCD(*)" name="nd_cccd" required value="{{ $_SESSION['form_data']['nd_cccd'] ?? '' }}" />
                                        </div>
                                        <div class="col-md-6">
                                            <input type="text" class="form-control" placeholder="Tên đăng nhập" name="nd_tendangnhap" required value="{{ $_SESSION['form_data']['nd_tendangnhap'] ?? '' }}" />
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <input type="email" class="form-control" placeholder="Email(*)" name="nd_email" required value="{{ $_SESSION['form_data']['nd_email'] ?? '' }}" />
                                        </div>
                                        <div class="col-md-6">
                                            <input type="password" class="form-control" placeholder="Mật khẩu(*)" name="nd_matkhau" required />
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <input type="tel" class="form-control" placeholder="Điện thoại(*)" name="nd_sdt" required value="{{ $_SESSION['form_data']['nd_sdt'] ?? '' }}" />
                                        </div>
                                        <div class="col-md-6">
                                            <input type="password" class="form-control" placeholder="Mật khẩu nhập lại(*)" name="confirm_password" required />
                                        </div>
                                    </div>
                                    <div class="text-center mt-4">
                                        <button type="submit" class="btn btn-danger px-4">ĐĂNG KÝ</button>
                                    </div>
                                    <div class="text-center mt-3">
                                        <p class="text-white">
                                            Đã có tài khoản vui lòng
                                            <a href="/dang-nhap" class="text-danger fw-bold">Đăng nhập!</a>
                                        </p>
                                    </div>
                                </form>
                                @php
                                    // Xóa form_data sau khi hiển thị để tránh hiện lại lần sau
                                    if (isset($_SESSION['form_data'])) {
                                        unset($_SESSION['form_data']);
                                    }
                                @endphp
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection