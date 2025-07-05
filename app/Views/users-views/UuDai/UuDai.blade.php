@extends('layouts.users.master')

@section('page-css')
    <link rel="stylesheet" href="/static/css/users/UuDai.css" />
@endsection

@section('content')
<main>
    <div class="container">
        <div class="row">
            <div class="col">
                <section class="offers-banner">
                    <h1>KHUYẾN MÃI ĐẶC BIỆT</h1>
                    <p>
                        Khám phá các ưu đãi hấp dẫn dành riêng cho bạn tại KHF Cinema. Tiết kiệm
                        nhiều hơn với mỗi lần đặt vé!
                    </p>
                </section>
                
                {{-- ƯU ĐÃI NỔI BẬT - Giữ nguyên dữ liệu cứng --}}
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
                                    <button class="featured-btn">SỬ DỤNG NGAY</button>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <img src="/static/imgs/bgChonGhe.avif" alt="Combo gia đình" class="img-fluid rounded" />
                            </div>
                        </div>
                    </div>
                </section>

                {{-- TRANG CHỦ ƯU ĐÃI ĐỘNG --}}
                <section class="dynamic-offers">
                    <div class="offers-filter">
                        <h2 class="section-title">TẤT CẢ ƯU ĐÃI</h2>
                        <div class="filter-buttons">
                            <button class="filter-btn active" data-filter="all">Tất cả ({{ count($allOffers ?? []) }})</button>
                            <button class="filter-btn" data-filter="ongoing">Đang diễn ra ({{ count($ongoingOffers ?? []) }})</button>
                            <button class="filter-btn" data-filter="upcoming">Sắp diễn ra ({{ count($upcomingOffers ?? []) }})</button>
                            <button class="filter-btn" data-filter="expired">Đã kết thúc ({{ count($expiredOffers ?? []) }})</button>
                        </div>
                    </div>

                    @if(isset($error))
                        <div class="alert alert-danger">{{ $error }}</div>
                    @endif

                    {{-- HIỂN THỊ ƯU ĐÃI --}}
                    <div class="offers-grid" id="offers-container">
                        @if(isset($allOffers) && count($allOffers) > 0)
                            @foreach($allOffers as $offer)
                                <div class="offer-card" 
                                     data-status="{{ strtolower(str_replace(' ', '-', $offer['ud_trangthai'])) }}">
                                    <div class="offer-image">
                                        @if(!empty($offer['ud_anhuudai']))
                                            <img src="{{ $offer['ud_anhuudai'] }}" alt="{{ $offer['ud_tenuudai'] }}">
                                        @else
                                            <img src="/static/imgs/default-offer.jpg" alt="{{ $offer['ud_tenuudai'] }}">
                                        @endif
                                        <div class="offer-status status-{{ strtolower(str_replace(' ', '-', $offer['ud_trangthai'])) }}">
                                            {{ $offer['ud_trangthai'] }}
                                        </div>
                                    </div>
                                    
                                    <div class="offer-content">
                                        <div class="offer-type">{{ $offer['ud_loaiuudai'] ?? 'Ưu đãi' }}</div>
                                        <h3 class="offer-title">{{ $offer['ud_tenuudai'] }}</h3>
                                        <p class="offer-desc">
                                            {{ mb_strlen($offer['ud_noidung']) > 120 ? mb_substr($offer['ud_noidung'], 0, 120) . '...' : $offer['ud_noidung'] }}
                                        </p>
                                        
                                        <div class="offer-validity">
                                            <i class="bi bi-calendar3"></i>
                                            Từ {{ date('d/m/Y', strtotime($offer['ud_thoigianbatdau'])) }} 
                                            đến {{ date('d/m/Y', strtotime($offer['ud_thoigianketthuc'])) }}
                                        </div>
                                        
                                        @if($offer['ud_trangthai'] === 'Đang diễn ra')
                                            <button class="offer-use-btn">SỬ DỤNG NGAY</button>
                                        @elseif($offer['ud_trangthai'] === 'Sắp diễn ra')
                                            <button class="offer-use-btn disabled">SẮP DIỄN RA</button>
                                        @else
                                            <button class="offer-use-btn disabled">ĐÃ KẾT THÚC</button>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="no-offers">
                                <div class="no-offers-icon">
                                    <i class="bi bi-gift"></i>
                                </div>
                                <h3>Chưa có ưu đãi nào</h3>
                                <p>Các ưu đãi hấp dẫn sẽ sớm được cập nhật. Hãy quay lại sau nhé!</p>
                            </div>
                        @endif
                    </div>
                </section>
            </div>
        </div>   
    </div>
</main>

<script>
// Filter functionality
document.addEventListener('DOMContentLoaded', function() {
    const filterBtns = document.querySelectorAll('.filter-btn');
    const offerCards = document.querySelectorAll('.offer-card');
    
    filterBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const filter = this.dataset.filter;
            
            // Update active button
            filterBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            
            // Filter cards
            offerCards.forEach(card => {
                if (filter === 'all') {
                    card.style.display = 'block';
                } else {
                    const cardStatus = card.dataset.status;
                    if (filter === 'ongoing' && cardStatus === 'đang-diễn-ra') {
                        card.style.display = 'block';
                    } else if (filter === 'upcoming' && cardStatus === 'sắp-diễn-ra') {
                        card.style.display = 'block';
                    } else if (filter === 'expired' && cardStatus === 'kết-thúc') {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                }
            });
        });
    });
});
</script>
@endsection