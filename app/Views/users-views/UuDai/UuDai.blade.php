@extends('layouts.users.master')

@section('title', 'Ưu đãi')
@section('page-css')
    <link rel="stylesheet" href="/static/css/users/UuDai.css" />
@endsection

@section('content')
<main>
    <div class="container">
        {{-- BANNER ƯU ĐÃI --}}
        <section class="offers-banner">
            <h1>KHUYẾN MÃI ĐẶC BIỆT</h1>
            <p>
                Khám phá các ưu đãi hấp dẫn dành riêng cho bạn tại KHF Cinema. Tiết kiệm
                nhiều hơn với mỗi lần đặt vé!
            </p>
        </section>
        
        {{-- ƯU ĐÃI NỔI BẬT --}}
        <section class="mb-5">
            <h2 class="section-title">ƯU ĐÃI NỔI BẬT</h2>
            <div class="featured-offer">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div class="featured-content">
                            <h3>COMBO GIA ĐÌNH SIÊU TIẾT KIỆM</h3>
                            <p>
                                Combo 4 vé + 2 bắp lớn + 4 nước chỉ 399.000đ. Áp dụng cho tất
                                cả các suất chiếu trong tuần. Hạn sử dụng đến 31/12/2024.
                            </p>
                            <!-- <button class="featured-btn">SỬ DỤNG NGAY</button> -->
                        </div>
                    </div>
                    <div class="col-md-6">
                        <img src="/static/imgs/bgChonGhe.avif" alt="Combo gia đình" class="img-fluid rounded" />
                    </div>
                </div>
            </div>
        </section>

        {{-- SECTION TẤT CẢ ƯU ĐÃI --}}
        <section class="offers-section">
            <div class="filter-section">
                <h2 class="section-title">TẤT CẢ ƯU ĐÃI</h2>
                <div class="btn-group" role="group">
                    <button type="button" class="btn filter-btn active" data-filter="all">
                        Tất cả ({{ count($allOffers ?? []) }})
                    </button>
                    <button type="button" class="btn filter-btn" data-filter="ongoing">
                        Đang diễn ra ({{ count($ongoingOffers ?? []) }})
                    </button>
                    <button type="button" class="btn filter-btn" data-filter="upcoming">
                        Sắp diễn ra ({{ count($upcomingOffers ?? []) }})
                    </button>
                    <button type="button" class="btn filter-btn" data-filter="expired">
                        Đã kết thúc ({{ count($expiredOffers ?? []) }})
                    </button>
                </div>
            </div>

            @if(isset($error))
                <div class="alert alert-danger">{{ $error }}</div>
            @endif

            {{-- GRID ƯU ĐÃI --}}
            <div class="row" id="offers-container">
                @if(isset($allOffers) && count($allOffers) > 0)
                    @foreach($allOffers as $offer)
                        @php
                            $statusClass = '';
                            switch($offer['ud_trangthai']) {
                                case 'Đang diễn ra': $statusClass = 'dang-dien-ra'; break;
                                case 'Sắp diễn ra': $statusClass = 'sap-dien-ra'; break;
                                case 'Kết thúc': $statusClass = 'ket-thuc'; break;
                                default: $statusClass = strtolower(str_replace([' ', 'đ', 'ê', 'ã'], ['-', 'd', 'e', 'a'], $offer['ud_trangthai']));
                            }
                        @endphp
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="offer-card" data-status="{{ $statusClass }}">
                                <div class="offer-img">
                                    @if(!empty($offer['ud_anhuudai']))
                                        <img src="{{ $offer['ud_anhuudai'] }}" alt="{{ $offer['ud_tenuudai'] }}">
                                    @else
                                        <img src="/static/imgs/default-offer.jpg" alt="{{ $offer['ud_tenuudai'] }}">
                                    @endif
                                </div>
                                
                                <div class="offer-content">
                                    <div class="offer-badge">{{ $offer['ud_loaiuudai'] ?? 'Ưu đãi' }}</div>
                                    <h3 class="offer-title">{{ $offer['ud_tenuudai'] }}</h3>
                                    <p class="offer-desc">
                                        {{ mb_strlen($offer['ud_noidung']) > 100 ? mb_substr($offer['ud_noidung'], 0, 100) . '...' : $offer['ud_noidung'] }}
                                    </p>
                                    
                                    <div class="offer-details">
                                        <div class="offer-expiry">
                                            <small>
                                                HSD: {{ date('d/m/Y', strtotime($offer['ud_thoigianketthuc'])) }}
                                            </small>
                                        </div>
                                        
                                        @if($offer['ud_trangthai'] === 'Đang diễn ra')
                                            <div class="offer-btn">ĐANG DIỄN RA</div>
                                        @elseif($offer['ud_trangthai'] === 'Sắp diễn ra')
                                            <span class="offer-btn" style="opacity: 0.6; cursor: not-allowed;">SẮP DIỄN RA</span>
                                        @else
                                            <span class="offer-btn" style="opacity: 0.4; cursor: not-allowed;">ĐÃ KẾT THÚC</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="col-12">
                        <div class="text-center py-5">
                            <div style="font-size: 4rem; color: #666; margin-bottom: 1rem;">
                                <i class="bi bi-gift"></i>
                            </div>
                            <h3 style="color: white;">Chưa có ưu đãi nào</h3>
                            <p style="color: #b0b0b0;">Các ưu đãi hấp dẫn sẽ sớm được cập nhật. Hãy quay lại sau nhé!</p>
                        </div>
                    </div>
                @endif
            </div>
        </section>
    </div>
</main>
@endsection

@section('page-js')
<script src="/static/js/users/UuDai.js"></script>
@endsection