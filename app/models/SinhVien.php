<?php

class SinhVien
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getAllSinhVien()
    {
        $sql = "SELECT * FROM SinhVien";
        $result = $this->conn->query($sql);
        return $result;
    }

    public function getSinhVienById($MaSV)
    {
        $sql = "SELECT * FROM SinhVien WHERE MaSV = '$MaSV'";
        $result = $this->conn->query($sql);
        return $result->fetch_assoc();
    }

    public function createSinhVien($data)
    {
        $sql = "INSERT INTO SinhVien (MaSV, HoTen, GioiTinh, NgaySinh, Hinh, MaNganh) 
                VALUES ('{$data['MaSV']}', '{$data['HoTen']}', '{$data['GioiTinh']}', 
                        '{$data['NgaySinh']}', '{$data['Hinh']}', '{$data['MaNganh']}')";
        return $this->conn->query($sql);
    }

    public function updateSinhVien($MaSV, $data)
    {
        $sql = "UPDATE SinhVien SET HoTen = '{$data['HoTen']}', GioiTinh = '{$data['GioiTinh']}', 
                NgaySinh = '{$data['NgaySinh']}', Hinh = '{$data['Hinh']}', 
                MaNganh = '{$data['MaNganh']}' WHERE MaSV = '$MaSV'";
        return $this->conn->query($sql);
    }

    public function deleteSinhVien($MaSV)
    {
        $sql = "DELETE FROM SinhVien WHERE MaSV = '$MaSV'";
        return $this->conn->query($sql);
    }
}