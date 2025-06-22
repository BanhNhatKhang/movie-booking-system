@extends('layouts.users.master')

@section('page-css')
    <link rel="stylesheet" href="/static/css/users/PhimDangChieu.css">
@endsection

@section('content')
<main>
    <div class="container mt-4">
        <div class="bg-light-subtle">
            <div class="row p-2">
                <!-- Card phim 1 -->
                <div class="col-12 col-sm-6 col-lg-3 mb-4">
                    <div class="movie-card">
                        <div class="movie-header">
                            <a href="/chi-tiet-phim"><img src="/static/imgs/latmat1.jpg" alt="Lật Mặt 7" /></a>
                        </div>
                        <div class="movie-title fs-4">LẬT MẶT 7: MỘT ĐIỀU ƯỚC</div>
                        <div class="movie-buttons">
                            <span class="btn btn-danger btn-sm">Lồng tiếng</span>
                            <a href="/chi-tiet-phim" class="btn btn-danger btn-sm">Đặt vé</a>
                        </div>
                        <div class="movie-footer">
                            <p><span class="fw-bold">Thể loại:</span> Tình cảm, Gia đình</p>
                            <p><span class="fw-bold">Ngày chiếu:</span> 26/04/2024</p>
                            <p><span class="fw-bold">Đạo diễn:</span> Lý Hải</p><br>
                            <button class="openTrailerBtn btn btn-light border hover-trailer" 
                                    data-trailer="https://www.youtube.com/embed/d1ZHdosjNX8">
                                <i class="bi bi-play"></i>
                                Trailer
                            </button>
                        </div>
                    </div>
                </div>
                <!-- Card phim 2 -->
                <div class="col-12 col-sm-6 col-lg-3 mb-4">
                    <div class="movie-card">
                        <div class="movie-header">
                            <a href="#"><img src="/static/imgs/1-onl-17217488966071748078747-2402.jpg" alt="Làm giàu với ma" /></a>
                        </div>
                        <div class="movie-title fs-4">LÀM GIÀU VỚI MA</div>
                        <div class="movie-buttons">
                            <span class="btn btn-danger btn-sm">Lồng tiếng</span>
                            <button class="btn btn-danger btn-sm">Đặt vé</button>
                        </div>
                        <div class="movie-footer">
                            <p><span class="fw-bold">Thể loại:</span> Gia đình, Hài</p>
                            <p><span class="fw-bold">Ngày chiếu:</span> 30/08/2024</p>
                            <p><span class="fw-bold">Đạo diễn:</span> Nguyễn Nhật Trung</p><br>
                            <button class="openTrailerBtn btn btn-light border hover-trailer" 
                                    data-trailer="https://www.youtube.com/embed/MtZ_hf7tLxk">
                                <i class="bi bi-play"></i>
                                Trailer
                            </button>
                        </div>
                    </div>
                </div>
                <!-- Card phim 3 -->
                <div class="col-12 col-sm-6 col-lg-3 mb-4">
                    <div class="movie-card">
                        <div class="movie-header">
                            <a href="#"><img src="/static/imgs/conan-movie-26-16932064940221048161862.webp" alt="Conan 26" /></a>
                        </div>
                        <div class="movie-title fs-4">THÁM TỬ LỪNG DANH CONAN: TÀU NGẦM SẮT MÀU ĐEN</div>
                        <div class="movie-buttons">
                            <span class="btn btn-danger btn-sm">Lồng tiếng</span>
                            <button class="btn btn-danger btn-sm">Đặt vé</button>
                        </div>
                        <div class="movie-footer">
                            <p><span class="fw-bold">Thể loại:</span> Hành động, Hoạt hình, Phiêu lưu</p>
                            <p><span class="fw-bold">Ngày chiếu:</span> 21/07/2023</p>
                            <p><span class="fw-bold">Đạo diễn:</span> Yuzuru Tachikawa</p><br>
                            <button class="openTrailerBtn btn btn-light border hover-trailer" 
                                    data-trailer="https://www.youtube.com/embed/NwnQI9izPFc">
                                <i class="bi bi-play"></i>
                                Trailer
                            </button>
                        </div>
                    </div>
                </div>
                <!-- Card phim 4 -->
                <div class="col-12 col-sm-6 col-lg-3 mb-4">
                    <div class="movie-card">
                        <div class="movie-header">
                            <a href="#"><img src="/static/imgs/lilo-500_1747389395062.jpg" alt="Lilo & Stitch" /></a>
                        </div>
                        <div class="movie-title fs-4">LILO & STITCH</div>
                        <div class="movie-buttons">
                            <span class="btn btn-danger btn-sm">Lồng tiếng</span>
                            <button class="btn btn-danger btn-sm">Đặt vé</button>
                        </div>
                        <div class="movie-footer">
                            <p><span class="fw-bold">Thể loại:</span> Gia đình, Hài, Phiêu lưu</p>
                            <p><span class="fw-bold">Ngày chiếu:</span> 23/05/2025</p>
                            <p><span class="fw-bold">Đạo diễn:</span> Dean Fleischer Camp</p><br>
                            <button class="openTrailerBtn btn btn-light border hover-trailer" 
                                    data-trailer="https://www.youtube.com/embed/9qhrQqijrOU">
                                <i class="bi bi-play"></i>
                                Trailer
                            </button>
                        </div>
                    </div>
                </div>
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
    const modal = document.getElementById("trailerModal");
    const iframe = document.getElementById("trailerVideo");
    const closeBtn = document.getElementById("closeTrailerBtn");
    const openBtns = document.querySelectorAll(".openTrailerBtn");

    openBtns.forEach(btn => {
        btn.addEventListener("click", () => {
            const youtubeLink = btn.getAttribute("data-trailer");
            iframe.src = youtubeLink + "?autoplay=1&rel=0";
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
</script>
@endsection