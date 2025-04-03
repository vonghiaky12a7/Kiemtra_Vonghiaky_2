<?php
include '../../config/db.php';
session_start();

if (!isset($_SESSION['MaSV'])) {
    header("Location: login.php");
    exit();
}

// Fetch list of available courses
$sqlHocPhan = "SELECT * FROM HocPhan";
$result = $conn->query($sqlHocPhan);

if (!$result) {
    die("Lỗi khi lấy danh sách học phần: " . $conn->error);
}

// Handle adding courses to the session cart
if (isset($_GET['MaHP'])) {
    $MaHP = $_GET['MaHP'];
    $MaSV = $_SESSION['MaSV'];

    // Check if the student has already registered for this course
    $stmtCheck = $conn->prepare("SELECT COUNT(*) FROM ChiTietDangKy INNER JOIN DangKy ON ChiTietDangKy.MaDK = DangKy.MaDK WHERE DangKy.MaSV = ? AND ChiTietDangKy.MaHP = ?");
    $stmtCheck->bind_param("ss", $MaSV, $MaHP);
    $stmtCheck->execute();
    $stmtCheck->bind_result($exists);
    $stmtCheck->fetch();
    $stmtCheck->close();

    if ($exists > 0) {
        // Show an alert and prevent adding the course
        echo "<script>alert('Bạn đã đăng ký học phần này trước đó!'); window.location.href='hocphan.php';</script>";
        exit();
    }

    // Check if the course still has available slots
    $sqlCheck = "SELECT SoLuongDuKien FROM HocPhan WHERE MaHP = '$MaHP'";
    $resultCheck = $conn->query($sqlCheck);
    $rowCheck = $resultCheck->fetch_assoc();

    if ($rowCheck['SoLuongDuKien'] > 0) {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
        if (!in_array($MaHP, $_SESSION['cart'])) {
            $_SESSION['cart'][] = $MaHP;
        }
    } else {
        echo "<script>alert('Học phần đã hết chỗ!'); window.location.href='hocphan.php';</script>";
        exit();
    }

    header("Location: hocphan.php");
} ?>

<?php ob_start(); ?>
<h1 class="text-4xl font-extrabold text-center text-blue-600 mb-8">Danh Sách Học Phần</h1>
<div class="overflow-x-auto">
    <table class="w-full border border-gray-300 rounded-lg shadow-md bg-white">
        <thead class="bg-gradient-to-r from-blue-400 to-blue-600 text-white">
            <tr>
                <th class="border px-4 py-2 font-semibold text-sm">Mã Học Phần</th>
                <th class="border px-4 py-2 font-semibold text-sm">Tên Học Phần</th>
                <th class="border px-4 py-2 font-semibold text-sm">Số Tín Chỉ</th>
                <th class="border px-4 py-2 font-semibold text-sm">Số lượng còn lại</th>
                <th class="border px-4 py-2 font-semibold text-sm">Hành Động</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr class="hover:bg-gray-100 transition duration-300">
                <td class="border px-4 py-2 text-center"><?php echo $row['MaHP']; ?></td>
                <td class="border px-4 py-2 text-center"><?php echo $row['TenHP']; ?></td>
                <td class="border px-4 py-2 text-center"><?php echo $row['SoTinChi']; ?></td>
                <td class="border px-4 py-2 text-center">
                    <?php echo $row['SoLuongDuKien'] > 0 ? $row['SoLuongDuKien'] : '<span class="text-red-500">0</span>'; ?>
                </td>
                <td class="border px-4 py-2 text-center">
                    <?php if ($row['SoLuongDuKien'] > 0): ?>
                    <a href="?MaHP=<?php echo $row['MaHP']; ?>"
                        class="text-white bg-blue-400 hover:bg-blue-700 px-4 py-2 rounded shadow">Đăng Ký</a>
                    <?php else: ?>
                    <button class="text-white bg-gray-400 px-4 py-2 rounded shadow cursor-not-allowed" disabled>Hết
                        chỗ</button>
                    <?php endif; ?>
                </td>

            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
<?php $content = ob_get_clean(); ?>
<?php include './layout.php'; ?>