<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        .user-dropdown-link:hover,
        .user-dropdown-link:focus {
            color: #fff;
            text-decoration: none;
            background: none;
            box-shadow: none;
        }
        .dropdown-menu .dropdown-item {
            color: #212529 !important;
            background-color: transparent;
        }

        .user-dropdown-link{
            color:white;
        }
    </style>
</head>
<body>
<header>

<div class="container">
    <div class="row align-items-center">
        <div class="col">
            <!-- Header section -->
            <div class="bg-header text-white py-2">
                <div class="container">
                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                        <a class="navbar-brand" href="#">
                            <div class="khf-logo">
                            <div class="logo-icon"></div>
                            <div class="logo-text">
                                <span class="cinema">CINEMA</span>
                                <span class="khf">KHF</span>
                            </div>
                            </div>
                        </a>
                        <div class="dropdown " >
                                <a href="#" class="d-flex align-items-center user-dropdown-link text-decoration-none " id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="outline: none;">
                                    <i class="bi bi-person-circle fs-4 me-2"></i>
                                    <span class="fw-semibold user-name">Nguyễn Văn A</span>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end mt-0 shadow-sm" aria-labelledby="userDropdown">
                                    <li>
                                        <a class="dropdown-item hover-red-nav" href="/static/html/users/ThongTinCaNhan/ThongTinCaNhan.php">Thông tin cá nhân</a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item hover-red-nav" href="/static/html/users/LichSuDatVe/LichSuDatVe.php">Lịch sử đặt vé</a>
                                    </li>
                                </ul>
                            </div>
                    </div>
                </div>

            <!-- Navigation menu -->
            <nav class="navbar navbar-expand-lg custom-navbar">
            <div class="container px-0">
                <button
                class="navbar-toggler"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarNav"
                >
                <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav mx-auto">
                        <li class="nav-item">
                            <a class="nav-link <?= ($activePage == 'home') ? 'active' : '' ?>" href="/index.php">LỊCH CHIẾU</a>
                        </li>
                        <li class="nav-item  dropdown">
                            <a class="nav-link <?= ($activePage == 'movies') ? 'active' : '' ?>"
                             href="/static/html/Phim.php" id="phimDropdown"
                             role="button"
                             data-bs-toggle="dropdown"
                             aria-expanded="false">PHIM</a>
                            <ul class="dropdown-menu" aria-labelledby="phimDropdown">
                                <li>
                                <a class="dropdown-item hover-red-nav" href="/static/html/users/Phim/PhimDangChieu.php"
                                    >Phim đang chiếu</a
                                >
                                </li>
                                <li>
                                <a class="dropdown-item hover-red-nav" href="#"
                                    >Phim sắp chiếu</a
                                >
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= ($activePage == 'offers') ? 'active' : '' ?>" href="/static/html/users/UuDai/UuDai.php">ƯU ĐÃI</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= ($activePage == 'member') ? 'active' : '' ?>" href="/static/html/users/ThanhVien/ThanhVienKHF.php">THÀNH VIÊN</a>
                        </li>
                    </ul>
                </div>
            </div>
            </nav>
        </div>
    </div>
</div>
</header>
</body>
</html>
