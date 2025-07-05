


<?php $__env->startSection('page-css'); ?>
    <link rel="stylesheet" href="/static/css/users/PhimDangChieu.css">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<main>
    <div class="container mt-4">
        <div class="bg-light-subtle">
            <div class="row p-2">
                <?php $__empty_1 = true; $__currentLoopData = $phimDangChieu ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $phim): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="col-12 col-sm-6 col-lg-3 mb-4">
                        <div class="movie-card" style="background-image: url('<?php echo e($phim['poster']); ?>');">
                            <div class="movie-header">
                                <a href="/chi-tiet-phim?id=<?php echo e($phim['id']); ?>" style="display: block; height: 100%; text-decoration: none;">

                                </a>
                            </div>
                            <div class="movie-content">
                                <div class="movie-title"><?php echo e(strtoupper($phim['name'] ?? 'TÊN PHIM KHÔNG XÁC ĐỊNH')); ?></div>
                                <div class="movie-buttons">
                                    <span class="btn btn-outline-light btn-sm hover-trailer">Lồng tiếng</span>
                                    <a href="/chi-tiet-phim?id=<?php echo e($phim['id']); ?>" class="btn btn-outline-light btn-sm hover-trailer">Đặt vé</a>
                                </div>
                                <div class="movie-footer">
                                    <p><span class="fw-bold">Thể loại:</span> <?php echo e($phim['genre'] ?? 'Chưa phân loại'); ?></p>
                                    <p><span class="fw-bold">Ngày chiếu:</span> 
                                        <?php if(!empty($phim['release'])): ?>
                                            <?php echo e(date('d/m/Y', strtotime($phim['release']))); ?>

                                        <?php else: ?>
                                            Chưa xác định
                                        <?php endif; ?>
                                    </p>
                                    <p><span class="fw-bold">Đạo diễn:</span> <?php echo e($phim['director'] ?? 'Chưa xác định'); ?></p>
                                    
                                    <?php if(!empty($phim['trailer'])): ?>
                                        <button class="openTrailerBtn btn btn-light border hover-trailer btn-sm mt-2" 
                                                data-trailer="<?php echo e($phim['trailer']); ?>">
                                            <i class="bi bi-play"></i>
                                            Trailer
                                        </button>
                                    <?php else: ?>
                                        <button class="btn btn-secondary btn-sm mt-2" disabled>
                                            <i class="bi bi-film"></i>
                                            Chưa có trailer
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <!-- Hiển thị khi không có phim -->
                    <div class="col-12">
                        <div class="text-center py-5">
                            <i class="bi bi-film display-1 text-muted"></i>
                            <h3 class="text-muted mt-3">Hiện tại chưa có phim nào đang chiếu</h3>
                            <p class="text-muted">Vui lòng quay lại sau hoặc xem các phim sắp chiếu</p>
                        </div>
                    </div>
                <?php endif; ?>
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

    // Function to convert YouTube URL to embed format
    function getEmbedUrl(url) {
        if (!url) return '';
        
        // YouTube watch URL: https://www.youtube.com/watch?v=VIDEO_ID
        if (url.includes('youtube.com/watch?v=')) {
            const videoId = url.split('v=')[1].split('&')[0];
            return `https://www.youtube.com/embed/${videoId}`;
        }
        
        // YouTube short URL: https://youtu.be/VIDEO_ID
        if (url.includes('youtu.be/')) {
            const videoId = url.split('youtu.be/')[1].split('?')[0];
            return `https://www.youtube.com/embed/${videoId}`;
        }
        
        // YouTube embed URL: https://www.youtube.com/embed/VIDEO_ID
        if (url.includes('youtube.com/embed/')) {
            return url;
        }
        
        // If not recognized, return original URL
        return url;
    }

    openBtns.forEach(btn => {
        btn.addEventListener("click", () => {
            const originalUrl = btn.getAttribute("data-trailer");
            let embedUrl = getEmbedUrl(originalUrl);
            
            // Add autoplay parameter
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
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.users.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Server\ct27501-project-BanhNhatKhang\app\Views/users-views/Phim/PhimDangChieu.blade.php ENDPATH**/ ?>