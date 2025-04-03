<?php
include '../config/db.php';

include '../models/SinhVien.php';

$MaSV = $_GET['MaSV'];
$sinhVienModel = new SinhVien($conn);
$student = $sinhVienModel->getSinhVienById($MaSV);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = [
        'HoTen' => $_POST['HoTen'],
        'GioiTinh' => $_POST['GioiTinh'],
        'NgaySinh' => $_POST['NgaySinh'],
        'Hinh' => $_POST['Hinh'],
        'MaNganh' => $_POST['MaNganh'],
    ];
    $sinhVienModel->updateSinhVien($MaSV, $data);
    header("Location: index.php");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa thông tin sinh viên</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gradient-to-r from-gray-100 to-gray-300">
    <div class="container mx-auto p-8 mt-10 bg-white rounded-lg shadow-lg">
        <h1 class="text-4xl font-extrabold text-center text-blue-600 mb-8">Sửa Thông Tin Sinh Viên</h1>
        <form method="POST" class="space-y-6">
            <div class="flex flex-col space-y-4">
                <label class="text-lg font-semibold text-gray-700">Họ Tên:</label>
                <input type="text" name="HoTen"
                    class="w-full border border-gray-300 rounded-lg shadow-sm py-2 px-4 focus:ring-2 focus:ring-blue-500"
                    value="<?php echo $student['HoTen']; ?>" required>
            </div>
            <div class="flex flex-col space-y-4">
                <label class="text-lg font-semibold text-gray-700">Giới Tính:</label>
                <select name="GioiTinh"
                    class="w-full border border-gray-300 rounded-lg shadow-sm py-2 px-4 focus:ring-2 focus:ring-blue-500">
                    <option value="Nam" <?php if ($student['GioiTinh'] == 'Nam') echo 'selected'; ?>>Nam</option>
                    <option value="Nữ" <?php if ($student['GioiTinh'] == 'Nữ') echo 'selected'; ?>>Nữ</option>
                </select>
            </div>
            <div class="flex flex-col space-y-4">
                <label class="text-lg font-semibold text-gray-700">Ngày Sinh:</label>
                <input type="date" name="NgaySinh"
                    class="w-full border border-gray-300 rounded-lg shadow-sm py-2 px-4 focus:ring-2 focus:ring-blue-500"
                    value="<?php echo $student['NgaySinh']; ?>" required>
            </div>
            <div class="flex flex-col space-y-4">
                <label class="text-lg font-semibold text-gray-700">Hình:</label>
                <input type="text" name="Hinh"
                    class="w-full border border-gray-300 rounded-lg shadow-sm py-2 px-4 focus:ring-2 focus:ring-blue-500"
                    value="<?php echo $student['Hinh']; ?>">

                <!-- Hiển thị ảnh -->
                <?php if (!empty($student['Hinh'])): ?>
                <img src="<?php echo $student['Hinh']; ?>" alt="Hình sinh viên"
                    class="w-32 h-32 object-cover rounded-full mt-2 shadow-md">
                <?php endif; ?>
            </div>

            <div class="flex flex-col space-y-4">
                <label class="text-lg font-semibold text-gray-700">Ngành Học:</label>
                <select name="MaNganh"
                    class="w-full border border-gray-300 rounded-lg shadow-sm py-2 px-4 focus:ring-2 focus:ring-blue-500">
                    <?php
                    $result = $conn->query("SELECT MaNganh, TenNganh FROM NganhHoc");
                    while ($row = $result->fetch_assoc()) {
                        $selected = $row['MaNganh'] == $student['MaNganh'] ? 'selected' : '';
                        echo "<option value='{$row['MaNganh']}' $selected>{$row['TenNganh']}</option>";
                    }
                    ?>
                </select>
            </div>
            <button type="submit"
                class="w-full bg-blue-500 hover:bg-blue-700 text-white py-3 rounded-lg shadow-md font-bold transition duration-300">
                Lưu Thông Tin
            </button>
        </form>
    </div>
</body>

</html>