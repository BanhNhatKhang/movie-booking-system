{{-- filepath: resources/views/users-views/ThanhVien/QuaTang.blade.php --}}
@extends('layouts.users.master')
@section('title', 'Quà tặng sinh nhật')
@section('page-css')
    <link rel="stylesheet" href="/static/css/users/HeaderFooter.css">
    <link rel="stylesheet" href="/static/css/users/ThanhVien.css">
@endsection

@section('content')
<main>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="row bg-body m-0">
                    <div class="col-lg-3">
                        <ul class="py-3 px-2 m-0">
                            <div class="bg-secondary-subtle p-2 mb-3 rounded-start-pill shadow-sm member-hover">
                                <li class="list-unstyled">
                                    <a href="/thanh-vien" class="text-decoration-none text-dark fw-bold">THÀNH VIÊN KHF CINEMA</a>
                                </li>
                            </div>
                            <div class="bg-secondary-subtle p-2 mb-3 rounded-start-pill shadow-sm member-hover">
                                <li class="list-unstyled">
                                    <a href="/diem-thuong" class="text-decoration-none text-dark fw-bold">ĐIỂM THƯỞNG TÍCH LŨY</a>
                                </li>
                            </div>
                            <div class="bg-secondary-subtle p-2 mb-3 rounded-start-pill shadow-sm member-hover">
                                <li class="list-unstyled">
                                    <a href="/cap-do" class="text-decoration-none text-dark fw-bold">CẤP ĐỘ THÀNH VIÊN</a>
                                </li>
                            </div>
                            <div class="p-2 mb-3 rounded-start-pill shadow-sm" style="background-color: #ff4444;">
                                <li class="list-unstyled">
                                    <a href="#" class="text-decoration-none text-white fw-bold">QUÀ TẶNG SINH NHẬT</a>
                                </li>
                            </div>
                        </ul>
                    </div>
                    <div class="col-lg-9">
                        <div class="p-3">
                            <h1>🎉 Ưu Đãi Sinh Nhật Từ KHF Cinema 🎉</h1><br>

                            <div class="card">
                                <h2>🎁 Quà Tặng Theo Cấp Độ</h2>
                                <ul>
                                    <li><strong>KHF Bạc:</strong> 01 Combo đôi (1 phần bắp + 2 ly nước)</li>
                                    <li><strong>KHF Vàng:</strong> 01 Combo đôi + 01 vé xem phim 2D miễn phí</li>
                                    <li><strong>KHF Kim cương:</strong> 01 Combo đôi + 02 vé xem phim 2D miễn phí</li>
                                </ul>
                            </div>

                            <div class="note mt-4">
                                <h3>📌 Điều Kiện Nhận Quà</h3>
                                <ul>
                                    <li>Chỉ áp dụng trong <strong>tháng sinh nhật</strong> của thành viên</li>
                                    <li>Phải có ít nhất <strong>01 giao dịch</strong> bất kỳ trước tháng sinh nhật</li>
                                    <li>Tài khoản thành viên được tạo ít nhất <strong>72 giờ</strong> trước khi đổi quà</li>
                                    <li>Có ít nhất 01 giao dịch trước <strong>48 giờ</strong> so với thời điểm nhận quà</li>
                                    <li>Vui lòng xuất trình <strong>thẻ thành viên hoặc CCCD</strong> để xác nhận</li>
                                    <li>Vé miễn phí không áp dụng vào các ngày <strong>Lễ, Tết, suất chiếu sớm hoặc đặc biệt</strong></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection

@section('page-js')
{{-- Nếu có thêm script riêng thì thêm ở đây --}}
@endsection
