@extends('layouts.admin.master')

@section('title', 'Chi tiết thanh toán')

@section('page-css')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="/static/css/admin/LayoutAdmin.css" rel="stylesheet">
    <link href="/static/css/admin/ChiTietThanhToan.css" rel="stylesheet">
@endsection

@section('content')
<div class="container py-4 content">
    <h1><i class="bi bi-receipt"></i> Chi tiết thanh toán</h1>
    <hr>
    <div class="card mb-4">
        <div class="card-header">
            <i class="bi bi-credit-card"></i> Thông tin giao dịch
        </div>
        <div class="card-body">
            <div class="row mb-2">
                <div class="col-md-4"><span class="info-label">Mã giao dịch:</span> {{ $thanhToan['tt_mathanhtoan'] }}</div>
                <div class="col-md-4"><span class="info-label">Thời gian:</span> {{ date('d/m/Y H:i', strtotime($thanhToan['tt_thoigianthanhtoan'])) }}</div>
                <div class="col-md-4"><span class="info-label">Trạng thái:</span> <span class="badge bg-success">Thành công</span></div>
            </div>
            <div class="row mb-2">
                <div class="col-md-4"><span class="info-label">Phương thức:</span>
                    @php
                        $map = [
                            'momo' => ['MoMo', 'bg-momo'],
                            'cash' => ['Tiền mặt', 'bg-cash'],
                            'vnpay' => ['VN Pay', 'bg-vnpay'],
                            'zalopay' => ['Zalo Pay', 'bg-zalopay'],
                            'bank' => ['Ngân hàng', 'bg-bank']
                        ];
                        $method = $thanhToan['tt_phuongthuc'];
                        $methodName = $map[$method][0] ?? $method;
                        $methodClass = $map[$method][1] ?? 'bg-secondary';
                    @endphp
                    <span class="badge {{ $methodClass }}">
                        {{ $methodName }}
                    </span>
                </div>
                <div class="col-md-4"><span class="info-label">Số tiền:</span> <b>{{ number_format($thanhToan['tt_sotien'], 0, ',', '.') }}đ</b></div>
                <div class="col-md-4"><span class="info-label">Mã vé:</span>
                    @foreach($veList as $ve)
                        <span class="badge bg-info text-dark">{{ $ve['v_mave'] }}</span>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <div class="card mb-4">
        <div class="card-header">
            <i class="bi bi-person"></i> Thông tin người dùng
        </div>
        <div class="card-body">
            <div class="row mb-2">
                <div class="col-md-4"><span class="info-label">Họ tên:</span> {{ $user['nd_hoten'] ?? '' }}</div>
                <div class="col-md-4"><span class="info-label">Email:</span> {{ $user['nd_email'] ?? '' }}</div>
                <div class="col-md-4"><span class="info-label">Số điện thoại:</span> {{ $user['nd_sdt'] ?? '' }}</div>
            </div>
        </div>
    </div>
    <div class="card mb-4">
        <div class="card-header">
            <i class="bi bi-ticket-detailed"></i> Thông tin vé đã mua
        </div>
        <div class="card-body p-0">
            <div class="table-responsive p-3">
                <table class="table align-middle table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Suất chiếu</th>
                            <th>Phim</th>
                            <th>Phòng</th>
                            <th>Ghế</th>
                            <th>Ngày</th>
                            <th>Giờ</th>
                            <th>Giá vé</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($veList as $ve)
                        <tr>
                            <td>{{ $ve['lc_malichchieu'] }}</td>
                            <td>{{ $ve['p_tenphim'] ?? '' }}</td>
                            <td>{{ $ve['pc_tenphong'] ?? '' }}</td>
                            <td>{{ $ve['g_maghe'] }}</td>
                            <td>{{ $ve['lc_ngaychieu'] ?? '' }}</td>
                            <td>
                                @if(isset($ve['lc_giobatdau']))
                                    {{ date('H:i', strtotime($ve['lc_giobatdau'])) }}
                                @endif
                            </td>
                            <td>{{ number_format($ve['v_tongtien'], 0, ',', '.') }}đ</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <a href="/quan-ly-thanh-toan" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Quay lại</a>
</div>
@endsection

@section('page-js')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@endsection