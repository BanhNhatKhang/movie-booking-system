{{-- filepath: d:\Server\ct27501-project-BanhNhatKhang\app\Views\admin-views\QuanLyPhongGhe\QuanLyPhongGhe.blade.php --}}
@extends('layouts.admin.master')

@section('title', 'Quản lý Ghế phòng chiếu')

@section('page-css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/static/css/admin/LayoutAdmin.css">
    <link rel="stylesheet" href="/static/css/admin/QuanLyPhongGhe.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
@endsection

@section('content')
@php
    $activePage = 'PhongGhe';
@endphp

<!-- Thông báo overlay khi chưa có phòng -->
@if(empty($rooms))
<div class="position-fixed top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center" 
     style="background: rgba(0,0,0,0.7); z-index: 9999;" id="no-rooms-overlay">
    <div class="card shadow-lg border-0" style="max-width: 500px; width: 90%;">
        <div class="card-body text-center p-5">
            <div class="mb-4">
                <i class="bi bi-exclamation-triangle text-warning" style="font-size: 4rem;"></i>
            </div>
            <h3 class="card-title text-dark mb-3">Chưa có phòng chiếu</h3>
            <p class="card-text text-muted mb-4">
                {{ $noRoomsMessage ?? 'Vui lòng thêm phòng chiếu trước khi quản lý ghế.' }}
            </p>
            <div class="d-flex gap-2 justify-content-center">
                <a href="/tao-so-do-ghe" class="btn btn-primary btn-lg">
                    <i class="bi bi-plus-circle"></i> Tạo phòng chiếu
                </a>
                <button class="btn btn-outline-secondary btn-lg" onclick="window.history.back()">
                    <i class="bi bi-arrow-left"></i> Quay lại
                </button>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Nội dung chính -->
<div class="container py-4 content {{ empty($rooms) ? 'opacity-25' : '' }}">
    @if(!empty($rooms))
    <div class="d-flex justify-content-center align-items-center" style="min-height:100vh;">
        <div>
            
            <!-- Chọn phòng -->
            <form class="row g-3 align-items-center mb-4 justify-content-center" method="get" action="">
                <div class="col-auto">
                    <label class="form-label fw-bold">Chọn phòng:</label>
                </div>
                <div class="col-auto">
                    <select name="room" class="form-select" onchange="this.form.submit()">
                        @foreach($rooms as $id => $r)
                            <option value="{{ $id }}"{{ $roomId == $id ? ' selected' : '' }}>
                                {{ $r['name'] }} ({{ $r['type'] }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </form>
            
            <!-- Chỉnh sửa phân loại phòng -->
            <form class="row g-3 align-items-center mb-4 justify-content-center" method="post" action="/update-room-type">
                <input type="hidden" name="room_id" value="{{ $room['id'] ?? '' }}">
                <div class="col-auto">
                    <label class="form-label fw-bold">Phân loại phòng:</label>
                </div>
                <div class="col-auto">
                    <select name="room_type" class="form-select">
                        @foreach($roomTypes as $type)
                            <option value="{{ $type }}"{{ (isset($room['type']) && $room['type'] == $type) ? ' selected' : '' }}>
                                {{ $type }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-auto">
                    <button class="btn btn-primary" type="submit" name="update_room_type">Cập nhật</button>
                </div>
            </form>
            
            <!-- Sơ đồ ghế từ database -->
            @if(isset($room) && !empty($gheListByRoom))
            <!-- Tiêu đề màn hình -->
            <div class="text-center mb-3">
                <div class="bg-dark text-white p-2 rounded d-inline-block">
                    <i class="bi bi-camera-reels"></i> MÀN HÌNH
                </div>
            </div>
            
            <div class="seat-map-demo mb-3 text-center">
                @php
                    //logic group ghế theo hàng
                    $seatsByRow = [];
                    $seatPositions = []; // Track vị trí ghế để sắp xếp
                    
                    foreach($gheListByRoom as $ghe) {
                        // Extract row và column từ mã ghế
                        $seatCode = $ghe['g_maghe'];
                        
                        if (str_contains($seatCode, '_')) {
                            // Format: PC001_A01, PC001_B12
                            $parts = explode('_', $seatCode);
                            $displayCode = end($parts); // A01, B12, etc.
                        } else {
                            // Format: A01, B12 (nếu không có prefix)
                            $displayCode = $seatCode;
                        }
                        
                        // Extract row letter và column number
                        if (preg_match('/^([A-Z])(\d+)$/', $displayCode, $matches)) {
                            $row = $matches[1]; // A, B, C, etc.
                            $column = intval($matches[2]); // 1, 2, 3, etc.
                            
                            if (!isset($seatsByRow[$row])) {
                                $seatsByRow[$row] = [];
                                $seatPositions[$row] = [];
                            }
                            
                            // Store ghế với thông tin vị trí
                            $seatsByRow[$row][] = array_merge($ghe, [
                                'display_code' => $displayCode,
                                'column' => $column
                            ]);
                            
                            // Track position để biết max column
                            $seatPositions[$row][] = $column;
                        }
                    }
                    
                    //  Sắp xếp theo thứ tự A, B, C...
                    ksort($seatsByRow);
                    
                    // Sắp xếp ghế trong mỗi hàng và tính max column RIÊNG BIỆT
                    $maxColumns = []; // Separate array for max columns
                    foreach ($seatsByRow as $row => $seats) {
                        // Chỉ xử lý nếu $seats là array
                        if (is_array($seats)) {
                            // Sort seats by column number
                            usort($seatsByRow[$row], function($a, $b) {
                                return $a['column'] <=> $b['column'];
                            });
                            
                            // Calculate max column cho hàng này
                            if (isset($seatPositions[$row]) && !empty($seatPositions[$row])) {
                                $maxColumns[$row] = max($seatPositions[$row]);
                            } else {
                                $maxColumns[$row] = 12; // default
                            }
                        }
                    }
                @endphp
                
                @forelse($seatsByRow as $row => $seats)
                    {{-- Chỉ xử lý nếu $seats là array --}}
                    @if(is_array($seats))
                        @php
                            $maxColumn = $maxColumns[$row] ?? 12;
                            // Tạo array đầy đủ vị trí từ 1 đến maxColumn
                            $seatGrid = array_fill(1, $maxColumn, null);
                            
                            // Fill ghế vào đúng vị trí
                            foreach($seats as $seat) {
                                if (isset($seat['column']) && $seat['column'] <= $maxColumn) {
                                    $seatGrid[$seat['column']] = $seat;
                                }
                            }
                        @endphp
                        
                        <div class="seat-row mb-2 d-flex justify-content-center align-items-center">
                            {{-- Row label --}}
                            <span class="row-label me-3 fw-bold" style="min-width: 20px;">{{ $row }}</span>
                            
                            {{-- Seat grid with exact columns --}}
                            <div class="d-flex gap-1">
                                @for($col = 1; $col <= $maxColumn; $col++)
                                    @if(isset($seatGrid[$col]) && $seatGrid[$col] !== null)
                                        @php
                                            $seat = $seatGrid[$col];
                                            $class = 'seat-demo seat-label ';
                                            if($seat['g_trangthai'] == 'booked') $class .= 'seat-booked';
                                            elseif($seat['g_trangthai'] == 'selected') $class .= 'seat-selected';
                                            elseif($seat['g_trangthai'] == 'locked') $class .= 'seat-locked';
                                            else $class .= 'seat-' . $seat['g_loaighe'];
                                        @endphp
                                        
                                        <span class="{{ $class }}" 
                                              data-seat="{{ $seat['g_maghe'] }}" 
                                              data-type="{{ $seat['g_loaighe'] }}" 
                                              data-status="{{ $seat['g_trangthai'] }}"
                                              title="{{ $seat['g_maghe'] }} - {{ ucfirst($seat['g_loaighe']) }}">
                                            {{ $seat['display_code'] }}
                                        </span>
                                    @else
                                        {{-- Empty seat position --}}
                                        <span class="seat-demo seat-empty" style="visibility: hidden;">
                                            {{ $row }}{{ str_pad($col, 2, '0', STR_PAD_LEFT) }}
                                        </span>
                                    @endif
                                @endfor
                            </div>
                        </div>
                    @endif
                @empty
                    <div class="alert alert-warning">
                        Không có ghế nào trong phòng {{ $actualRoomCode ?? '' }}
                    </div>
                @endforelse
            </div>
            
            <!-- Action buttons cho sơ đồ ghế -->
            <div class="mb-3 text-center">
                <button class="btn btn-warning btn-sm" id="btn-vip">Đổi sang VIP</button>
                <button class="btn btn-info btn-sm" id="btn-normal">Đổi sang Thường</button>
                <button class="btn btn-purple btn-sm" id="btn-luxury" style="background:#8e24aa;color:#fff;">Đổi sang LUXURY</button>
                <button class="btn btn-secondary btn-sm" id="btn-lock">Khóa ghế</button>
                <button class="btn btn-success btn-sm" id="btn-unlock">Mở khóa ghế</button>
            </div>
            @else
            <!-- Hiển thị khi chưa có ghế -->
            <div class="text-center py-5">
                <i class="bi bi-grid-3x3-gap-fill text-muted" style="font-size: 4rem;"></i>
                <h4 class="mt-3 text-muted">Chưa có sơ đồ ghế</h4>
                <p class="text-muted mb-4">Phòng này chưa có sơ đồ ghế. Hãy tạo sơ đồ ghế để bắt đầu.</p>
                <a href="/tao-so-do-ghe" class="btn btn-primary btn-lg">
                    <i class="bi bi-plus-circle"></i> Tạo sơ đồ ghế
                </a>
            </div>
            @endif
            
            <!-- Chú thích -->
            <div class="mt-4 text-center">
                <h5>Chú thích:</h5>
                <span class="seat-demo seat-normal seat-label">A01</span> Ghế thường
                <span class="seat-demo seat-vip seat-label ms-3">D01</span> Ghế VIP
                <span class="seat-demo seat-luxury seat-label ms-3">H01</span> LUXURY
                <span class="seat-demo seat-booked seat-label ms-3">F06</span> Ghế đã bán
                <span class="seat-demo seat-selected seat-label ms-3">G09</span> Ghế đang chọn
                <span class="seat-demo seat-locked seat-label ms-3">A03</span> Ghế khóa (hư)
            </div>
            
            <!-- Thống kê từ database -->
            <div class="mt-4 text-center">
                <h5>Thống kê</h5>
                <div id="stat">
                    @if(!empty($gheListByRoom))
                        @php
                            $stats = [
                                'normal' => 0,
                                'vip' => 0,
                                'luxury' => 0,
                                'available' => 0,
                                'booked' => 0,
                                'locked' => 0,
                                'total' => count($gheListByRoom)
                            ];
                            
                            foreach($gheListByRoom as $seat) {
                                if (isset($stats[$seat['g_loaighe']])) {
                                    $stats[$seat['g_loaighe']]++;
                                }
                                if (isset($stats[$seat['g_trangthai']])) {
                                    $stats[$seat['g_trangthai']]++;
                                }
                            }
                        @endphp
                        
                        <div class="row text-center">
                            <div class="col-md-2">
                                <div class="badge bg-primary fs-6">{{ $stats['normal'] }}</div>
                                <small class="d-block">Thường</small>
                            </div>
                            <div class="col-md-2">
                                <div class="badge bg-warning fs-6">{{ $stats['vip'] }}</div>
                                <small class="d-block">VIP</small>
                            </div>
                            <div class="col-md-2">
                                <div class="badge bg-info fs-6">{{ $stats['luxury'] }}</div>
                                <small class="d-block">Luxury</small>
                            </div>
                            <div class="col-md-2">
                                <div class="badge bg-success fs-6">{{ $stats['available'] }}</div>
                                <small class="d-block">Có sẵn</small>
                            </div>
                            <div class="col-md-2">
                                <div class="badge bg-danger fs-6">{{ $stats['booked'] }}</div>
                                <small class="d-block">Đã đặt</small>
                            </div>
                            <div class="col-md-2">
                                <div class="badge bg-secondary fs-6">{{ $stats['total'] }}</div>
                                <small class="d-block">Tổng cộng</small>
                            </div>
                        </div>
                    @else
                        <p class="text-muted">Chưa có dữ liệu thống kê</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @else
    <!-- Placeholder khi không có phòng -->
    <div class="d-flex justify-content-center align-items-center" style="min-height:100vh;">
        <div class="text-center text-muted">
            <i class="bi bi-building" style="font-size: 3rem;"></i>
            <p class="mt-3">Giao diện quản lý ghế sẽ hiển thị khi có phòng chiếu</p>
        </div>
    </div>
    @endif
</div>

<!-- Bảng danh sách ghế từ database -->
<div class="container {{ empty($rooms) ? 'opacity-25' : '' }}">
    <div class="table-responsive p-3">
        <!-- Header section với các nút action -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>Danh sách Ghế @if(isset($room)) - {{ $room['name'] }} @endif</h2>
            <div class="btn-group" role="group">
                <a href="/tao-so-do-ghe" class="btn btn-success">
                    <i class="bi bi-plus-circle"></i> Tạo sơ đồ ghế
                </a>
                <button class="btn btn-outline-primary" type="button" data-bs-toggle="collapse" 
                        data-bs-target="#filterCollapse" aria-expanded="false">
                    <i class="bi bi-funnel"></i> Bộ lọc
                </button>
            </div>
        </div>
        
        <!-- Bộ lọc (collapsible) -->
        <div class="collapse mb-3" id="filterCollapse">
            <div class="card card-body">
                <form class="row g-3" method="get">
                    <input type="hidden" name="room" value="{{ $roomId ?? '' }}">
                    <div class="col-md-4">
                        <label class="form-label">Tìm kiếm mã ghế:</label>
                        <input type="text" class="form-control" name="q" placeholder="Nhập mã ghế..." 
                               value="{{ $_GET['q'] ?? '' }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Loại ghế:</label>
                        <select class="form-select" name="loai_ghe">
                            <option value="">Tất cả loại ghế</option>
                            <option value="normal" {{ (isset($_GET['loai_ghe']) && $_GET['loai_ghe'] == 'normal') ? 'selected' : '' }}>Thường</option>
                            <option value="vip" {{ (isset($_GET['loai_ghe']) && $_GET['loai_ghe'] == 'vip') ? 'selected' : '' }}>VIP</option>
                            <option value="luxury" {{ (isset($_GET['loai_ghe']) && $_GET['loai_ghe'] == 'luxury') ? 'selected' : '' }}>Luxury</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Trạng thái:</label>
                        <select class="form-select" name="trang_thai">
                            <option value="">Tất cả trạng thái</option>
                            <option value="available" {{ (isset($_GET['trang_thai']) && $_GET['trang_thai'] == 'available') ? 'selected' : '' }}>Có sẵn</option>
                            <option value="booked" {{ (isset($_GET['trang_thai']) && $_GET['trang_thai'] == 'booked') ? 'selected' : '' }}>Đã đặt</option>
                            <option value="locked" {{ (isset($_GET['trang_thai']) && $_GET['trang_thai'] == 'locked') ? 'selected' : '' }}>Bị khóa</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-flex gap-2">
                            <button class="btn btn-primary" type="submit">
                                <i class="bi bi-search"></i> Lọc
                            </button>
                            <a href="/quan-ly-phong-ghe{{ isset($roomId) ? '?room=' . $roomId : '' }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-counterclockwise"></i>
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Bảng ghế từ database -->
        <table class="table align-middle table-hover">
            <thead class="table-dark">
                <tr>
                    <th>STT</th>
                    <th>Mã ghế</th>
                    <th>Loại ghế</th>
                    <th>Trạng thái</th>
                    <th>Phòng chiếu</th>
                    <th>Chức năng</th>
                </tr>
            </thead>
            <tbody>
                @php
                    // Pagination settings
                    $perPage = 12;
                    $currentPage = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
                    $totalSeats = count($gheList);
                    $totalPages = ceil($totalSeats / $perPage);
                    $offset = ($currentPage - 1) * $perPage;
                    
                    // Get seats for current page
                    $seatsOnPage = array_slice($gheList, $offset, $perPage);
                    
                    // Calculate starting number for STT
                    $startNumber = $offset;
                @endphp
                
                @forelse($seatsOnPage as $index => $ghe)
                <tr>
                    <td>{{ $startNumber + $index + 1 }}</td>
                    <td><strong>{{ $ghe['g_maghe'] }}</strong></td>
                    <td>
                        @if($ghe['g_loaighe'] == 'normal')
                            <span class="badge bg-primary">Thường</span>
                        @elseif($ghe['g_loaighe'] == 'vip')
                            <span class="badge bg-warning text-dark">VIP</span>
                        @elseif($ghe['g_loaighe'] == 'luxury')
                            <span class="badge bg-info">Luxury</span>
                        @else
                            <span class="badge bg-secondary">{{ ucfirst($ghe['g_loaighe']) }}</span>
                        @endif
                    </td>
                    <td>
                        @if($ghe['g_trangthai'] == 'available')
                            <span class="badge bg-success">
                                <i class="bi bi-unlock"></i> Có sẵn
                            </span>
                        @elseif($ghe['g_trangthai'] == 'booked')
                            <span class="badge bg-danger">
                                <i class="bi bi-person-fill"></i> Đã đặt
                            </span>
                        @elseif($ghe['g_trangthai'] == 'locked')
                            <span class="badge bg-secondary">
                                <i class="bi bi-lock"></i> Bị khóa
                            </span>
                        @else
                            <span class="badge bg-dark">{{ ucfirst($ghe['g_trangthai']) }}</span>
                        @endif
                    </td>
                    <td>
                        <code>{{ $ghe['pc_maphongchieu'] }}</code>
                        @if(isset($ghe['pc_tenphong']))
                            <br><small class="text-muted">{{ $ghe['pc_tenphong'] }}</small>
                        @endif
                    </td>
                    <td>
                        @if($ghe['g_trangthai'] == 'locked')
                            <!-- Ghế bị khóa -> Hiển thị nút Mở khóa -->
                            <button class="btn btn-success btn-sm seat-action-btn" 
                                    data-action="unlock" 
                                    data-seat="{{ $ghe['g_maghe'] }}" 
                                    title="Mở khóa ghế">
                                <i class="bi bi-unlock"></i>
                            </button>
                        @else
                            <!-- Ghế không bị khóa -> Hiển thị nút Khóa -->
                            <button class="btn btn-secondary btn-sm seat-action-btn" 
                                    data-action="lock" 
                                    data-seat="{{ $ghe['g_maghe'] }}" 
                                    title="Khóa ghế">
                                <i class="bi bi-lock"></i>
                            </button>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-muted py-4">
                        @if(empty($allGhes))
                            <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                            <strong>Chưa có ghế nào trong hệ thống.</strong>
                            <p class="mb-0">Hãy tạo sơ đồ ghế để bắt đầu quản lý.</p>
                        @else
                            <i class="bi bi-search fs-3 d-block mb-2"></i>
                            <strong>Không tìm thấy ghế nào phù hợp.</strong>
                            <p class="mb-0">Thử thay đổi bộ lọc hoặc từ khóa tìm kiếm.</p>
                        @endif
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        
        <!-- Pagination -->
        @if($totalPages > 1)
        <div class="d-flex justify-content-center align-items-center mt-4">
            
            <!-- Pagination controls -->
            <nav aria-label="Phân trang ghế">
                <ul class="pagination pagination-sm mb-0">
                    <!-- Previous button -->
                    @if($currentPage > 1)
                        <li class="page-item">
                            <a class="page-link" href="{{ buildPaginationUrl($currentPage - 1) }}" 
                               aria-label="Trang trước">
                                <i class="bi bi-chevron-left"></i>
                            </a>
                        </li>
                    @else
                        <li class="page-item disabled">
                            <span class="page-link">
                                <i class="bi bi-chevron-left"></i>
                            </span>
                        </li>
                    @endif
                    
                    <!-- Page numbers -->
                    @php
                        $startPage = max(1, $currentPage - 2);
                        $endPage = min($totalPages, $currentPage + 2);
                        
                        // Adjust if we're near the beginning or end
                        if ($endPage - $startPage < 4) {
                            if ($startPage == 1) {
                                $endPage = min($totalPages, $startPage + 4);
                            } else {
                                $startPage = max(1, $endPage - 4);
                            }
                        }
                    @endphp
                    
                    @if($startPage > 1)
                        <li class="page-item">
                            <a class="page-link" href="{{ buildPaginationUrl(1) }}">1</a>
                        </li>
                        @if($startPage > 2)
                            <li class="page-item disabled">
                                <span class="page-link">...</span>
                            </li>
                        @endif
                    @endif
                    
                    @for($page = $startPage; $page <= $endPage; $page++)
                        @if($page == $currentPage)
                            <li class="page-item active">
                                <span class="page-link">{{ $page }}</span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ buildPaginationUrl($page) }}">{{ $page }}</a>
                            </li>
                        @endif
                    @endfor
                    
                    @if($endPage < $totalPages)
                        @if($endPage < $totalPages - 1)
                            <li class="page-item disabled">
                                <span class="page-link">...</span>
                            </li>
                        @endif
                        <li class="page-item">
                            <a class="page-link" href="{{ buildPaginationUrl($totalPages) }}">{{ $totalPages }}</a>
                        </li>
                    @endif
                    
                    <!-- Next button -->
                    @if($currentPage < $totalPages)
                        <li class="page-item">
                            <a class="page-link" href="{{ buildPaginationUrl($currentPage + 1) }}" 
                               aria-label="Trang sau">
                                <i class="bi bi-chevron-right"></i>
                            </a>
                        </li>
                    @else
                        <li class="page-item disabled">
                            <span class="page-link">
                                <i class="bi bi-chevron-right"></i>
                            </span>
                        </li>
                    @endif
                </ul>
            </nav>
        </div>
        @endif
        
        @php
        // Helper function to build pagination URLs
        function buildPaginationUrl($page) {
            $params = $_GET;
            $params['page'] = $page;
            $queryString = http_build_query($params);
            return '/quan-ly-phong-ghe' . ($queryString ? '?' . $queryString : '');
        }
        @endphp
    </div>
</div>

@endsection

@section('page-js')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    // xử lý khóa/ mở khóa ghế
    $('.seat-action-btn').click(function() {
        const action = $(this).data('action');
        const seatCode = $(this).data('seat');
        const newStatus = action === 'lock' ? 'locked' : 'available';
        
        if (confirm(`Bạn có chắc muốn ${action === 'lock' ? 'khóa' : 'mở khóa'} ghế ${seatCode}?`)) {
            updateSeatStatus(seatCode, newStatus);
        }
    });
    
    // Xử lý tương tác sơ đồ chỗ ngồi
    let selectedSeats = [];
    
    $('.seat-demo').click(function() {
        const seatCode = $(this).data('seat');
        
        if ($(this).hasClass('seat-selected')) {
            $(this).removeClass('seat-selected');
            selectedSeats = selectedSeats.filter(s => s !== seatCode);
        } else if (!$(this).hasClass('seat-booked')) {
            $(this).addClass('seat-selected');
            selectedSeats.push(seatCode);
        }
    });
    
    // xử lý nút hành động
    $('#btn-vip').click(() => updateSelectedSeatsType('vip'));
    $('#btn-normal').click(() => updateSelectedSeatsType('normal'));
    $('#btn-luxury').click(() => updateSelectedSeatsType('luxury'));
    $('#btn-lock').click(() => updateSelectedSeatsStatus('locked'));
    $('#btn-unlock').click(() => updateSelectedSeatsStatus('available'));
    
    function updateSeatStatus(seatCode, status) {
        $.post('/update-seat-status', {
            ma_ghe: seatCode,
            trang_thai: status
        })
        .done(function(response) {
            if (response.success) {
                location.reload(); // Reload để cập nhật giao diện
            } else {
                alert('Lỗi: ' + response.message);
            }
        })
        .fail(function() {
            alert('Có lỗi xảy ra khi cập nhật trạng thái ghế!');
        });
    }
    
    function updateSelectedSeatsType(type) {
        if (selectedSeats.length === 0) {
            alert('Vui lòng chọn ghế trước!');
            return;
        }
        
        const updates = selectedSeats.map(seat => ({
            ma_ghe: seat,
            loai_ghe: type
        }));
        
        $.ajax({
            url: '/bulk-update-seat-types',
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(updates)
        })
        .done(function(response) {
            if (response.success) {
                location.reload();
            } else {
                alert('Lỗi: ' + response.message);
            }
        })
        .fail(function() {
            alert('Có lỗi xảy ra khi cập nhật loại ghế!');
        });
    }
    
    function updateSelectedSeatsStatus(status) {
        if (selectedSeats.length === 0) {
            alert('Vui lòng chọn ghế trước!');
            return;
        }
        
        selectedSeats.forEach(seat => {
            updateSeatStatus(seat, status);
        });
    }
    
    // hàm nhảy trang
    window.jumpToPage = function() {
        const page = parseInt($('#jumpToPage').val());
        
        // tính toán max page
        let maxPage = 1;
        $('.pagination .page-link').each(function() {
            const pageNum = parseInt($(this).text());
            if (!isNaN(pageNum) && pageNum > maxPage) {
                maxPage = pageNum;
            }
        });
        
        if (page >= 1 && page <= maxPage) {
            const currentUrl = new URL(window.location);
            currentUrl.searchParams.set('page', page);
            window.location.href = currentUrl.toString();
        } else {
            alert(`Vui lòng nhập số trang từ 1 đến ${maxPage}`);
            $('#jumpToPage').focus();
        }
    };
    
    // Nhập khóa chuyển trang
    $('#jumpToPage').keypress(function(e) {
        if (e.which == 13) {
            jumpToPage();
        }
    });
    
    // Cập nhật thông tin phân trang sau các hoạt động AJAX
    function updatePagination() {
        // Lấy trang hiện tại từ tham số URL
        const urlParams = new URLSearchParams(window.location.search);
        const currentPage = parseInt(urlParams.get('page')) || 1;
        
        const currentUrl = new URL(window.location);
        currentUrl.searchParams.set('page', currentPage);
        window.location.href = currentUrl.toString();
    }
});
</script>
@endsection