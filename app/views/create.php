<?php
include '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $MaSV = $_POST['MaSV'];
    $HoTen = $_POST['HoTen'];
    $GioiTinh = $_POST['GioiTinh'];
    $NgaySinh = $_POST['NgaySinh'];
    $MaNganh = $_POST['MaNganh'];

    // Xử lý upload file
    $target_dir = "../../uploads/";
    $target_file = $target_dir . basename($_FILES["Hinh"]["name"]);
    move_uploaded_file($_FILES["Hinh"]["tmp_name"], $target_file);

    // Thêm dữ liệu vào bảng SinhVien
    $sql = "INSERT INTO SinhVien (MaSV, HoTen, GioiTinh, NgaySinh, Hinh, MaNganh) 
            VALUES ('$MaSV', '$HoTen', '$GioiTinh', '$NgaySinh', '$target_file', '$MaNganh')";
    if ($conn->query($sql) === TRUE) {
        header("Location: index.php");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm Sinh Viên</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gradient-to-r from-gray-100 to-blue-100 h-screen flex items-center justify-center">
    <div class="bg-white p-6 rounded-lg shadow-lg w-96">
        <h1 class="text-3xl font-bold text-center text-blue-600 mb-6">Thêm Sinh Viên</h1>
        <form method="POST" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Mã SV:</label>
                <input type="text" name="MaSV"
                    class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-500"
                    placeholder="Nhập mã sinh viên" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Họ Tên:</label>
                <input type="text" name="HoTen"
                    class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-500"
                    placeholder="Nhập họ tên" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Giới Tính:</label>
                <select name="GioiTinh"
                    class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-500">
                    <option value="Nam">Nam</option>
                    <option value="Nữ">Nữ</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Ngày Sinh:</label>
                <input type="date" name="NgaySinh"
                    class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Hình:</label>
                <input type="file" name="Hinh"
                    class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Ngành Học:</label>
                <select name="MaNganh"
                    class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-500" required>
                    <option value="" disabled selected>Chọn ngành học</option>
                    <?php
                    $result = $conn->query("SELECT MaNganh, TenNganh FROM NganhHoc");
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='{$row['MaNganh']}'>{$row['TenNganh']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="text-center">
                <button type="submit"
                    class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 rounded-lg shadow-md transition duration-300">
                    Lưu Thông Tin
                </button>
            </div>
            <div class="text-center mt-4">
                <a href="index.php" class="text-blue-500 hover:underline text-sm font-medium">Quay Lại</a>
            </div>
        </form>
    </div>
</body>

</html>