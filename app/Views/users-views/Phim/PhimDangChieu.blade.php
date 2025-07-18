{{-- filepath: d:\Server\project\app\Views\users-views\Phim\PhimDangChieu.blade.php --}}
@extends('layouts.users.master')

@section('title', 'Phim đang chiếu')
@section('page-css')
    <link rel="stylesheet" href="/static/css/users/PhimDangChieu.css">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
@endsection

@section('content')
<main>
    <div class="container mt-4">
        <div class="bg-light-subtle">
            <div class="row p-2">
                @forelse($phimDangChieu ?? [] as $phim)
                    <div class="col-12 col-sm-6 col-lg-3 mb-4">
                        <div class="movie-card" data-poster="{{ $phim['poster'] ?? '/static/imgs/default-poster.jpg' }}">
                            <div class="movie-header">
                                {{-- ✅ Sử dụng slug thay vì ID --}}
                                <a href="/phim/{{ $phim['slug'] ?? $phim['id'] }}" style="display: block; height: 100%; text-decoration: none;">
                                </a>
                            </div>
                            <div class="movie-content">
                                <div class="movie-title">{{ strtoupper($phim['name'] ?? 'TÊN PHIM KHÔNG XÁC ĐỊNH') }}</div>
                                <div class="movie-buttons">
                                    <span class="btn btn-outline-light btn-sm hover-trailer">Lồng tiếng</span>
                                    {{-- ✅ Sử dụng slug thay vì ID --}}
                                    <a href="/phim/{{ $phim['slug'] ?? $phim['id'] }}" class="btn btn-outline-light btn-sm hover-trailer">Đặt vé</a>
                                </div>
                                <div class="movie-footer">
                                    <p><span class="fw-bold">Thể loại:</span> {{ $phim['genre'] ?? 'Chưa phân loại' }}</p>
                                    <p><span class="fw-bold">Ngày chiếu:</span> 
                                        @if(!empty($phim['release']))
                                            {{ date('d/m/Y', strtotime($phim['release'])) }}
                                        @else
                                            Chưa xác định
                                        @endif
                                    </p>
                                    <p><span class="fw-bold">Đạo diễn:</span> {{ $phim['director'] ?? 'Chưa xác định' }}</p>
                                    
                                    @if(!empty($phim['trailer']))
                                        <button class="openTrailerBtn btn btn-light border hover-trailer btn-sm mt-2" 
                                                data-trailer="{{ $phim['trailer'] }}">
                                            <i class="bi bi-play"></i>
                                            Trailer
                                        </button>
                                    @else
                                        <button class="btn btn-secondary btn-sm mt-2" disabled>
                                            <i class="bi bi-film"></i>
                                            Chưa có trailer
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <!-- Hiển thị khi không có phim -->
                    <div class="col-12">
                        <div class="text-center py-5">
                            <i class="bi bi-film display-1 text-muted"></i>
                            <h3 class="text-muted mt-3">Hiện tại chưa có phim nào đang chiếu</h3>
                            <p class="text-muted">Vui lòng quay lại sau hoặc xem các phim sắp chiếu</p>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Modal trailer đặt ngoài cùng, chỉ 1 lần -->
        <div id="trailerModal" class="modal">
            <div class="modal-content">
                <span id="closeTrailerBtn" class="close">&times;</span>
                <iframe id="trailerVideo" width="100%" height="400px"
                    src=""
                    title="Trailer Video"
                    frameborder="0"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen>
                </iframe>
            </div>
        </div>
    </div>
</main>

<script src="/static/js/users/PhimDangChieu.js"></script>
@endsection