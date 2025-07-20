@php
    header('Content-Type: text/html; charset=UTF-8');
@endphp
@extends('layouts.users.master')

@section('content')
<main>
    <div class="container">
        {{-- Thông báo đăng nhập thành công --}}
        @if(isset($_SESSION['success_message']))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>{{ $_SESSION['success_message'] }}</strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @php
                unset($_SESSION['success_message']);
            @endphp
        @endif
        <div class="row div-pad">
            <div class="col">
                <!--Poster quảng cáo phim-->
                <div id="carouselExample" class="carousel slide " data-bs-ride="carousel" data-bs-interval="3000">
                    <div class="carousel-inner">
                        @forelse($posterList ?? [] as $index => $poster)
                            <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                <img src="{{ $poster['pt_anhposter'] }}" 
                                     class="d-block w-100 carousel-img img-fluid" 
                                     alt="Poster {{ $index+1 }}"
                                     onerror="this.style.display='none'; this.parentElement.innerHTML='<div class=\'d-flex justify-content-center align-items-center\' style=\'height: 400px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);\'><div class=\'text-center text-white\'><h3>Poster không khả dụng</h3></div></div>';">
                            </div>
                        @empty
                            <div class="carousel-item active">
                                <div class="d-flex justify-content-center align-items-center" style="height: 400px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                    <div class="text-center text-white">
                                        <h3>Chưa có poster quảng cáo</h3>
                                        <p>Vui lòng quay lại sau</p>
                                    </div>
                                </div>
                            </div>
                        @endforelse
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
                <hr style="color:#fff; border-width: 2px;">
                <br>
                <!--Hiển thị chuyển đổi phim-->
                <div>
                    <ul class="nav nav-tabs justify-content-center" id="phimTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                        <button
                            class="nav-link active swap-f-btn"
                            id="dang-chieu-tab"
                            data-bs-toggle="tab"
                            data-bs-target="#dang-chieu"
                            type="button"
                            role="tab"
                            aria-controls="dang-chieu"
                            aria-selected="true"
                        >
                            <span class="responsive-text">PHIM ĐANG CHIẾU</span>
                        </button>
                        </li>
                        <li class="nav-item" role="presentation">
                        <button
                            class="nav-link swap-f-btn"
                            id="sap-chieu-tab"
                            data-bs-toggle="tab"
                            data-bs-target="#sap-chieu"
                            type="button"
                            role="tab"
                            aria-controls="sap-chieu"
                            aria-selected="false"
                        >
                            <span class="responsive-text"> PHIM SẮP CHIẾU</span>
                        </button>
                        </li>
                    </ul><br>

                    <div class="tab-content mt-3" id="phimTabContent">
                        <!-- Tab PHIM ĐANG CHIẾU  -->
                        <div
                        class="tab-pane fade show active"
                        id="dang-chieu"
                        role="tabpanel"
                        aria-labelledby="dang-chieu-tab"
                        >
                        <div id="carouselDangChieu" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5000">
                            <div class="carousel-inner">
                                @if(count($phimDangChieu ?? []) > 0)
                                    @php
                                        $chunks = array_chunk($phimDangChieu, 4);
                                    @endphp
                                    @foreach($chunks as $index => $chunk)
                                    <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                        <div class="row">
                                            {{-- Phim đang chiếu --}}
                                            @foreach($chunk as $phim)
                                            <div class="col-6 col-md-4 col-lg-3">
                                                <div class="movie-card">
                                                    @if($phim['p_poster'])
                                                        <img src="{{ $phim['p_poster'] }}" 
                                                             class="img-fluid carousel-img-inner" 
                                                             alt="{{ $phim['p_tenphim'] }}"
                                                             onerror="this.style.display='none'; this.parentElement.innerHTML='<div class=\'d-flex justify-content-center align-items-center bg-secondary\' style=\'height: 300px;\'><span class=\'text-white\'>Chưa có poster</span></div>';">
                                                    @else
                                                        <div class="d-flex justify-content-center align-items-center bg-secondary" style="height: 300px;">
                                                            <span class="text-white">Chưa có poster</span>
                                                        </div>
                                                    @endif
                                                    <a href="/phim/{{ $phim['slug'] }}" class="book-hover-btn">Đặt vé</a>
                                                </div>  
                                                <div class="movie-card-text">
                                                    <p>{{ $phim['p_tenphim'] }}</p>
                                                </div>
                                                <div class="book-bg">
                                                    <a href="/phim/{{ $phim['slug'] }}" class="book-tk">Đặt vé</a>
                                                </div>                                                                    
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    @endforeach
                                @else
                                    <div class="carousel-item active">
                                        <div class="row justify-content-center">
                                            <div class="col-12 text-center">
                                                <div class="alert alert-info" style="background: rgba(13, 202, 240, 0.1); border: 1px solid rgba(13, 202, 240, 0.3); color: #0dcaf0;">
                                                    <h5>Chưa có phim đang chiếu</h5>
                                                    <p>Vui lòng quay lại sau!</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <button class="carousel-control-prev" type="button" data-bs-target="#carouselDangChieu" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon"></span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#carouselDangChieu" data-bs-slide="next">
                                <span class="carousel-control-next-icon"></span>
                            </button>
                        </div>
                    </div>
                    <!-- Tab PHIM SẮP CHIẾU  -->
                    <div
                    class="tab-pane fade"
                    id="sap-chieu"
                    role="tabpanel"
                    aria-labelledby="sap-chieu-tab"
                    >
                        <div id="carouselSapChieu" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5000">
                            <div class="carousel-inner">
                                @if(count($phimSapChieu ?? []) > 0)
                                    @php
                                        $chunks = array_chunk($phimSapChieu, 4);
                                    @endphp
                                    @foreach($chunks as $index => $chunk)
                                    <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                        <div class="row">
                                            {{-- Phim sắp chiếu --}}
                                            @foreach($chunk as $phim)
                                            <div class="col-6 col-md-4 col-lg-3">
                                                <div class="movie-card">
                                                    <a href="/phim/{{ $phim['slug'] }}" class="text-decoration-none">
                                                        @if($phim['p_poster'])
                                                            <img src="{{ $phim['p_poster'] }}" 
                                                                 class="img-fluid carousel-img-inner" 
                                                                 alt="{{ $phim['p_tenphim'] }}"
                                                                 onerror="this.style.display='none'; this.parentElement.innerHTML='<div class=\'d-flex justify-content-center align-items-center bg-secondary\' style=\'height: 300px;\'><span class=\'text-white\'>Chưa có poster</span></div>';">
                                                        @else
                                                            <div class="d-flex justify-content-center align-items-center bg-secondary" style="height: 300px;">
                                                                <span class="text-white">Chưa có poster</span>
                                                            </div>
                                                        @endif
                                                    </a>
                                                </div>                                                
                                                <div class="movie-card-text">
                                                    <p>{{ $phim['p_tenphim'] }}</p>
                                                </div>
                                                <div class="book-bg">
                                                    <a href="/phim/{{ $phim['slug'] }}" class="book-tk">Sắp chiếu</a>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    @endforeach
                                @else
                                    <div class="carousel-item active">
                                        <div class="row justify-content-center">
                                            <div class="col-12 text-center">
                                                <div class="alert alert-info" style="background: rgba(13, 202, 240, 0.1); border: 1px solid rgba(13, 202, 240, 0.3); color: #0dcaf0;">
                                                    <h5>Chưa có phim sắp chiếu</h5>
                                                    <p>Vui lòng quay lại sau!</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <button class="carousel-control-prev" type="button" data-bs-target="#carouselSapChieu" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon"></span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#carouselSapChieu" data-bs-slide="next">
                                <span class="carousel-control-next-icon"></span>
                            </button>
                        </div>
                    </div>
                </div>
                <br>
                <hr style="color:#fff; border-width: 2px;">
                <br><br>
                <!-- ƯU ĐÃI VÀ DỊCH VỤ -->
                <div>
                    <h1 class="align-text">ƯU ĐÃI VÀ DỊCH VỤ</h1><br>
                    
                    <div class="row">
                        @forelse($uuDaiList ?? [] as $index => $uuDai)
                            @if($index < 4) {{-- Giới hạn hiển thị tối đa 4 ưu đãi --}}
                            <div class="col-6 col-md-3 mb-3">
                                <a href="/uu-dai" class="text-decoration-none">
                                    @if($uuDai['udtc_anhuudai'])
                                        <img src="{{ $uuDai['udtc_anhuudai'] }}" 
                                             alt="Ưu đãi {{ $uuDai['udtc_mauudai'] ?? '' }}" 
                                             class="img-fluid service-img"
                                             style="border-radius: 8px; transition: transform 0.3s ease;"
                                             onmouseover="this.style.transform='scale(1.05)'"
                                             onmouseout="this.style.transform='scale(1)'"
                                             onerror="this.style.display='none'; this.parentElement.innerHTML='<div class=\'d-flex justify-content-center align-items-center bg-gradient\' style=\'height: 200px; border-radius: 8px; background: linear-gradient(45deg, #667eea, #764ba2);\'><span class=\'text-white\'>Chưa có hình ảnh</span></div>';">
                                    @else
                                        <div class="d-flex justify-content-center align-items-center bg-gradient" 
                                             style="height: 200px; border-radius: 8px; background: linear-gradient(45deg, #667eea, #764ba2);">
                                            <span class="text-white">Chưa có hình ảnh</span>
                                        </div>
                                    @endif
                                </a>                             
                            </div>
                            @endif
                        @empty
                            <div class="col-12 text-center">
                                <div class="alert alert-info" style="background: rgba(13, 202, 240, 0.1); border: 1px solid rgba(13, 202, 240, 0.3); color: #0dcaf0;">
                                    <i class="bi bi-info-circle me-2"></i>
                                    <h5 class="mb-2">Hiện tại chưa có ưu đãi nào</h5>
                                    <p class="mb-0">Vui lòng quay lại sau để xem các ưu đãi mới nhất!</p>
                                </div>
                            </div>
                        @endforelse
                        
                        {{-- Hiển thị nút "Xem tất cả" nếu có nhiều hơn 4 ưu đãi --}}
                        @if(count($uuDaiList ?? []) > 4)
                        <div class="col-12 text-center mt-4">
                            <a href="/uu-dai" class="btn btn-outline-light btn-lg px-4 py-2">
                                <i class="bi bi-eye me-2"></i> Xem tất cả ưu đãi
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
            </div><br>
        </div>
    </div>
</main>
@endsection