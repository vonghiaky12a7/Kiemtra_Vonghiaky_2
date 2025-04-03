<?php
include '../config/db.php';

// Lấy mã sinh viên từ URL
if (!isset($_GET['MaSV'])) {
    header("Location: index.php");
    exit();
}

$MaSV = $_GET['MaSV'];

// Lấy thông tin sinh viên
$sql = "SELECT * FROM SinhVien WHERE MaSV = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $MaSV);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();

// Kiểm tra nếu sinh viên không tồn tại
if (!$student) {
    echo "<script>alert('Không tìm thấy sinh viên!'); window.location.href='index.php';</script>";
    exit();
}

// Lấy thông tin ngành học
$sqlNganh = "SELECT TenNganh FROM NganhHoc WHERE MaNganh = ?";
$stmtNganh = $conn->prepare($sqlNganh);
$stmtNganh->bind_param("s", $student['MaNganh']);
$stmtNganh->execute();
$resultNganh = $stmtNganh->get_result();
$nganh = $resultNganh->fetch_assoc();

// Xử lý xóa sinh viên
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $sqlDelete = "DELETE FROM SinhVien WHERE MaSV = ?";
    $stmtDelete = $conn->prepare($sqlDelete);
    $stmtDelete->bind_param("s", $MaSV);
    if ($stmtDelete->execute()) {
        echo "<script>alert('Xóa sinh viên thành công!'); window.location.href='index.php';</script>";
    } else {
        echo "<script>alert('Lỗi khi xóa!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xóa Sinh Viên</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
    <div class="container mx-auto mt-10 p-6 bg-white shadow-lg rounded-lg max-w-lg">
        <h2 class="text-3xl font-bold text-red-600 text-center">Xác nhận xóa</h2>
        <p class="text-lg text-gray-700 mt-4 text-center">Bạn có chắc chắn muốn xóa sinh viên
            <strong><?php echo $student['HoTen']; ?></strong>?
        </p>

        <!-- Hiển thị ảnh sinh viên -->
        <div class="flex justify-center mt-6">
            <img src="<?php echo $student['Hinh']; ?>" alt="Ảnh sinh viên" class="w-32 h-32 rounded-lg shadow">
        </div>

        <!-- Hiển thị thông tin sinh viên -->
        <div class="mt-6 text-gray-700">
            <p><strong>Giới tính:</strong> <?php echo $student['GioiTinh']; ?></p>
            <p><strong>Ngày sinh:</strong> <?php echo $student['NgaySinh']; ?></p>
            <p><strong>Ngành học:</strong> <?php echo $nganh['TenNganh']; ?></p>
        </div>

        <!-- Hành động xóa -->
        <div class="flex justify-between mt-6">
            <a href="index.php" class="px-6 py-2 bg-gray-500 text-white rounded shadow hover:bg-gray-700">Hủy</a>
            <form method="POST">
                <button type="submit" class="px-6 py-2 bg-red-600 text-white rounded shadow hover:bg-red-800">
                    Xóa ngay
                </button>
            </form>
        </div>
    </div>
</body>

</html>