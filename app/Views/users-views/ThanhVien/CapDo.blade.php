{{-- filepath: resources/views/users-views/ThanhVien/CapDo.blade.php --}}
@extends('layouts.users.master')
@section('title', 'Cấp độ thành viên')
@section('page-css')
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
                            <div class="p-2 mb-3 rounded-start-pill shadow-sm" style="background-color: #ff4444;">
                                <li class="list-unstyled">
                                    <a href="#" class="text-decoration-none text-white fw-bold">CẤP ĐỘ THÀNH VIÊN</a>
                                </li>
                            </div>
                            <div class="bg-secondary-subtle p-2 mb-3 rounded-start-pill shadow-sm member-hover">
                                <li class="list-unstyled">
                                    <a href="/qua-tang" class="text-decoration-none text-dark fw-bold">QUÀ TẶNG SINH NHẬT</a>
                                </li>
                            </div>
                        </ul>
                    </div>
                    <div class="col-lg-9">
                        <div class="p-3">
                            <h1>Chương trình Thành viên KHF Cinema</h1><br>

                            <div class="card">
                                <h2>1. Hạng KHF Bạc</h2>
                                <p>Tất cả khách hàng từ 12 tuổi trở lên khi đăng ký tham gia chương trình sẽ trở thành thành viên KHF Member.</p>
                                <p><strong>Quyền lợi:</strong></p>
                                <ul>
                                    <li>Tích điểm: 5% tại quầy vé, 2% tại quầy thực phẩm.</li>
                                    <li>Đổi điểm lấy vé xem phim, combo hoặc quà tặng tại KHF.</li>
                                    <li>Quà sinh nhật: 01 combo đôi (1 phần bắp + 2 ly nước).</li>
                                    <li>Tham gia các chương trình khuyến mãi dành riêng cho thành viên.</li>
                                </ul>
                            </div>

                            <div class="card mt-4">
                                <h2>2. Hạng KHF Vàng</h2>
                                <p>Thành viên có tổng chi tiêu từ 2.000.000 đến 3.999.999 VNĐ sẽ được nâng cấp lên KHF VIP.</p>
                                <p><strong>Quyền lợi:</strong></p>
                                <ul>
                                    <li>Tăng tỷ lệ tích điểm: 7% tại quầy vé, 3% tại quầy thực phẩm.</li>
                                    <li>Ưu đãi tặng thêm:
                                        <ul>
                                            <li>02 vé xem phim 2D miễn phí.</li>
                                        </ul>
                                    </li>
                                    <li>Quà sinh nhật:
                                        <ul>
                                            <li>01 combo đôi (1 phần bắp + 2 nước).</li>
                                            <li>01 vé xem phim 2D.</li>
                                        </ul>
                                    </li>
                                    <li>Ưu tiên tham gia các sự kiện đặc biệt do KHF tổ chức.</li>
                                </ul>
                            </div>

                            <div class="card mt-4">
                                <h2>3. Hạng KHF Kim cương</h2>
                                <p>Thành viên có tổng chi tiêu từ 4.000.000 VNĐ trở lên sẽ được nâng cấp lên KHF VVIP.</p>
                                <p><strong>Quyền lợi:</strong></p>
                                <ul>
                                    <li>Tích điểm tối đa: 10% tại quầy vé, 5% tại quầy thực phẩm.</li>
                                    <li>Ưu đãi tặng thêm:
                                        <ul>
                                            <li>04 vé xem phim 2D miễn phí.</li>
                                        </ul>
                                    </li>
                                    <li>Quà sinh nhật:
                                        <ul>
                                            <li>01 combo đôi (1 phần bắp + 2 nước).</li>
                                            <li>02 vé xem phim 2D.</li>
                                        </ul>
                                    </li>
                                    <li>Ưu tiên đặc biệt trong các sự kiện tri ân khách hàng của KHF.</li>
                                </ul>
                            </div>

                            <div class="note mt-4">
                                <h3>Lưu ý</h3>
                                <ul>
                                    <li>Chi tiêu được tính theo năm (từ 01/01 đến 31/12).</li>
                                    <li>Hạng thành viên sẽ có hiệu lực trong suốt năm kế tiếp (01 năm).</li>
                                    <li>Các vé xem phim tặng không áp dụng vào ngày Lễ, Tết hoặc suất chiếu đặc biệt.</li>
                                    <li>Rạp KHF không có chi nhánh và không có phòng Premium – ưu đãi chỉ áp dụng tại rạp hiện tại.</li>
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
    {{-- Nếu cần script riêng cho trang này --}}
@endsection
