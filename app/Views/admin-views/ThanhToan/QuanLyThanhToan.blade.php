@extends('layouts.admin.master')

@section('title', 'Quản lý Thanh toán')

@section('page-css')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="/static/css/admin/LayoutAdmin.css" rel="stylesheet">
    <style>
        .bg-momo { background: #a50064 !important; color: #fff !important; }
        .bg-vnpay { background: #0060af !important; color: #fff !important; }
        .bg-zalopay { background: #00b4f3 !important; color: #fff !important; }
        .bg-bank { background: #28a745 !important; color: #fff !important; }
        .bg-cash { background: #6c757d !important; color: #fff !important; }
    </style>
@endsection

@section('content')
<div class="container py-4 content">
    <h1>Quản lý Thanh toán</h1>
    <hr>

    {{-- Thống kê --}}
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-bg-primary mb-3">
                <div class="card-body text-center">
                    <div class="fs-4 fw-bold">{{ $stats['total_month'] ?? 0 }}</div>
                    <div>Tổng giao dịch tháng {{ date('m/Y') }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-bg-success mb-3">
                <div class="card-body text-center">
                    <div class="fs-4 fw-bold">{{ number_format($stats['total_amount_month'] ?? 0, 0, ',', '.') }}đ</div>
                    <div>Tổng số tiền tháng {{ date('m/Y') }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-bg-warning mb-3">
                <div class="card-body text-center">
                    <div class="fs-4 fw-bold">{{ $stats['total_today'] ?? 0 }}</div>
                    <div>Giao dịch hôm nay</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-bg-danger mb-3">
                <div class="card-body text-center">
                    <div class="fs-4 fw-bold">{{ number_format($stats['total_amount_today'] ?? 0, 0, ',', '.') }}đ</div>
                    <div>Số tiền hôm nay ({{ date('d/m/Y') }})</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Form tìm kiếm --}}
    <form class="row g-3 align-items-end mb-3" method="get">
        <div class="col-md-3">
            <label class="form-label">Tìm theo tên khách hàng</label>
            <input type="text" class="form-control" name="search"
                   value="{{ $_GET['search'] ?? '' }}"
                   placeholder="Nhập tên khách hàng...">
        </div>
        <div class="col-md-3">
            <label class="form-label">Phương thức thanh toán</label>
            <select class="form-select" name="payment_method">
                <option value="">Tất cả</option>
                <option value="momo" {{ ($_GET['payment_method'] ?? '')=='momo' ? 'selected' : '' }}>MoMo</option>
                <option value="cash" {{ ($_GET['payment_method'] ?? '')=='cash' ? 'selected' : '' }}>Tiền mặt</option>
                <option value="vnpay" {{ ($_GET['payment_method'] ?? '')=='vnpay' ? 'selected' : '' }}>VN Pay</option>
                <option value="zalopay" {{ ($_GET['payment_method'] ?? '')=='zalopay' ? 'selected' : '' }}>Zalo Pay</option>
                <option value="bank" {{ ($_GET['payment_method'] ?? '')=='bank' ? 'selected' : '' }}>Ngân hàng</option>
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100"><i class="bi bi-search"></i> Tìm kiếm</button>
        </div>
        <div class="col-md-2">
            <a href="/quan-ly-thanh-toan" class="btn btn-secondary w-100"><i class="bi bi-x-circle"></i> Xóa lọc</a>
        </div>
    </form>

    <div class="table-responsive p-3">
        <table class="table align-middle table-hover">
            <thead class="table-dark">
                <tr>
                    <th>STT</th>
                    <th>Mã GD</th>
                    <th>Khách hàng</th>
                    <th>Số tiền</th>
                    <th>Phương thức</th>
                    <th>Thời gian</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse($list as $i => $tt)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $tt['tt_mathanhtoan'] }}</td>
                    <td>
                        {{ $tt['nd_hoten'] ?? '---' }}<br>
                        <small class="text-muted">{{ $tt['nd_email'] ?? '' }}</small>
                    </td>
                    <td>{{ number_format($tt['tt_sotien'], 0, ',', '.') }}đ</td>
                    <td>
                        @php
                            $methodMap = [
                                'momo' => ['MoMo', 'bg-momo'],
                                'cash' => ['Tiền mặt', 'bg-cash'],
                                'vnpay' => ['VN Pay', 'bg-vnpay'],
                                'zalopay' => ['Zalo Pay', 'bg-zalopay'],
                                'bank' => ['Ngân hàng', 'bg-bank'],
                            ];
                            $method = $tt['tt_phuongthuc'];
                            $methodName = $methodMap[$method][0] ?? $method;
                            $methodClass = $methodMap[$method][1] ?? 'bg-secondary';
                        @endphp
                        <span class="badge {{ $methodClass }}">
                            {{ $methodName }}
                        </span>
                    </td>
                    <td>{{ date('d/m/Y H:i', strtotime($tt['tt_thoigianthanhtoan'])) }}</td>
                    <td class="text-center">
                        <a href="/chi-tiet-thanh-toan?id={{ $tt['tt_mathanhtoan'] }}" class="btn btn-info btn-sm" title="Chi tiết">
                            <i class="bi bi-eye"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted">Chưa có giao dịch nào.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @include('layouts.admin.Pagination', [
        'currentPage' => $currentPage ?? 1,
        'totalPages' => $totalPages ?? 1,
        'totalItems' => $totalItems ?? 0,
        'itemsPerPage' => $itemsPerPage ?? 10,
        'itemName' => $itemName ?? 'giao dịch'
    ])
</div>
@endsection

@section('page-js')
@endsection