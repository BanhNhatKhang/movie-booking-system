-- Bảng POSTER
CREATE TABLE poster (
    pt_maposter VARCHAR(7) PRIMARY KEY,
    pt_anhposter VARCHAR(255)
);

-- Bảng NGUOI_DUNG
CREATE TABLE nguoi_dung (
    nd_id VARCHAR(10) PRIMARY KEY,
    nd_hoten VARCHAR(255),
    nd_ngaysinh DATE,
    nd_gioitinh BOOLEAN,
    nd_sdt VARCHAR(10),
    nd_cccd VARCHAR(12),
    nd_email VARCHAR(255),
    nd_tendangnhap VARCHAR(255),
    nd_matkhau VARCHAR(255),
    nd_ngaydangky DATE,
    nd_trangthai VARCHAR(15),
    nd_role VARCHAR(10)
);

-- Bảng PHONG_CHIEU
CREATE TABLE phong_chieu (
    pc_maphongchieu VARCHAR(10) PRIMARY KEY,
    pc_tenphong VARCHAR(100),
    pc_loaiphong VARCHAR(20)
);

-- Bảng GHE
CREATE TABLE ghe (
    g_maghe VARCHAR(10) PRIMARY KEY,
    g_loaighe VARCHAR(20),
    g_trangthai VARCHAR(20),
    pc_maphongchieu VARCHAR(10) REFERENCES phong_chieu(pc_maphongchieu)
);


-- Bảng PHIM
CREATE TABLE phim (
    p_maphim VARCHAR(10) PRIMARY KEY,
    p_tenphim VARCHAR(255),
    p_theloai VARCHAR(255),
    p_thoiluong INTEGER,
    p_phathanh DATE,
    p_mota TEXT,
    p_trailer VARCHAR(255),
    p_trangthai VARCHAR(20),
    p_dienvien VARCHAR(1024),
    p_daodien VARCHAR(255),
    p_poster VARCHAR(255)
);

-- Bảng UU_DAI
CREATE TABLE uu_dai (
    ud_mauudai VARCHAR(10) PRIMARY KEY,
    ud_tenuudai VARCHAR(255),
    ud_anhuudai VARCHAR(255),
    ud_loaiuudai VARCHAR(255),
    ud_noidung VARCHAR(1024),
    ud_thoigianbatdau DATE,
    ud_thoigianketthuc DATE,
    ud_trangthai VARCHAR(15)
);

-- Bảng UU_DAI_TRANG_CHU
CREATE TABLE uu_dai_trang_chu (
    udtc_mauudai VARCHAR(10) PRIMARY KEY,
    udtc_anhuudai VARCHAR(255)
);

-- Bảng LOAI_VE
CREATE TABLE loai_ve (
    lv_maloaive VARCHAR(10) PRIMARY KEY,
    lv_tenloaive VARCHAR(255),
    lv_giatien NUMERIC(10, 0)
);

-- Bảng LICH_CHIEU
CREATE TABLE lich_chieu (
    lc_malichchieu VARCHAR(25) PRIMARY KEY,
    lc_ngaychieu DATE,
    lc_giobatdau TIMESTAMP,
    lc_trangthai VARCHAR(15),
    p_maphim VARCHAR(10) REFERENCES phim(p_maphim),
    pc_maphongchieu VARCHAR(10) REFERENCES phong_chieu(pc_maphongchieu)
);

-- Bảng THANH_TOAN
CREATE TABLE thanh_toan (
    tt_mathanhtoan VARCHAR(30) PRIMARY KEY,
    tt_sotien NUMERIC(10, 0),
    tt_phuongthuc VARCHAR(255),
    tt_thoigianthanhtoan TIMESTAMP,
    nd_id VARCHAR(10) REFERENCES nguoi_dung(nd_id)
);

-- Bảng VE
CREATE TABLE ve (
    v_mave VARCHAR(10) PRIMARY KEY,
    v_ngaydat DATE,
    v_tongtien NUMERIC(10, 0),
    v_trangthai VARCHAR(20) DEFAULT 'chua_in', 
    nd_id VARCHAR(10) REFERENCES nguoi_dung(nd_id),
    tt_mathanhtoan VARCHAR(30) REFERENCES thanh_toan(tt_mathanhtoan),
    g_maghe VARCHAR(10) REFERENCES ghe(g_maghe),
    lv_maloaive VARCHAR(10) REFERENCES loai_ve(lv_maloaive),
    lc_malichchieu VARCHAR(25) REFERENCES lich_chieu(lc_malichchieu)
);