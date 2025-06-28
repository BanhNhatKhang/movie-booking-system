<?php

//user
$router->add('GET', '/', 'HomeController@index');

//phim
$router->add('GET', '/phim-dang-chieu', 'MovieController@phimDangChieu');
$router->add('GET', '/phim-sap-chieu', 'MovieController@phimSapChieu');
$router->add('GET', '/chi-tiet-phim', 'MovieController@chiTietPhim');
$router->add('GET', '/chon-ghe', 'MovieController@chonGhe');

//thanhtoan
$router->add('GET', '/thanh-toan', 'PayController@thanhToan');
$router->add('POST', '/xu-ly-thanh-toan', 'PayController@xuLyThanhToan');

//auth
$router->add('GET', '/dang-ky', 'AuthController@dangKy');
$router->add('POST', '/dang-ky', 'AuthController@xuLyDangKy');
$router->add('GET', '/dang-nhap', 'AuthController@dangnhap');
$router->add('POST', '/dang-nhap', 'AuthController@xuLyDangNhap');
$router->add('GET', '/dang-xuat', 'AuthController@dangXuat');

//uudai
$router->add('GET', '/uu-dai', 'UuDaiController@uudai');

//member
$router->add('GET', '/lich-su-dat-ve', 'LichSuDatVeController@lichsudatve');
$router->add('GET', '/thong-tin-ca-nhan', 'ThongTinCaNhanController@thongTinCaNhan');
$router->add('POST', '/doi-mat-khau', 'ThongTinCaNhanController@doiMatKhau');
//thanhvien
$router->add('GET', '/thanh-vien', 'ThanhVienKHFController@thanhvien');
$router->add('GET', '/diem-thuong', 'ThanhVienKHFController@diemthuong');
$router->add('GET', '/cap-do', 'ThanhVienKHFController@capdo');
$router->add('GET', '/qua-tang', 'ThanhVienKHFController@quatang');
//admin
//dashboard
$router->add('GET', '/dashboard', 'DashboardController@dashboard');

//switch
$router->add('GET', '/admin/switch-to-user', 'AdminModeController@switchToUser');
$router->add('GET', '/admin/switch-to-admin', 'AdminModeController@switchToAdmin');

//trangchu
$router->add('GET', '/quan-ly-trang-chu', 'QuanLyTrangChuController@trangChu');
$router->add('GET', '/them-poster', 'QuanLyTrangChuController@themPoster');
$router->add('POST', '/them-poster', 'QuanLyTrangChuController@storePoster');
$router->add('GET', '/sua-poster', 'QuanLyTrangChuController@suaPoster');
$router->add('POST', '/sua-poster', 'QuanLyTrangChuController@updatePoster');
$router->add('GET', '/them-uu-dai', 'QuanLyTrangChuController@themUuDai');
$router->add('POST', '/them-uu-dai', 'QuanLyTrangChuController@storeUuDai');
$router->add('GET', '/sua-uu-dai', 'QuanLyTrangChuController@suauuDai');
$router->add('POST', '/sua-uu-dai', 'QuanLyTrangChuController@updateUuDai');

//quanlyphim
$router->add('GET', '/quan-ly-phim', 'QuanLyPhimController@quanLyPhim');
$router->add('GET', '/them-phim', 'QuanLyPhimController@themPhim');
$router->add('POST', '/them-phim', 'QuanLyPhimController@storePhim');
$router->add('GET', '/sua-phim', 'QuanLyPhimController@suaPhim');
$router->add('POST', '/sua-phim', 'QuanLyPhimController@updatePhim');
$router->add('GET', '/doi-trang-thai-phim', 'QuanLyPhimController@doiTrangThaiPhim');
$router->add('POST', '/doi-trang-thai-phim', 'QuanLyPhimController@changeStatus');

//quanlylichchieu
$router->add('GET', '/quan-ly-lich-chieu', 'QuanLyLichChieuController@quanLyLichChieu');
$router->add('GET', '/them-lich-chieu', 'QuanLyLichChieuController@themLichChieu');
$router->add('POST', '/them-lich-chieu', 'QuanLyLichChieuController@storeLichChieu');
$router->add('GET', '/sua-lich-chieu', 'QuanLyLichChieuController@suaLichChieu');
$router->add('POST', '/sua-lich-chieu', 'QuanLyLichChieuController@updateLichChieu');

//phongghe
$router->add('GET', '/quan-ly-phong-ghe', 'QuanLyPhongGheController@quanLyPhongGhe');

//quanlydondatve
$router->add('GET', '/quan-ly-don-dat-ve', 'QuanLyDonDatVeController@quanLyDonDatVe');
$router->add('GET', '/chi-tiet-don-dat-ve', 'QuanLyDonDatVeController@chiTietDonDatVe');
$router->add('GET', '/xuat-ve-word', 'QuanLyDonDatVeController@xuatve');
$router->add('POST', '/huy-don-dat-ve', 'QuanLyDonDatVeController@huyDonDatVe');

//datvetaiquay
$router->add('GET', '/dat-ve-tai-quay', 'DatVeTaiQuayController@datVeTaiQuay');
$router->add('POST', '/dat-ve-tai-quay', 'DatVeTaiQuayController@storeDatVe');

//uudai
$router->add('GET', '/quan-ly-uu-dai', 'UuDaiAdminController@uuDai');
$router->add('GET', '/them-uu-dai', 'UuDaiAdminController@themUuDai');
$router->add('POST', '/them-uu-dai', 'UuDaiAdminController@storeUuDai');
$router->add('GET', '/sua-uu-dai', 'UuDaiAdminController@suaUuDai');
$router->add('POST', '/sua-uu-dai', 'UuDaiAdminController@updateUuDai');

//user
$router->add('GET', '/quan-ly-nguoi-dung', 'QuanLyNguoiDungController@quanLyNguoiDung');
$router->add('GET', '/chi-tiet-nguoi-dung', 'QuanLyNguoiDungController@chiTietNguoiDung');
$router->add('GET', '/khoa-nguoi-dung', 'QuanLyNguoiDungController@khoaNguoiDung');
$router->add('POST', '/khoa-nguoi-dung', 'QuanLyNguoiDungController@processKhoaNguoiDung');
$router->add('GET', '/sua-nguoi-dung', 'QuanLyNguoiDungController@suaNguoiDung');
$router->add('POST', '/sua-nguoi-dung', 'QuanLyNguoiDungController@xuLySuaNguoiDung');

//thanhtoan
$router->add('GET', '/quan-ly-thanh-toan', 'QuanLyThanhToanController@quanLyThanhToan');
$router->add('GET', '/chi-tiet-thanh-toan', 'QuanLyThanhToanController@chiTietThanhToan');

//nhanvien - CRUD hoàn chỉnh
$router->add('GET', '/quan-ly-nhan-vien', 'QuanLyNhanVienController@index');
$router->add('GET', '/them-nhan-vien', 'QuanLyNhanVienController@create');
$router->add('POST', '/them-nhan-vien', 'QuanLyNhanVienController@store');
$router->add('GET', '/sua-nhan-vien', 'QuanLyNhanVienController@edit');
$router->add('POST', '/sua-nhan-vien', 'QuanLyNhanVienController@update');
$router->add('GET', '/xoa-nhan-vien', 'QuanLyNhanVienController@delete');
$router->add('POST', '/xoa-nhan-vien', 'QuanLyNhanVienController@destroy');