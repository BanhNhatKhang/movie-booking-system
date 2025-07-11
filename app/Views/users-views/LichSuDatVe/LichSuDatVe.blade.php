{{-- filepath: d:\Server\ct27501-project-BanhNhatKhang\app\Views\users-views.LichSuDatVe.LichSuDatVe.blade.php --}}
@extends('layouts.users.master')

@section('page-css')
    <link rel="stylesheet" href="/static/css/users/ThongTinCaNhan.css">
@endsection

@section('content')
<main>
  <div class="container py-4">
    <div class="card shadow-sm rounded-4 p-4">
      <h4 class="fw-bold mb-4 text-danger">Lịch sử đặt vé</h4>
      <div class="table-responsive">
        <table class="table table-bordered align-middle table-hover mb-0 rounded-4 overflow-hidden">
          <thead class="custom-thead text-white text-center">
            <tr>
              <th>STT</th>
              <th>Ngày đặt</th>
              <th>Tên phim</th>
              <th>Suất chiếu</th>
              <th>Ghế</th>
              <th>Phòng</th>
              <th>Giá tiền</th>
              <th>Điểm tích lũy</th>
              <th>Mã vé</th>
              <th>Trạng thái vé</th>
              <th>QR vé</th> <!-- Thêm cột này -->
            </tr>
          </thead>
          <tbody>
            @forelse($tickets as $i => $ticket)
                @php
                    $hang = $user['nd_loaithanhvien'] ?? 'bac';
                    $tyle = match($hang) {
                        'vang' => 0.07,
                        'kimcuong' => 0.10,
                        default => 0.05
                    };
                    $diemTichLuy = floor(round($ticket['v_tongtien'] * $tyle) / 1000);
                @endphp
                <tr>
                    <td class="text-center">{{ $i + 1 }}</td>
                    <td>{{ date('d/m/Y', strtotime($ticket['v_ngaydat'])) }}</td>
                    <td>{{ $ticket['p_tenphim'] }}</td>
                    <td>{{ date('H:i - d/m/Y', strtotime($ticket['lc_giobatdau'])) }}</td>
                    <td>
                        {{ isset($ticket['g_maghe']) ? preg_replace('/^.*_/', '', $ticket['g_maghe']) : '' }}
                    </td>
                    <td class="text-center">{{ $ticket['pc_tenphong'] }}</td>
                    <td class="text-success fw-semibold">{{ number_format($ticket['v_tongtien']) }}đ</td>
                    <td class="text-danger fw-semibold text-center">{{ $diemTichLuy }}</td>
                    <td class="text-center">{{ $ticket['v_mave'] }}</td>
                    <td class="text-center">
                        @if($ticket['v_trangthai'] === 'da_in')
                            <span class="badge bg-success">Đã in</span>
                        @else
                            <span class="badge bg-secondary">Chưa in</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <a href="#" data-bs-toggle="modal" data-bs-target="#qrModal{{ $ticket['v_mave'] }}">
                            <i class="bi bi-qr-code-scan fs-4"></i>
                        </a>
                        <!-- Modal QR -->
                        <div class="modal fade" id="qrModal{{ $ticket['v_mave'] }}" tabindex="-1" aria-labelledby="qrModalLabel{{ $ticket['v_mave'] }}" aria-hidden="true">
                          <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content p-3 text-center">
                              <div class="modal-header border-0">
                                <h5 class="modal-title w-100" id="qrModalLabel{{ $ticket['v_mave'] }}">QR vé: {{ $ticket['v_mave'] }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                              </div>
                              <div class="modal-body">
                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data={{ urlencode($ticket['v_mave']) }}" alt="QR vé {{ $ticket['v_mave'] }}">
                                <div class="mt-2 text-muted small">{{ $ticket['v_mave'] }}</div>
                              </div>
                            </div>
                          </div>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="11" class="text-center text-muted">Bạn chưa có vé nào.</td>
                </tr>
            @endforelse
          </tbody>
        </table>
      </div>
      <?php if($totalPages > 1): ?>
      <nav>
        <ul class="pagination justify-content-center mt-3">
          <?php for($i = 1; $i <= $totalPages; $i++): ?>
            <li class="page-item <?= $i == $page ? 'active' : '' ?>">
              <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
            </li>
          <?php endfor; ?>
        </ul>
      </nav>
      <?php endif; ?>
    </div>
  </div>
</main>
@endsection

@section('page-js')
@endsection
