<?php
include '../config/db.php';

// Thiết lập số sinh viên trên mỗi trang
$limit = 4;

// Xác định trang hiện tại (mặc định là 1)
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page = $page < 1 ? 1 : $page;

// Tính toán vị trí bắt đầu cho mỗi trang
$start = ($page - 1) * $limit;

// Lấy tổng số sinh viên để tính tổng số trang
$totalResult = $conn->query("SELECT COUNT(*) AS total FROM SinhVien");
$totalRow = $totalResult->fetch_assoc();
$totalStudents = $totalRow['total'];
$totalPages = ceil($totalStudents / $limit);

// Lấy danh sách sinh viên cho trang hiện tại
$sql = "SELECT * FROM SinhVien LIMIT $start, $limit";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách sinh viên</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gradient-to-br from-gray-100 to-gray-300">
    <div class="container mx-auto p-8 bg-white rounded-lg shadow-lg">
        <h1 class="text-4xl font-extrabold text-blue-600 mb-6 text-center">Danh sách sinh viên</h1>
        <div class="flex justify-between items-center mb-6">
            <a href="create.php"
                class="bg-blue-500 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded shadow-lg">+ Thêm sinh
                viên</a>
        </div>
        <div class="overflow-x-auto">
            <table class="table-auto w-full border border-gray-300 rounded-lg shadow-md">
                <thead class="bg-gradient-to-r from-blue-500 to-blue-700 text-white">
                    <tr>
                        <th class="px-4 py-2 font-bold">Mã SV</th>
                        <th class="px-4 py-2 font-bold">Họ Tên</th>
                        <th class="px-4 py-2 font-bold">Giới Tính</th>
                        <th class="px-4 py-2 font-bold">Ngày Sinh</th>
                        <th class="px-4 py-2 font-bold">Hình</th>
                        <th class="px-4 py-2 font-bold">Ngành Học</th>
                        <th class="px-4 py-2 font-bold">Hành Động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr class="bg-gray-50 hover:bg-gray-100 transition">
                        <td class="border px-4 py-2 text-center"><?php echo $row['MaSV']; ?></td>
                        <td class="border px-4 py-2 text-center"><?php echo $row['HoTen']; ?></td>
                        <td class="border px-4 py-2 text-center"><?php echo $row['GioiTinh']; ?></td>
                        <td class="border px-4 py-2 text-center"><?php echo $row['NgaySinh']; ?></td>
                        <td class="border px-4 py-2 text-center">
                            <img src="<?php echo $row['Hinh']; ?>" alt="Ảnh sinh viên"
                                class="w-20 h-20 rounded-lg shadow-md">
                        </td>
                        <td class="border px-4 py-2 text-center"><?php echo $row['MaNganh']; ?></td>
                        <td class="border px-4 py-2 text-center">
                            <a href="detail.php?MaSV=<?php echo $row['MaSV']; ?>"
                                class="text-blue-500 hover:underline">Chi tiết</a> |
                            <a href="edit.php?MaSV=<?php echo $row['MaSV']; ?>"
                                class="text-green-500 hover:underline">Sửa</a> |
                            <a href="delete.php?MaSV=<?php echo $row['MaSV']; ?>" class="text-red-500 hover:underline"
                                onclick="return confirm('Bạn có chắc chắn muốn xóa sinh viên này không?');">
                                Xóa
                            </a>
                        </td>

                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6 flex justify-center">
            <?php for ($i = 1; $i <= $totalPages; $i++) { ?>
            <a href="?page=<?php echo $i; ?>"
                class="mx-1 px-4 py-2 rounded shadow-md <?php echo $i == $page ? 'bg-blue-500 text-white' : 'bg-gray-300 hover:bg-blue-400 text-blue-700'; ?>">
                <?php echo $i; ?>
            </a>
            <?php } ?>
        </div>
    </div>
</body>

</html>