{{-- filepath: c:\Servers\test\app\Views\admin-views\QuanLyPhim\QuanLyPhim.blade.php --}}
@extends('layouts.admin.master')

@section('title', 'Quản lý Phim')

@section('page-css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/static/css/admin/LayoutAdmin.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
@endsection

@section('content')
<div class="container py-4 content" style="background-color: white;">
    <h1>Quản lý Phim</h1><hr>
    <!-- Bộ lọc và tìm kiếm -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="/them-phim" class="btn btn-primary text-white text-decoration-none">
            <i class="bi bi-plus-circle"></i> Thêm phim mới
        </a>
        <form class="d-flex gap-2 w-75 justify-content-end flex-wrap" method="get">
            <input type="text" class="form-control w-auto" style="min-width:140px;" name="q" placeholder="Nhập tên phim..." value="{{ $_GET['q'] ?? '' }}">
            <select class="form-select w-auto" style="min-width:200px;" name="status">
                <option value="">Tất cả trạng thái</option>
                <option value="showing" {{ (isset($_GET['status']) && $_GET['status'] == 'showing') ? 'selected' : '' }}>Đang chiếu</option>
                <option value="coming" {{ (isset($_GET['status']) && $_GET['status'] == 'coming') ? 'selected' : '' }}>Sắp chiếu</option>
                <option value="ended" {{ (isset($_GET['status']) && $_GET['status'] == 'ended') ? 'selected' : '' }}>Ngưng chiếu</option>
            </select>
            <button class="btn btn-outline-primary" type="submit"><i class="bi bi-search"></i></button>
        </form>
    </div>
    <!-- Danh sách phim -->
    <div class="table-responsive p-3">
        <table class="table align-middle table-hover">
            <thead class="table-dark">
                <tr>
                    <th>Poster</th>
                    <th>Tên phim</th>
                    <th>Thể loại</th>
                    <th>Thời lượng</th>
                    <th>Ngày khởi chiếu</th>
                    <th>Trạng thái</th>
                    <th>Chức năng</th>
                </tr>
            </thead>
            <tbody>
                @php
                    // Dữ liệu mẫu, thay bằng dữ liệu thực tế từ controller
                    $movies = [
                        [
                            'id'=>1,
                            'name'=>'Thanh Gươm Diệt Quỷ',
                            'genre'=>'Hành động, Phiêu lưu',
                            'duration'=>120,
                            'release'=>'2024-06-28',
                            'desc'=>'Một bộ phim hành động hấp dẫn...',
                            'trailer'=>'https://youtube.com/demo',
                            'poster'=>'/static/imgs/demo1.jpg',
                            'status'=>'showing'
                        ],
                        [
                            'id'=>2,
                            'name'=>'Hành Trình Về Miền Đất Hứa',
                            'genre'=>'Tâm lý, Gia đình',
                            'duration'=>105,
                            'release'=>'2024-07-10',
                            'desc'=>'Câu chuyện cảm động về...',
                            'trailer'=>'https://youtube.com/demo2',
                            'poster'=>'/static/imgs/demo2.jpg',
                            'status'=>'coming'
                        ],
                        [
                            'id'=>3,
                            'name'=>'Ký Ức Mùa Hè',
                            'genre'=>'Tình cảm',
                            'duration'=>98,
                            'release'=>'2024-05-01',
                            'desc'=>'Một mùa hè không thể quên...',
                            'trailer'=>'https://youtube.com/demo3',
                            'poster'=>'/static/imgs/demo3.jpg',
                            'status'=>'ended'
                        ],
                    ];
                        $q = isset($_GET['q']) ? mb_strtolower(trim($_GET['q'])) : '';
                        $status = isset($_GET['status']) ? $_GET['status'] : '';
                        $filtered = collect($movies)->filter(function($m) use ($q, $status) {
                            $ok = true;
                            if ($q && mb_strpos(mb_strtolower($m['name']), $q) === false) $ok = false;
                            if ($status && $m['status'] != $status) $ok = false;
                            return $ok;
                        });
                @endphp
                @forelse($filtered as $movie)
                <tr>
                    <td><img src="{{ $movie['poster'] }}" class="movie-poster" alt="Poster"></td>
                    <td>
                        <strong>{{ $movie['name'] }}</strong>
                        <div>
                            <a href="{{ $movie['trailer'] }}" target="_blank" class="text-secondary small">Trailer</a>
                        </div>
                    </td>
                    <td>{{ $movie['genre'] }}</td>
                    <td>{{ $movie['duration'] }} phút</td>
                    <td>{{ \Carbon\Carbon::parse($movie['release'])->format('d/m/Y') }}</td>
                    <td>
                        @if($movie['status']=='showing')
                            <span class="badge bg-success">Đang chiếu</span>
                        @elseif($movie['status']=='coming')
                            <span class="badge bg-warning text-dark">Sắp chiếu</span>
                        @else
                            <span class="badge bg-danger">Ngưng chiếu</span>
                        @endif
                    </td>
                    <td>
                        <a href="/sua-phim?id={{ $movie['id'] }}" class="btn btn-warning btn-sm action-btn" title="Sửa"><i class="bi bi-pencil-square"></i></a>
                        <a href="/xoa-phim?id={{ $movie['id'] }}" class="btn btn-danger btn-sm action-btn" title="Xóa" onclick="return confirm('Bạn chắc chắn muốn xóa phim này?')"><i class="bi bi-trash"></i></a>
                        <a href="/doi-trang-thai-phim?id={{ $movie['id'] }}" class="btn btn-secondary btn-sm action-btn" title="Đổi trạng thái"><i class="bi bi-arrow-repeat"></i></a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted">Không tìm thấy phim phù hợp.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection