@if($totalPages > 1)
    <div class="d-flex justify-content-between align-items-center mt-4">
        {{-- Thông tin hiển thị --}}
        <div class="text-muted">
            Hiển thị {{ ($currentPage - 1) * ($itemsPerPage ?? 15) + 1 }} - {{ min($currentPage * ($itemsPerPage ?? 15), $totalItems) }} trong {{ $totalItems }} {{ $itemName ?? 'mục' }}
        </div>
        
        {{-- Pagination --}}
        <nav aria-label="Phân trang {{ $itemName ?? 'dữ liệu' }}">
            <ul class="pagination pagination-sm mb-0">
                {{-- Previous button --}}
                <li class="page-item {{ $currentPage <= 1 ? 'disabled' : '' }}">
                    <a class="page-link" href="?page={{ $currentPage - 1 }}&q={{ $search ?? '' }}&status={{ $status ?? '' }}" 
                       aria-label="Trang trước">
                        <i class="bi bi-chevron-left"></i>
                    </a>
                </li>
                
                @if($totalPages <= 7)
                    @for($i = 1; $i <= $totalPages; $i++)
                        <li class="page-item {{ $currentPage == $i ? 'active' : '' }}">
                            <a class="page-link" href="?page={{ $i }}&q={{ $search ?? '' }}&status={{ $status ?? '' }}">{{ $i }}</a>
                        </li>
                    @endfor
                @else
                    <li class="page-item {{ $currentPage == 1 ? 'active' : '' }}">
                        <a class="page-link" href="?page=1&q={{ $search ?? '' }}&status={{ $status ?? '' }}">1</a>
                    </li>
                    
                    @if($currentPage > 4)
                        <li class="page-item disabled">
                            <span class="page-link">...</span>
                        </li>
                    @endif
                    
                    @for($i = max(2, $currentPage - 1); $i <= min($totalPages - 1, $currentPage + 1); $i++)
                        @if($i != 1 && $i != $totalPages)
                            <li class="page-item {{ $currentPage == $i ? 'active' : '' }}">
                                <a class="page-link" href="?page={{ $i }}&q={{ $search ?? '' }}&status={{ $status ?? '' }}">{{ $i }}</a>
                            </li>
                        @endif
                    @endfor
                    
                    @if($currentPage < $totalPages - 3)
                        <li class="page-item disabled">
                            <span class="page-link">...</span>
                        </li>
                    @endif
                    
                    @if($totalPages > 1)
                        <li class="page-item {{ $currentPage == $totalPages ? 'active' : '' }}">
                            <a class="page-link" href="?page={{ $totalPages }}&q={{ $search ?? '' }}&status={{ $status ?? '' }}">{{ $totalPages }}</a>
                        </li>
                    @endif
                @endif
                
                <li class="page-item {{ $currentPage >= $totalPages ? 'disabled' : '' }}">
                    <a class="page-link" href="?page={{ $currentPage + 1 }}&q={{ $search ?? '' }}&status={{ $status ?? '' }}" 
                       aria-label="Trang tiếp">
                        <i class="bi bi-chevron-right"></i>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
@endif