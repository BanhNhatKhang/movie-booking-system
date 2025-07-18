{{-- filepath: d:\Server\project\app\Views\users-views\Login\DangNhap.blade.php --}}
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
                                <h2 class="auth-title">ĐĂNG NHẬP</h2>
                            </div>
                            <div class="auth-body">
                                {{-- Thông báo thành công từ session --}}
                                @if(isset($_SESSION['success_message']))
                                    <div class="alert alert-success text-center mb-3">
                                        <strong>{{ $_SESSION['success_message'] }}</strong>
                                    </div>
                                    @php
                                        unset($_SESSION['success_message']);
                                    @endphp
                                @endif
                                
                                {{-- Thông báo lỗi từ session --}}
                                @if(isset($_SESSION['error_message']))
                                    <div class="alert alert-danger text-center mb-3">
                                        <strong>{{ $_SESSION['error_message'] }}</strong>
                                    </div>
                                    @php
                                        unset($_SESSION['error_message']);
                                    @endphp
                                @endif
                                
                                {{-- Thông báo lỗi từ controller (biến) --}}
                                @if(isset($error_message) && $error_message)
                                    <div class="alert alert-danger text-center">{{ htmlspecialchars($error_message) }}</div>
                                @endif
                                
                                <form method="post" action="/dang-nhap">
                                    {{-- CSRF Token được generate động --}}
                                    @php
                                        use App\Core\Csrf;
                                        $csrfToken = Csrf::generateToken();
                                    @endphp
                                    <input type="hidden" name="csrf_token" value="{{ $csrfToken }}">
                                    
                                    <div class="mb-3">
                                        <input
                                            type="text"
                                            class="form-control"
                                            placeholder="Email / Tên đăng nhập"
                                            name="username"
                                            required
                                        />
                                    </div>
                                    <div class="mb-3">
                                        <input
                                            type="password"
                                            class="form-control"
                                            placeholder="Mật khẩu"
                                            name="password"
                                            required
                                        />
                                    </div>
                                    <div class="mb-3 form-check">
                                        <input
                                            type="checkbox"
                                            class="form-check-input"
                                            id="rememberMe"
                                            name="remember"
                                        />
                                        <label class="form-check-label text-white" for="rememberMe">
                                            Ghi nhớ đăng nhập
                                        </label>
                                    </div>
                                    <div class="text-center mt-4">
                                        <button type="submit" class="btn btn-danger px-4">
                                            ĐĂNG NHẬP
                                        </button>
                                    </div>
                                    <div class="text-center mt-3">
                                        <p class="text-white">
                                            Chưa có tài khoản?
                                            <a href="/dang-ky" class="text-danger fw-bold">Đăng ký!</a>
                                        </p>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
