-- Tạo database
CREATE DATABASE Test1;
USE Test1;

-- Tạo bảng NganhHoc
CREATE TABLE NganhHoc (
    MaNganh CHAR(4) PRIMARY KEY,
    TenNganh VARCHAR(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB;

-- Tạo bảng SinhVien
CREATE TABLE SinhVien (
    MaSV CHAR(10) PRIMARY KEY,
    HoTen VARCHAR(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
    GioiTinh VARCHAR(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
    NgaySinh DATE,
    Hinh VARCHAR(255),
    MaNganh CHAR(4),
    FOREIGN KEY (MaNganh) REFERENCES NganhHoc(MaNganh) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Tạo bảng HocPhan
CREATE TABLE HocPhan (
    MaHP CHAR(6) PRIMARY KEY,
    TenHP VARCHAR(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
    SoTinChi INT NOT NULL
) ENGINE=InnoDB;

-- Tạo bảng DangKy (Dùng AUTO_INCREMENT thay vì IDENTITY)
CREATE TABLE DangKy (
    MaDK INT AUTO_INCREMENT PRIMARY KEY,
    NgayDK DATE,
    MaSV CHAR(10),
    FOREIGN KEY (MaSV) REFERENCES SinhVien(MaSV) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Tạo bảng ChiTietDangKy (bảng trung gian)
CREATE TABLE ChiTietDangKy (
    MaDK INT,
    MaHP CHAR(6),
    PRIMARY KEY (MaDK, MaHP),
    FOREIGN KEY (MaDK) REFERENCES DangKy(MaDK) ON DELETE CASCADE,
    FOREIGN KEY (MaHP) REFERENCES HocPhan(MaHP) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Chèn dữ liệu vào NganhHoc
INSERT INTO NganhHoc (MaNganh, TenNganh) VALUES 
('CNTT', 'Công nghệ thông tin'),
('QTKD', 'Quản trị kinh doanh');

-- Chèn dữ liệu vào SinhVien
INSERT INTO SinhVien (MaSV, HoTen, GioiTinh, NgaySinh, Hinh, MaNganh) VALUES 
('0123456789', 'Võ Nghĩa Kỳ', 'Nam', '2003-11-14', 'https://assets.grok.com/users/027ea617-be8a-44e5-a280-4049768e8b91/feY9h0gi4Pz54XSR-profile-picture.webp', 'CNTT'),
('9876543210', 'Nguyễn Thị B', 'Nữ', '2000-07-03', 'https://th.bing.com/th/id/OIP.7ITF2gx8_a3s4NbnDOpZzAHaHa?rs=1&pid=ImgDetMain', 'QTKD');

-- Chèn dữ liệu vào HocPhan
INSERT INTO HocPhan (MaHP, TenHP, SoTinChi) VALUES 
('CNTT01', 'Lập trình C', 3),
('CNTT02', 'Cơ sở dữ liệu', 2),
('QTKD01', 'Kinh tế vi mô', 2),
('QTKD02', 'Xác suất thống kê 1', 3);

-- Truy vấn dữ liệu
SELECT * FROM SinhVien;
SELECT * FROM NganhHoc;
SELECT * FROM HocPhan;
SELECT * FROM DangKy;
SELECT * FROM ChiTietDangKy;
