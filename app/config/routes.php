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
$router->add('POST', '/sua-poster', 'QuanLyTrangChuController@capNhatPoster');
$router->add('GET', '/danh-sach-poster', 'QuanLyTrangChuController@danhSachPoster');
$router->add('POST', '/cap-nhat-poster', 'QuanLyTrangChuController@capNhatPoster');
$router->add('GET', '/xoa-poster', 'QuanLyTrangChuController@xoaPoster');
$router->add('POST', '/luu-poster', 'QuanLyTrangChuController@luuPoster');

$router->add('GET', '/them-uu-dai-home', 'QuanLyTrangChuController@themUuDai');
$router->add('POST', '/luu-uu-dai-home', 'QuanLyTrangChuController@luuUuDai');
$router->add('GET', '/sua-uu-dai-home', 'QuanLyTrangChuController@suaUuDai');
$router->add('POST', '/cap-nhat-uu-dai-home', 'QuanLyTrangChuController@capNhatUuDai');
$router->add('GET', '/xoa-uu-dai-home', 'QuanLyTrangChuController@xoaUuDai');


//quanlyphim - CRUD hoàn chỉnh
$router->add('GET', '/quan-ly-phim', 'QuanLyPhimController@quanLyPhim');       
$router->add('GET', '/them-phim', 'QuanLyPhimController@themPhim');              
$router->add('POST', '/luu-phim', 'QuanLyPhimController@luuPhim');              
$router->add('GET', '/sua-phim', 'QuanLyPhimController@suaPhim');             
$router->add('POST', '/cap-nhat-phim', 'QuanLyPhimController@capNhatPhim');      
$router->add('GET', '/doi-trang-thai-phim', 'QuanLyPhimController@doiTrangThaiPhim');  
$router->add('POST', '/cap-nhat-trang-thai', 'QuanLyPhimController@capNhatTrangThai'); 

//quanlylichchieu
$router->add('GET', '/quan-ly-lich-chieu', 'QuanLyLichChieuController@quanLyLichChieu');
$router->add('GET', '/them-lich-chieu', 'QuanLyLichChieuController@themLichChieu');
$router->add('POST', '/them-lich-chieu', 'QuanLyLichChieuController@storeLichChieu');
$router->add('GET', '/sua-lich-chieu', 'QuanLyLichChieuController@suaLichChieu');
$router->add('POST', '/sua-lich-chieu', 'QuanLyLichChieuController@updateLichChieu');

//phongghe
$router->add('GET', '/quan-ly-phong-ghe', 'QuanLyPhongGheController@quanLyPhongGhe');
$router->add('GET', '/quan-ly-phong-ghe', 'QuanLyPhongGheController@quanLyPhongGhe');
$router->add('GET', '/tao-so-do-ghe', 'QuanLyPhongGheController@showTaoSoDoGhe');
$router->add('POST', '/luu-so-do-ghe', 'QuanLyPhongGheController@luuSoDoGhe');
$router->add('GET', '/check-room-available', 'QuanLyPhongGheController@checkRoomAvailable');
$router->add('POST', '/update-seat-status', 'QuanLyPhongGheController@updateSeatStatus');
$router->add('POST', '/update-seat-type', 'QuanLyPhongGheController@updateSeatType');
$router->add('GET', '/get-seat-statistics', 'QuanLyPhongGheController@getSeatStatistics');
$router->add('POST', '/bulk-update-seat-types', 'QuanLyPhongGheController@bulkUpdateSeatTypes');

//quanlydondatve
$router->add('GET', '/quan-ly-don-dat-ve', 'QuanLyDonDatVeController@quanLyDonDatVe');
$router->add('GET', '/chi-tiet-don-dat-ve', 'QuanLyDonDatVeController@chiTietDonDatVe');
$router->add('GET', '/xuat-ve-word', 'QuanLyDonDatVeController@xuatveWord');
$router->add('POST', '/huy-don-dat-ve', 'QuanLyDonDatVeController@huyDonDatVe');
$router->add('POST', '/process-ve-print', 'QuanLyDonDatVeController@processVePrint');

//datvetaiquay
$router->add('GET', '/dat-ve-tai-quay', 'DatVeTaiQuayController@datVeTaiQuay');
$router->add('POST', '/dat-ve-tai-quay', 'DatVeTaiQuayController@storeDatVe');

// UuDai Admin Routes
$router->add('GET', '/quan-ly-uu-dai', 'UuDaiAdminController@uuDai');
$router->add('GET', '/them-uu-dai', 'UuDaiAdminController@themUuDai');
$router->add('POST', '/them-uu-dai', 'UuDaiAdminController@storeUuDai');
$router->add('GET', '/sua-uu-dai', 'UuDaiAdminController@suaUuDai');
$router->add('POST', '/sua-uu-dai', 'UuDaiAdminController@updateUuDai');
$router->add('GET', '/xoa-uu-dai', 'UuDaiAdminController@deleteUuDai');

//quanlynguoidung
$router->add('GET', '/quan-ly-nguoi-dung', 'QuanLyNguoiDungController@quanLyNguoiDung');
$router->add('GET', '/chi-tiet-nguoi-dung', 'QuanLyNguoiDungController@chiTietNguoiDung');
$router->add('GET', '/sua-nguoi-dung', 'QuanLyNguoiDungController@suaNguoiDung');
$router->add('POST', '/sua-nguoi-dung', 'QuanLyNguoiDungController@suaNguoiDung');
$router->add('GET', '/khoa-nguoi-dung', 'QuanLyNguoiDungController@khoaNguoiDung');
$router->add('POST', '/khoa-nguoi-dung', 'QuanLyNguoiDungController@khoaNguoiDung');
$router->add('POST', '/reset-mat-khau', 'QuanLyNguoiDungController@resetMatKhau');

//thanhtoan
$router->add('GET', '/quan-ly-thanh-toan', 'QuanLyThanhToanController@quanLyThanhToan');
$router->add('GET', '/chi-tiet-thanh-toan', 'QuanLyThanhToanController@chiTietThanhToan');

//nhanvien
$router->add('GET', '/quan-ly-nhan-vien', 'QuanLyNhanVienController@quanLyNhanVien');
$router->add('GET', '/chi-tiet-nhan-vien', 'QuanLyNhanVienController@chiTietNhanVien');
$router->add('GET', '/sua-nhan-vien', 'QuanLyNhanVienController@suaNhanVien');
$router->add('POST', '/sua-nhan-vien', 'QuanLyNhanVienController@capNhatNhanVien');
$router->add('GET', '/khoa-nhan-vien', 'QuanLyNhanVienController@khoaNhanVien');
$router->add('GET', '/them-nhan-vien', 'QuanLyNhanVienController@themNhanVien');
$router->add('POST', '/them-nhan-vien', 'QuanLyNhanVienController@storeNhanVien');

// Thông tin cá nhân admin
$router->add('GET', '/admin/thong-tin-ca-nhan', 'AdminController@thongTinCaNhan');

// Đổi mật khẩu admin
$router->add('GET', '/admin/doi-mat-khau', 'AdminController@doiMatKhau');
$router->add('POST', '/admin/xu-ly-doi-mat-khau', 'AdminController@xuLyDoiMatKhau');