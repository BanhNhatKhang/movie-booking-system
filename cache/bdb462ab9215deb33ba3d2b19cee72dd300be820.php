


<?php $__env->startSection('content'); ?>
<main>
    <div class="container">
        <div class="row div-pad">
            <div class="col">
                <!--Poster quảng cáo phim-->
                <div id="carouselExample" class="carousel slide " data-bs-ride="carousel" data-bs-interval="3000">
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <img src="/static/imgs/tham-tu-kien-3-1744984172402.jpg" class="d-block w-100 carousel-img img-fluid" alt="...">
                        </div>
                        <div class="carousel-item">
                            <img src="/static/imgs/doreamon_900x448.jpg" class="d-block w-100 carousel-img img-fluid" alt="...">
                        </div>
                        <div class="carousel-item">
                            <img src="/static/imgs/anime-spirited-away-5986-1901.jpg" class="d-block w-100 carousel-img img-fluid" alt="...">
                        </div>
                        <div class="carousel-item">
                            <img src="/static/imgs/conan.jpg" class="d-block w-100 carousel-img img-fluid" alt="...">
                        </div>
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
                            <span class=responsive-text>PHIM ĐANG CHIẾU</span>
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
                        <!-- Tab PHIM ĐANG CHIẾU - Dynamic Data -->
                        <div
                        class="tab-pane fade show active"
                        id="dang-chieu"
                        role="tabpanel"
                        aria-labelledby="dang-chieu-tab"
                        >
                        <div id="carouselDangChieu" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5000">
                            <div class="carousel-inner">
                                <?php if(count($phimDangChieu ?? []) > 0): ?>
                                    <?php
                                        $chunks = array_chunk($phimDangChieu, 4);
                                    ?>
                                    <?php $__currentLoopData = $chunks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $chunk): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="carousel-item <?php echo e($index === 0 ? 'active' : ''); ?>">
                                        <div class="row">
                                            <?php $__currentLoopData = $chunk; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $phim): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <div class="col-6 col-md-4 col-lg-3">
                                                <div class="movie-card">
                                                    <img src="<?php echo e($phim['pt_anhposter'] ?? '/static/imgs/placeholder-movie.jpg'); ?>" class="img-fluid carousel-img-inner" alt="<?php echo e($phim['p_tenphim']); ?>" onerror="this.src='/static/imgs/placeholder-movie.jpg'">
                                                    <a href="/dat-ve?phim=<?php echo e($phim['p_maphim']); ?>" class="book-hover-btn">Đặt vé</a>
                                                </div>  
                                                <div class="movie-card-text">
                                                    <p><?php echo e($phim['p_tenphim']); ?></p>
                                                </div>
                                                <div class="book-bg">
                                                    <a href="/dat-ve?phim=<?php echo e($phim['p_maphim']); ?>" class="book-tk">Đặt vé</a>
                                                </div>                                                                    
                                            </div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                    </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php else: ?>
                                    <div class="carousel-item active">
                                        <div class="row">
                                            <div class="col-6 col-md-4 col-lg-3">
                                                <div class="movie-card">
                                                    <img src="/static/imgs/latmat1.jpg" class="img-fluid carousel-img-inner" alt="...">
                                                    <a href="#" class="book-hover-btn">Đặt vé</a>
                                                </div>  
                                                <div class="movie-card-text">
                                                    <p>Chưa có phim đang chiếu</p>
                                                </div>
                                                <div class="book-bg">
                                                    <a href="#" class="book-tk">Đặt vé</a>
                                                </div>                                                                    
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <button class="carousel-control-prev" type="button" data-bs-target="#carouselDangChieu" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon"></span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#carouselDangChieu" data-bs-slide="next">
                                <span class="carousel-control-next-icon"></span>
                            </button>
                        </div>
                    </div>
                    <!-- Tab PHIM SẮP CHIẾU - Dynamic Data -->
                    <div
                    class="tab-pane fade"
                    id="sap-chieu"
                    role="tabpanel"
                    aria-labelledby="sap-chieu-tab"
                    >
                        <div id="carouselSapChieu" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5000">
                            <div class="carousel-inner">
                                <?php if(count($phimSapChieu ?? []) > 0): ?>
                                    <?php
                                        $chunks = array_chunk($phimSapChieu, 4);
                                    ?>
                                    <?php $__currentLoopData = $chunks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $chunk): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="carousel-item <?php echo e($index === 0 ? 'active' : ''); ?>">
                                        <div class="row">
                                            <?php $__currentLoopData = $chunk; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $phim): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <div class="col-6 col-md-4 col-lg-3">
                                                <img src="<?php echo e($phim['pt_anhposter'] ?? '/static/imgs/C2-1.webp'); ?>" class="img-fluid carousel-img-inner" alt="<?php echo e($phim['p_tenphim']); ?>" onerror="this.src='/static/imgs/C2-1.webp'">
                                                <div class="movie-card-text">
                                                    <p><?php echo e($phim['p_tenphim']); ?></p>
                                                </div>
                                                <div class="book-bg">
                                                    <a href="#" class="book-tk">Sắp chiếu</a>
                                                </div>
                                            </div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                    </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php else: ?>
                                    <div class="carousel-item active">
                                        <div class="row">
                                            <div class="col-6 col-md-4 col-lg-3">
                                                <img src="/static/imgs/C2-1.webp" class="img-fluid carousel-img-inner" alt="...">
                                                <div class="movie-card-text">
                                                    <p>Chưa có phim sắp chiếu</p>
                                                </div>
                                                <div class="book-bg">
                                                    <a href="#" class="book-tk">Đặt vé</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
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
                <!-- ƯU ĐÃI VÀ DỊCH VỤ - Dynamic Data -->
                <div>
                    <h1 class="align-text">ƯU ĐÃI VÀ DỊCH VỤ</h1><br>
                    
                    <div class="row">
                        <?php $__empty_1 = true; $__currentLoopData = $uuDaiList ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $uuDai): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <?php if($index < 4): ?>
                            <div class="col-6 col-md-3 mb-3">
                                <a href="/uu-dai">
                                    <img src="<?php echo e($uuDai['udtc_anhuudai']); ?>" 
                                         alt="<?php echo e($uuDai['udtc_tenuudai']); ?>" 
                                         class="img-fluid service-img" 
                                         onerror="this.src='/static/imgs/DV-<?php echo e($index + 1); ?>.jpg'">
                                </a>                             
                            </div>
                            <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            
                            <div class="col-6 col-md-3 mb-3">
                                <a href="/uu-dai">
                                    <img src="/static/imgs/DV-1.jpg" alt="..." class="img-fluid service-img">
                                </a>                             
                            </div>
                            <div class="col-6 col-md-3 mb-3">
                                <a href="/uu-dai">
                                    <img src="/static/imgs/DV-2.png" alt="..." class="img-fluid service-img">
                                </a>                              
                            </div>
                            <div class="col-6 col-md-3 mb-3">
                                <a href="/uu-dai">
                                    <img src="/static/imgs/DV-3.jpg" alt="..." class="img-fluid service-img">
                                </a>                              
                            </div>
                            <div class="col-6 col-md-3 mb-3">
                                <a href="/uu-dai">
                                    <img src="/static/imgs/DV-4.jpg" alt="..." class="img-fluid service-img">
                                </a>                               
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div><br>
        </div>
    </div>
</main>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.users.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Server\ct27501-project-BanhNhatKhang\app\Views/index.blade.php ENDPATH**/ ?>