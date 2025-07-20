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
                    <div class="auth-card">
                        <div class="auth-header">
                            <h2 class="auth-title">CẬP NHẬT THÔNG TIN</h2>
                            <h6 class="text-white">VÌ VẤN ĐỀ CHỨNG THỰC PHIỀN BẠN CẬP NHẬT THÔNG TIN CÁ NHÂN</h6>
                        </div>
                        <div class="auth-body">
                            <form method="post" action="/cap-nhat-thong-tin">
                                @php
                                    use App\Core\Csrf;
                                    $csrfToken = Csrf::generateToken();
                                @endphp
                                <input type="hidden" name="csrf_token" value="{{ $csrfToken }}">
                                <div class="mb-3 text-white">
                                    <label class="me-3 mb-0 text-white">Ngày sinh</label>
                                    <input type="date" class="form-control" name="nd_ngaysinh" required value="{{ $_SESSION['form_data']['nd_ngaysinh'] ?? '' }}" />
                                </div>
                                <div class="mb-3 text-white">
                                    <label class="me-3 mb-0 text-white">Giới tính</label>
                                    <div class="mb-3 text-white">
                                        <input type="radio" name="nd_gioitinh" value="1" {{ (($_SESSION['form_data']['nd_gioitinh'] ?? '1') == '1') ? 'checked' : '' }}> Nam
                                        <input type="radio" name="nd_gioitinh" value="0" {{ (($_SESSION['form_data']['nd_gioitinh'] ?? '') == '0') ? 'checked' : '' }}> Nữ
                                    </div>
                                </div>
                                <div class="mb-3 text-white">
                                    <label class="me-3 mb-0 text-white">Số điện thoại</label>
                                    <input type="tel" class="form-control" name="nd_sdt" required value="{{ $_SESSION['form_data']['nd_sdt'] ?? '' }}" />
                                </div>
                                <div class="mb-3 text-white">
                                    <label class="me-3 mb-0 text-white">Căn cước công dân</label>
                                    <input type="text" class="form-control" name="nd_cccd" required value="{{ $_SESSION['form_data']['nd_cccd'] ?? '' }}" />
                                </div>
                                <div class="text-center mt-4">
                                    <button type="submit" class="btn btn-danger px-4 text-white">CẬP NHẬT</button>
                                </div>
                            </form>
                            @php
                                if (isset($_SESSION['form_data'])) unset($_SESSION['form_data']);
                            @endphp
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection