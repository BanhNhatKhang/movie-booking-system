{{-- filepath: d:\Server\ct27501-project-BanhNhatKhang\app\Views\users-views\UuDai.blade.php --}}

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

                    <div class="featured-offer">
                        <div class="row align-items-center">
                            <div class="col-md-6 order-md-2">
                                <div class="featured-content">
                                    <h3>MIỄN PHÍ VÉ THỨ 3</h3>
                                    <p>
                                        Khi mua 2 vé bất kỳ, nhận ngay vé thứ 3 miễn phí. Chương trình
                                        áp dụng vào thứ 4 hàng tuần tại tất cả các rạp KHF Cinema trên
                                        toàn quốc.
                                    </p>
                                    <button class="featured-btn">SỬ DỤNG NGAY</button>
                                </div>
                            </div>
                            <div class="col-md-6 order-md-1">
                                <img
                                    src="/static/imgs/tichdiem2x.jpg"
                                    alt="Miễn phí vé"
                                    class="img-fluid rounded"
                                />
                            </div>
                        </div>
                    </div>
                </section>

                {{-- TẤT CẢ ƯU ĐÃI - Dữ liệu động từ database --}}
                <section class="offers-section">
                    <div class="row p-3">
                        <div class="col-md-12">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h2 class="section-title">TẤT CẢ ƯU ĐÃI</h2>
                                <div class="d-flex flex-wrap">
                                    <button class="btn filter-btn active" data-filter="all">
                                        Tất cả ({{ count($allOffers ?? []) }})
                                    </button>
                                    <button class="btn filter-btn" data-filter="ongoing">
                                        Đang diễn ra ({{ count($ongoingOffers ?? []) }})
                                    </button>
                                    <button class="btn filter-btn" data-filter="upcoming">
                                        Sắp diễn ra ({{ count($upcomingOffers ?? []) }})
                                    </button>
                                    <button class="btn filter-btn" data-filter="expired">
                                        Đã kết thúc ({{ count($expiredOffers ?? []) }})
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        @forelse($allOffers ?? [] as $offer)
                            <div class="col-lg-4 col-md-6 offer-item" 
                                 data-status="{{ 
                                     $offer['ud_trangthai'] === 'Đang diễn ra' ? 'ongoing' : 
                                     ($offer['ud_trangthai'] === 'Sắp diễn ra' ? 'upcoming' : 'expired') 
                                 }}">
                                <div class="offer-card">
                                    <div class="offer-img">
                                        @if($offer['ud_anhuudai'])
                                            <img src="{{ $offer['ud_anhuudai'] }}" 
                                                 alt="{{ $offer['ud_tenuudai'] }}"
                                                 onerror="this.src='/static/imgs/placeholder-offer.jpg'" />
                                        @else
                                            <img src="/static/imgs/placeholder-offer.jpg" 
                                                 alt="{{ $offer['ud_tenuudai'] }}" />
                                        @endif
                                    </div>
                                    <div class="offer-content">
                                        <span class="offer-badge">{{ $offer['ud_loaiuudai'] }}</span>
                                        <h4 class="offer-title">{{ $offer['ud_tenuudai'] }}</h4>
                                        
                                        <p class="offer-desc">
                                            {{ mb_strlen($offer['ud_noidung']) > 120 ? mb_substr($offer['ud_noidung'], 0, 120) . '...' : $offer['ud_noidung'] }}
                                        </p>
                                        <div class="offer-details">
                                            <span class="offer-expiry">
                                                HSD: {{ date('d/m/Y', strtotime($offer['ud_thoigianketthuc'])) }}
                                            </span>
                                            <a href="#" class="offer-btn">Chi tiết</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            {{-- Empty state khi không có ưu đãi --}}
                            <div class="col-12">
                                <div class="text-center py-5">
                                    <div style="color: #888; padding: 3rem;">
                                        <i class="bi bi-gift" style="font-size: 4rem; color: #555; margin-bottom: 1rem;"></i>
                                        <h4 style="color: #ccc; margin-bottom: 1rem;">Chưa có ưu đãi nào</h4>
                                        <p style="color: #888;">Các ưu đãi hấp dẫn sẽ sớm được cập nhật. Hãy quay lại sau nhé!</p>
                                    </div>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </section>
            </div>
        </div>   
    </div>
</main>
@endsection

@section('page-js')
    <script src="/static/js/users/UuDai.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    {{-- Custom JavaScript cho filter --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const filterButtons = document.querySelectorAll('.filter-btn');
            const offerItems = document.querySelectorAll('.offer-item');

            filterButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const filter = this.getAttribute('data-filter');

                    // Update active button
                    filterButtons.forEach(btn => btn.classList.remove('active'));
                    this.classList.add('active');

                    // Filter offers
                    offerItems.forEach(item => {
                        if (filter === 'all') {
                            item.style.display = 'block';
                            item.classList.add('fade-in');
                        } else {
                            const status = item.getAttribute('data-status');
                            if (status === filter) {
                                item.style.display = 'block';
                                item.classList.add('fade-in');
                            } else {
                                item.style.display = 'none';
                                item.classList.remove('fade-in');
                            }
                        }
                    });
                });
            });
        });
    </script>
    
    <style>
        .offer-item {
            transition: all 0.3s ease;
        }
        .fade-in {
            animation: fadeIn 0.5s ease-in;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
@endsection