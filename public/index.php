<?php
    session_start();
    $activePage = 'home';
    if (isset($_SESSION['user'])) {
        include __DIR__ . '/static/layouts/users/HeaderLogin.php';
    } else {
        include __DIR__ . '/static/layouts/users/Header.php';
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang chủ</title>
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css"
    />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <link rel="stylesheet" href="/static/css/users/HeaderFooter.css">
    <link rel="stylesheet" href="/static/css/users/TrangChu.css">
</head>
<body class="bg-dark">
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
                            <!-- Tab PHIM ĐANG CHIẾU -->
                            <div
                            class="tab-pane fade show active"
                            id="dang-chieu"
                            role="tabpanel"
                            aria-labelledby="dang-chieu-tab"
                            >
                            <div id="carouselDangChieu" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5000">
                                <div class="carousel-inner">
                                    <div class="carousel-item active">
                                        <div class="row">
                                            <div class="col-6 col-md-4 col-lg-3">
                                                <div class="movie-card">
                                                    <img src="/static/imgs/latmat1.jpg" class="img-fluid  carousel-img-inner" alt="...">
                                                    <a href="#" class="book-hover-btn">Đặt vé</a>
                                                </div>  
                                                <div class="movie-card-text">
                                                    <p>Lật mặt 7: Một điều ước</p>
                                                </div>
                                                <div class="book-bg">
                                                    <a href="#" class="book-tk">Đặt vé</a>
                                                </div>                                                                    
                                            </div>
                                            <div class="col-6 col-md-4 col-lg-3">
                                                <div class="movie-card">
                                                    <img src="./static/imgs/1-onl-17217488966071748078747-2402.jpg" class="img-fluid  carousel-img-inner" alt="...">
                                                    <a href="#" class="book-hover-btn">Đặt vé</a>
                                                </div>
                                                <div class="movie-card-text">
                                                    <p>Lật mặt 7: Một điều ước</p>
                                                </div>
                                                <div class="book-bg">
                                                    <a href="#" class="book-tk">Đặt vé</a>
                                                </div>   
                                            </div>
                                            <div class="col-6 col-md-4 col-lg-3">
                                                <div class="movie-card">
                                                    <img src="/static/imgs/conan-movie-26-16932064940221048161862.webp" class="img-fluid  carousel-img-inner" alt="...">
                                                    <a href="#" class="book-hover-btn">Đặt vé</a>
                                                </div>
                                                <div class="movie-card-text">
                                                    <p>Lật mặt 7: Một điều ước</p>
                                                </div>
                                                <div class="book-bg">
                                                    <a href="#" class="book-tk">Đặt vé</a>
                                                </div>
                                            </div>
                                            <div class="col-6 col-md-4 col-lg-3">
                                                <div class="movie-card">
                                                    <img src="/static/imgs/lilo-500_1747389395062.jpg" class="img-fluid carousel-img-inner" alt="...">
                                                    <a href="#" class="book-hover-btn">Đặt vé</a>
                                                </div>
                                                <div class="movie-card-text">
                                                    <p>Lật mặt 7: Một điều ước</p>
                                                </div>
                                                <div class="book-bg">
                                                    <a href="#" class="book-tk">Đặt vé</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="carousel-item">
                                        <div class="row">
                                            <div class="col-6 col-md-4 col-lg-3">
                                                <div class="movie-card">
                                                    <img src="/static/imgs/latmat1.jpg" class="img-fluid  carousel-img-inner" alt="...">
                                                    <a href="#" class="book-hover-btn">Đặt vé</a>
                                                </div>  
                                                <div class="movie-card-text">
                                                    <p>Lật mặt 7: Một điều ước</p>
                                                </div>
                                                <div class="book-bg">
                                                    <a href="#" class="book-tk">Đặt vé</a>
                                                </div>                                                                    
                                            </div>
                                            <div class="col-6 col-md-4 col-lg-3">
                                                <div class="movie-card">
                                                    <img src="/static/imgs/1-onl-17217488966071748078747-2402.jpg" class="img-fluid  carousel-img-inner" alt="...">
                                                    <a href="#" class="book-hover-btn">Đặt vé</a>
                                                </div>
                                                <div class="movie-card-text">
                                                    <p>Lật mặt 7: Một điều ước</p>
                                                </div>
                                                <div class="book-bg">
                                                    <a href="#" class="book-tk">Đặt vé</a>
                                                </div>   
                                            </div>
                                            <div class="col-6 col-md-4 col-lg-3">
                                                <div class="movie-card">
                                                    <img src="/static/imgs/conan-movie-26-16932064940221048161862.webp" class="img-fluid  carousel-img-inner" alt="...">
                                                    <a href="#" class="book-hover-btn">Đặt vé</a>
                                                </div>
                                                <div class="movie-card-text">
                                                    <p>Lật mặt 7: Một điều ước</p>
                                                </div>
                                                <div class="book-bg">
                                                    <a href="#" class="book-tk">Đặt vé</a>
                                                </div>
                                            </div>
                                            <div class="col-6 col-md-4 col-lg-3">
                                                <div class="movie-card">
                                                    <img src="/static/imgs/lilo-500_1747389395062.jpg" class="img-fluid carousel-img-inner" alt="...">
                                                    <a href="#" class="book-hover-btn">Đặt vé</a>
                                                </div>
                                                <div class="movie-card-text">
                                                    <p>Lật mặt 7: Một điều ước</p>
                                                </div>
                                                <div class="book-bg">
                                                    <a href="#" class="book-tk">Đặt vé</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button class="carousel-control-prev" type="button" data-bs-target="#carouselDangChieu" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon"></span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#carouselDangChieu" data-bs-slide="next">
                                    <span class="carousel-control-next-icon"></span>
                                </button>
                            </div>
                        </div>
                        <!-- Tab PHIM SẮP CHIẾU -->
                        <div
                        class="tab-pane fade"
                        id="sap-chieu"
                        role="tabpanel"
                        aria-labelledby="sap-chieu-tab"
                        >
                            <div id="carouselSapChieu" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5000">
                                <div class="carousel-inner">
                                    <div class="carousel-item active">
                                        <div class="row">
                                            <div class="col-6 col-md-4 col-lg-3">
                                                <img src="/static/imgs/C2-1.webp" class="img-fluid carousel-img-inner" alt="...">
                                                <div class="movie-card-text">
                                                    <p>Lật mật 7: Một điều ước</p>
                                                </div>
                                                <div class="book-bg">
                                                    <a href="#" class="book-tk">Đặt vé</a>
                                                </div>
                                            </div>
                                            <div class="col-6 col-md-4 col-lg-3">
                                                <img src="/static/imgs/C2-2.jpg" class="img-fluid carousel-img-inner" alt="...">
                                                <div class="movie-card-text">
                                                    <p>Lật mật 7: Một điều ước</p>
                                                </div>
                                                <div class="book-bg">
                                                    <a href="#" class="book-tk">Đặt vé</a>
                                                </div>
                                            </div>
                                            <div class="col-6 col-md-4 col-lg-3">
                                                <img src="/static/imgs/C2-4.webp" class="img-fluid carousel-img-inner" alt="...">
                                                <div class="movie-card-text">
                                                    <p>Lật mật 7: Một điều ước</p>
                                                </div>
                                                <div class="book-bg">
                                                    <a href="#" class="book-tk">Đặt vé</a>
                                                </div>
                                            </div>
                                            <div class="col-6 col-md-4 col-lg-3">
                                                <img src="/static/imgs/C2-5.jpg" class="img-fluid carousel-img-inner" alt="...">
                                                <div class="movie-card-text">
                                                    <p>Lật mật 7: Một điều ước</p>
                                                </div>
                                                <div class="book-bg">
                                                    <a href="#" class="book-tk">Đặt vé</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="carousel-item">
                                        <div class="row">
                                            <div class="col-6 col-md-4 col-lg-3">
                                                <img src="/static/imgs/C2-1.webp" class="img-fluid carousel-img-inner" alt="...">
                                                <div class="movie-card-text">
                                                        <p>Lật mật 7: Một điều ước</p>
                                                    </div>
                                                    <div class="book-bg">
                                                        <a href="#" class="book-tk">Đặt vé</a>
                                                </div>
                                            </div>
                                            <div class="col-6 col-md-4 col-lg-3">
                                                <img src="/static/imgs/C2-2.jpg" class="img-fluid carousel-img-inner" alt="...">
                                                <div class="movie-card-text">
                                                    <p>Lật mật 7: Một điều ước</p>
                                                </div>
                                                <div class="book-bg">
                                                    <a href="#" class="book-tk">Đặt vé</a>
                                                </div>
                                            </div>                                         
                                            <div class="col-6 col-md-4 col-lg-3">
                                                <img src="/static/imgs/C2-4.webp" class="img-fluid carousel-img-inner" alt="...">
                                                <div class="movie-card-text">
                                                    <p>Lật mật 7: Một điều ước</p>
                                                </div>
                                                <div class="book-bg">
                                                    <a href="#" class="book-tk">Đặt vé</a>
                                                </div>
                                            </div>
                                            <div class="col-6 col-md-4 col-lg-3">
                                                <img src="/static/imgs/C2-5.jpg" class="img-fluid carousel-img-inner" alt="...">
                                                <div class="movie-card-text">
                                                    <p>Lật mật 7: Một điều ước</p>
                                                </div>
                                                <div class="book-bg">
                                                    <a href="#" class="book-tk">Đặt vé</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
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
                    <div>
                        <h1 class="align-text">ƯU ĐÃI VÀ DỊCH VỤ</h1><br>
                        <div class="row">
                            <div class="col-6 col-md-3 mb-3">
                                <a href="#">
                                    <img src="/static/imgs/DV-1.jpg" alt="..." class="img-fluid service-img">
                                </a>                             
                            </div>
                            <div class="col-6 col-md-3 mb-3">
                                <a href="#">
                                    <img src="/static/imgs/DV-2.png" alt="..." class="img-fluid service-img">
                                </a>                              
                            </div>
                            <div class="col-6 col-md-3 mb-3">
                                <a href="#">
                                    <img src="/static/imgs/DV-3.jpg" alt="..." class="img-fluid service-img">
                                </a>                              
                            </div>
                            <div class="col-6 col-md-3 mb-3">
                                <a href="#">
                                    <img src="/static/imgs/DV-4.jpg" alt="..." class="img-fluid service-img">
                                </a>                               
                            </div>
                        </div>
                    </div>
                </div><br>
            </div>
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>
</body>
</html>
    <?php
        include __DIR__ . '/static/layouts/users/Footer.php';
    ?>