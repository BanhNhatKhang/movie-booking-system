{{-- filepath: d:\Server\ct27501-project-BanhNhatKhang\app\Views\users-views\Phim\PhimSapChieu.blade.php --}}
@extends('layouts.users.master')

@section('page-css')
    <link rel="stylesheet" href="/static/css/users/PhimDangChieu.css">
@endsection

@section('content')
<main>
    <div class="container mt-4">
        <div class="bg-light-subtle">
            <div class="row p-2">
                @forelse($phimSapChieu ?? [] as $phim)
                    <div class="col-12 col-sm-6 col-lg-3 mb-4">
                    <div class="movie-card" data-poster="{{ $phim['poster'] ?? '/static/imgs/default-poster.jpg' }}">
                            <div class="movie-header">
                                <a href="/chi-tiet-phim?id={{ $phim['id'] }}" style="display: block; height: 100%; text-decoration: none;">
                                </a>
                            </div>
                            <div class="movie-content">
                                <div class="movie-title">{{ strtoupper($phim['name'] ?? 'TÊN PHIM KHÔNG XÁC ĐỊNH') }}</div>
                                <div class="movie-buttons">
                                    <span class="btn btn-outline-light btn-sm hover-trailer">Sắp chiếu</span>
                                    <a href="/chi-tiet-phim?id={{ $phim['id'] }}" class="btn btn-outline-light btn-sm hover-trailer">Xem chi tiết</a>
                                </div>
                                <div class="movie-footer">
                                    <p><span class="fw-bold">Thể loại:</span> {{ $phim['genre'] ?? 'Chưa phân loại' }}</p>
                                    <p><span class="fw-bold">Ngày khởi chiếu:</span> 
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
                            <h3 class="text-muted mt-3">Hiện tại chưa có phim nào sắp chiếu</h3>
                            <p class="text-muted">Vui lòng quay lại sau hoặc xem các phim đang chiếu</p>
                            <a href="/phim-dang-chieu" class="btn btn-primary">
                                <i class="bi bi-arrow-left"></i>
                                Xem phim đang chiếu
                            </a>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Set poster backgrounds
    const movieCards = document.querySelectorAll('.movie-card[data-poster]');
    movieCards.forEach(card => {
        const posterUrl = card.getAttribute('data-poster');
        if (posterUrl) {
            card.style.backgroundImage = `url('${posterUrl}')`;
        }
    });

    const modal = document.getElementById("trailerModal");
    const iframe = document.getElementById("trailerVideo");
    const closeBtn = document.getElementById("closeTrailerBtn");
    const openBtns = document.querySelectorAll(".openTrailerBtn");

    // hàm chuyển đổi url ytb
    function getEmbedUrl(url) {
        if (!url) return '';
        
        
        if (url.includes('youtube.com/watch?v=')) {
            const videoId = url.split('v=')[1].split('&')[0];
            return `https://www.youtube.com/embed/${videoId}`;
        }
        
        
        if (url.includes('youtu.be/')) {
            const videoId = url.split('youtu.be/')[1].split('?')[0];
            return `https://www.youtube.com/embed/${videoId}`;
        }
        
        
        if (url.includes('youtube.com/embed/')) {
            return url;
        }
        
        
        return url;
    }

    openBtns.forEach(btn => {
        btn.addEventListener("click", () => {
            const originalUrl = btn.getAttribute("data-trailer");
            let embedUrl = getEmbedUrl(originalUrl);
            
            // thêm tham số auto
            if (embedUrl.includes('youtube.com/embed/')) {
                embedUrl += "?autoplay=1&rel=0";
            }
            
            iframe.src = embedUrl;
            modal.style.display = "block";
        });
    });

    closeBtn.addEventListener("click", () => {
        modal.style.display = "none";
        iframe.src = "";
    });

    window.addEventListener("click", event => {
        if (event.target == modal) {
            modal.style.display = "none";
            iframe.src = "";
        }
    });
});
</script>
@endsection