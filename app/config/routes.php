<?php

//user
$router->add('GET', '/', 'HomeController@index');
$router->add('GET', '/phim-dang-chieu', 'MovieController@phimDangChieu');
$router->add('GET', '/phim-sap-chieu', 'MovieController@phimSapChieu');
$router->add('GET', '/chi-tiet-phim', 'MovieController@chiTietPhim');
$router->add('GET', '/chon-ghe', 'MovieController@chonGhe');
$router->add('GET', '/thanh-toan', 'PayController@thanhToan');

//admin
//dashboard
$router->add('GET', '/dashboard', 'DashboardController@dashboard');
//trangchu
$router->add('GET', '/quan-ly-trang-chu', 'QuanLyTrangChuController@trangChu');
$router->add('GET', '/them-poster', 'QuanLyTrangChuController@themPoster');
$router->add('GET', '/sua-poster', 'QuanLyTrangChuController@suaPoster');
$router->add('GET', '/them-uu-dai', 'QuanLyTrangChuController@themUuDai');
$router->add('GET', '/sua-uu-dai', 'QuanLyTrangChuController@suauuDai');

//quanlyphim
$router->add('GET', '/quan-ly-phim', 'QuanLyPhimController@quanLyPhim');
$router->add('GET', '/them-phim', 'QuanLyPhimController@themPhim');
$router->add('GET', '/sua-phim', 'QuanLyPhimController@suaPhim');
$router->add('GET', '/doi-trang-thai-phim', 'QuanLyPhimController@doiTrangThaiPhim');

//quanlylichchieu
$router->add('GET', '/quan-ly-lich-chieu', 'QuanLyLichChieuController@quanLyLichChieu');
$router->add('GET', '/them-lich-chieu', 'QuanLyLichChieuController@themLichChieu');
$router->add('GET', '/sua-lich-chieu', 'QuanLyLichChieuController@suaLichChieu');

//phongghe
$router->add('GET', '/quan-ly-phong-ghe', 'QuanLyPhongGheController@quanLyPhongGhe');

//quanlydondatve
$router->add('GET', '/quan-ly-don-dat-ve', 'QuanLyDonDatVeController@quanLyDonDatVe');
$router->add('GET', '/chi-tiet-don-dat-ve', 'QuanLyDonDatVeController@chiTietDonDatVe');

//datvetaiquay
$router->add('GET', '/dat-ve-tai-quay', 'DatVeTaiQuayController@datVeTaiQuay');

//uudai
$router->add('GET', '/quan-ly-uu-dai', 'UuDaiAdminController@uuDai');
$router->add('GET', '/them-uu-dai', 'UuDaiAdminController@themUuDai');
$router->add('GET', '/sua-uu-dai', 'UuDaiAdminController@suaUuDai');

//user
$router->add('GET', '/quan-ly-nguoi-dung', 'QuanLyNguoiDungController@quanLyNguoiDung');
$router->add('GET', '/chi-tiet-nguoi-dung', 'QuanLyNguoiDungController@chiTietNguoiDung');

//thanhtoan
$router->add('GET', '/quan-ly-thanh-toan', 'QuanLyThanhToanController@quanLyThanhToan');
$router->add('GET', '/chi-tiet-thanh-toan', 'QuanLyThanhToanController@chiTietThanhToan');

//nhanvien
$router->add('GET', '/quan-ly-nhan-vien', 'QuanLyNhanVienController@quanLyNhanVien');