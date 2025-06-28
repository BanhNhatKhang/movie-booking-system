{{-- filepath: d:\Server\ct27501-project-BanhNhatKhang\app\Views\users-views.ThanhVien.ThanhVien.blade.php --}}
@extends('layouts.users.master')

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
                            <div class="p-2 mb-3 rounded-start-pill shadow-sm" style="background-color: #ff4444;">
                                <li class="list-unstyled">
                                    <a href="/thanh-vien" class="text-decoration-none text-white fw-bold">THÀNH VIÊN KHF CINEMA</a>
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
                            <div class="bg-secondary-subtle p-2 mb-3 rounded-start-pill shadow-sm member-hover">
                                <li class="list-unstyled">
                                    <a href="/qua-tang" class="text-decoration-none text-dark fw-bold">QUÀ TẶNG SINH NHẬT</a>
                                </li>
                            </div>
                        </ul>
                    </div>
                    <div class="col-lg-9">
                        <div class="p-3">
                            <p class="fw-bold fs-4">Chào mừng bạn đến với KHF Cinema Membership – chương trình thành viên đặc biệt dành cho những tín đồ điện ảnh tại hệ thống rạp KHF Cinema!</p>
                            <hr>
                            <p class="fw-bold">1. Hướng dẫn đăng ký thành viên KHF Cinema Membership</p>
                            <p>Tham gia KHF Cinema Membership thật dễ dàng! Bạn có thể đăng ký trực tuyến ngay trên website chính thức của chúng tôi tại www.khfcinema.vn hoặc đến trực tiếp các rạp KHF Cinema để được hỗ trợ đăng ký nhanh chóng.</p>

                            <p class="fw-bold">2. Các hạng thành viên tại KHF Cinema Membership</p>
                            <p>Chúng tôi có 3 hạng thành viên: Thành viên thường (Member), Thành viên Vàng (VIP) và Thành viên Kim Cương (VVIP), mỗi hạng đều được nhận những phần quà và ưu đãi riêng biệt, hấp dẫn.</p>

                            <p class="fw-bold">3. Ưu đãi hấp dẫn khi trở thành thành viên KHF Cinema Membership</p>
                            <p>Khi tham gia chương trình, bạn sẽ được hưởng hàng loạt đặc quyền:</p>
                            <ul>
                                <li>Tích điểm mua vé và bắp nước nhanh chóng, dễ dàng.</li>
                                <li>Đổi điểm lấy vé xem phim, combo bắp nước hoặc các món quà độc quyền chỉ dành riêng cho thành viên KHF Cinema.</li>
                                <li>Nhận quà tặng sinh nhật đầy ý nghĩa từ KHF Cinema.</li>
                                <li>Tham gia các chương trình khuyến mãi và sự kiện riêng biệt, dành riêng cho hội viên của chúng tôi.</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection

@section('page-js')
@endsection
