<?php
include '../config/db.php';

include '../models/SinhVien.php';

$MaSV = $_GET['MaSV'];
$sinhVienModel = new SinhVien($conn);
$student = $sinhVienModel->getSinhVienById($MaSV);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết sinh viên</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gradient-to-r from-gray-100 to-gray-300">
    <div class="container mx-auto p-8 bg-white rounded-lg shadow-xl mt-10">
        <h1 class="text-4xl font-extrabold text-center text-blue-600 mb-8">Chi Tiết Sinh Viên</h1>
        <div class="bg-blue-50 p-6 rounded-lg border border-blue-200 shadow-md">
            <p class="text-gray-800 text-lg mb-4"><strong>Mã SV:</strong> <?php echo $student['MaSV']; ?></p>
            <p class="text-gray-800 text-lg mb-4"><strong>Họ Tên:</strong> <?php echo $student['HoTen']; ?></p>
            <p class="text-gray-800 text-lg mb-4"><strong>Giới Tính:</strong> <?php echo $student['GioiTinh']; ?></p>
            <p class="text-gray-800 text-lg mb-4"><strong>Ngày Sinh:</strong> <?php echo $student['NgaySinh']; ?></p>
            <div class="flex items-center mb-4">
                <strong class="text-gray-800 text-lg mr-4">Hình:</strong>
                <img src="<?php echo $student['Hinh']; ?>" alt="Ảnh sinh viên" class="w-32 h-32 rounded-lg shadow-lg">
            </div>
            <p class="text-gray-800 text-lg mb-4"><strong>Ngành Học:</strong> <?php echo $student['MaNganh']; ?></p>
        </div>
        <div class="flex justify-center mt-6">
            <a href="index.php"
                class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 px-6 rounded-lg shadow-lg transition duration-300">Quay
                Lại</a>
        </div>
    </div>
</body>

</html>