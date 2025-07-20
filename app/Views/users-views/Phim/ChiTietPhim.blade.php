{{-- filepath: d:\Server\project\app\Views\users-views\Phim\ChiTietPhim.blade.php --}}
@php
    header('Content-Type: text/html; charset=UTF-8');
@endphp

@extends('layouts.users.master')

@section('title', 'Chi tiết phim')
@section('page-css')
    <link rel="stylesheet" href="/static/css/users/ChiTietPhim.css">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
@endsection

@section('content')
<main>
    <div class="container mt-3">
        <div class="bg-light-subtle border rounded">
            <div class="row flex-column flex-lg-row p-2">
                <div class="col-lg-4 mb-3 mb-lg-0">
                    @if($phim['poster'])
                        <img src="{{ $phim['poster'] }}" 
                             class="img-fluid" 
                             alt="{{ $phim['name'] }}"
                             onerror="this.style.display='none'; this.parentElement.innerHTML='<div class=\'d-flex align-items-center justify-content-center bg-light border rounded\' style=\'height: 400px;\'><div class=\'text-center text-muted\'><i class=\'bi bi-film display-1\'></i><p class=\'mt-2\'>Chưa có poster</p></div></div>';" />
                    @else
                        <div class="d-flex align-items-center justify-content-center bg-light border rounded" style="height: 400px;">
                            <div class="text-center text-muted">
                                <i class="bi bi-film display-1"></i>
                                <p class="mt-2">Chưa có poster</p>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="col-lg-8">
                    <div class="row bg-light-subtle border rounded text-dark p-3 me-3 align-items-start">
                        <div class="col-lg-9 col-md-12">
                            <div>
                                <p class="fs-1">{{ strtoupper($phim['name']) }}</p>
                                <hr class="red-bold-hr">
                                <div class="fs-5">
                                    <p><span class="fw-semibold">Thể loại :</span> {{ $phim['genre'] ?? 'Chưa xác định' }}</p>
                                    <p><span class="fw-semibold">Đạo diễn :</span> {{ $phim['director'] ?? 'Chưa xác định' }}</p>
                                    <p><span class="fw-semibold">Ngày chiếu :</span> 
                                        @if($phim['release'])
                                            {{ date('d/m/Y', strtotime($phim['release'])) }}
                                        @else
                                            Chưa xác định
                                        @endif
                                    </p>
                                    <p><span class="fw-semibold">Diễn viên :</span> {{ $phim['actors'] ?? 'Chưa xác định' }}</p>
                                    <p><span class="fw-semibold">Thời lượng :</span> {{ $phim['duration'] ?? 0 }} phút</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-12 mt-3 mt-lg-0">
                            @if($phim['trailer'])
                                <button class="button-trailer openTrailerBtn w-100" data-trailer="{{ $phim['trailer'] }}">
                                    <i class="bi bi-play"></i>
                                    Trailer
                                </button>
                            @else
                                <button class="btn btn-secondary w-100" disabled>
                                    <i class="bi bi-film"></i>
                                    Chưa có trailer
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            
            @if($phim['desc'])
            <div class="row p-4">
                <div class="border rounded p-3 bg-danger-subtle">
                    <button class="btn btn-danger">Tóm tắt</button><hr>
                    <p>{{ $phim['desc'] }}</p>
                </div>
            </div>
            @endif
            
            <div class="row p-4">
                <div class="border rounded shadow-sm p-3 bg-white">
                    <button class="btn btn-danger mb-3 px-4 py-2 fw-bold">SUẤT CHIẾU</button>
                    <div class="bg-danger text-white fw-bold px-3 py-2 rounded-top">
                        KHF CINEMA CẦN THƠ
                    </div>
                    <div class="border border-top-0 px-3 py-2">
                        3/2 Quận Ninh Kiều, TP Cần Thơ
                    </div>
                    
                    @forelse($lichChieuByDate as $ngay => $gioList)
                        <div class="bg-dark text-white mt-3 px-3 py-2 rounded-top fw-bold">
                            {{ date('d/m/Y', strtotime($ngay)) }}
                        </div>
                        <div class="border border-top-0 p-2 d-flex flex-wrap gap-2">
                            @foreach($gioList as $lichChieu)
                                @if($lichChieu['trangthai'] === 'Sắp chiếu')
                                    <a href="/chon-ghe/{{ $lichChieu['slug'] }}" class="text-decoration-none">
                                        <button class="btn btn-danger btn-sm">
                                            {{ $lichChieu['gio'] }}
                                        </button>
                                    </a>
                                @elseif($lichChieu['trangthai'] === 'Đang chiếu')
                                    <button class="btn btn-warning btn-sm" 
                                            title="Phim đang chiếu - Không thể đặt vé">
                                        {{ $lichChieu['gio'] }}
                                    </button>
                                @else
                                    <button class="btn btn-secondary btn-sm" disabled 
                                            title="Suất chiếu này đã {{ strtolower($lichChieu['trangthai']) }}">
                                        {{ $lichChieu['gio'] }}
                                    </button>
                                @endif
                            @endforeach
                        </div>
                    @empty
                        <div class="text-center p-4">
                            <i class="bi bi-calendar-x display-4 text-muted"></i>
                            <h5 class="mt-3 text-muted">Hiện tại chưa có lịch chiếu</h5>
                            <p class="text-muted">Vui lòng quay lại sau để xem lịch chiếu mới nhất</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Trailer -->
    <div id="trailerModal" class="modal" style="display: none;">
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
</main>

<script src="/static/js/users/ChiTietPhim.js"></script>
@endsection