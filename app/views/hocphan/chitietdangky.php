<?php
include '../../config/db.php';
session_start();

if (!isset($_SESSION['MaSV'])) {
    header("Location: login.php");
    exit();
}

$MaSV = $_SESSION['MaSV'];
$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

// Remove a specific course from the session cart
if (isset($_GET['remove'])) {
    $MaHP = $_GET['remove'];
    if (($key = array_search($MaHP, $cart)) !== false) {
        unset($cart[$key]); // Remove the item from the cart
        $_SESSION['cart'] = array_values($cart); // Re-index the cart array
    }
    header("Location: chitietdangky.php");
    exit();
}

// Clear the entire session cart
if (isset($_GET['clear'])) {
    unset($_SESSION['cart']);
    header("Location: chitietdangky.php");
    exit();
}

// Process saving the cart to the database upon confirmation
if (isset($_GET['confirm'])) {
    if (!empty($cart)) {
        $NgayDK = date('Y-m-d');

        // Check if the student already has an existing MaDK
        $stmtCheckMaDK = $conn->prepare("SELECT MaDK FROM DangKy WHERE MaSV = ?");
        $stmtCheckMaDK->bind_param("s", $MaSV);
        $stmtCheckMaDK->execute();
        $stmtCheckMaDK->bind_result($existingMaDK);
        $stmtCheckMaDK->fetch();
        $stmtCheckMaDK->close();

        if (!$existingMaDK) {
            // If no existing MaDK, create a new one
            $stmtDangKy = $conn->prepare("INSERT INTO DangKy (NgayDK, MaSV) VALUES (?, ?)");
            $stmtDangKy->bind_param("ss", $NgayDK, $MaSV);
            $stmtDangKy->execute();
            $MaDK = $stmtDangKy->insert_id; // Get the newly created MaDK
        } else {
            $MaDK = $existingMaDK; // Use the existing MaDK
        }

        // Add courses to ChiTietDangKy
        $stmtChiTiet = $conn->prepare("INSERT INTO ChiTietDangKy (MaDK, MaHP) VALUES (?, ?)");

        foreach ($cart as $MaHP) {
            // Check if the course is already registered
            $stmtCheckCourse = $conn->prepare("SELECT COUNT(*) FROM ChiTietDangKy WHERE MaDK = ? AND MaHP = ?");
            $stmtCheckCourse->bind_param("is", $MaDK, $MaHP);
            $stmtCheckCourse->execute();
            $stmtCheckCourse->bind_result($courseExists);
            $stmtCheckCourse->fetch();
            $stmtCheckCourse->close();

            if ($courseExists > 0) {
                // Skip if the course is already registered
                continue;
            }

            // Add the course under the same MaDK
            $stmtChiTiet->bind_param("is", $MaDK, $MaHP);
            $stmtChiTiet->execute();

            // Decrease the available slots for the course
            $stmtUpdate = $conn->prepare("UPDATE HocPhan SET SoLuongDuKien = SoLuongDuKien - 1 WHERE MaHP = ? AND SoLuongDuKien > 0");
            $stmtUpdate->bind_param("s", $MaHP);
            $stmtUpdate->execute();
        }

        unset($_SESSION['cart']); // Clear the cart after saving
        header("Location: chitietdangky.php?status=success&NgayDK=$NgayDK");
        exit();
    }
}

// Fetch course details and calculate total credits
$hocPhanList = [];
$totalCredits = 0;
if (!empty($cart)) {
    $placeholders = implode(',', array_fill(0, count($cart), '?'));
    $stmt = $conn->prepare("SELECT * FROM HocPhan WHERE MaHP IN ($placeholders)");
    $stmt->bind_param(str_repeat('s', count($cart)), ...$cart);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $hocPhanList[] = $row;
        $totalCredits += $row['SoTinChi'];
    }
}

// Fetch student details
$stmtSinhVien = $conn->prepare("SELECT * FROM SinhVien WHERE MaSV = ?");
$stmtSinhVien->bind_param("s", $MaSV);
$stmtSinhVien->execute();
$sinhVien = $stmtSinhVien->get_result()->fetch_assoc();
?>

<?php ob_start(); ?>
<h1 class="text-3xl font-bold mb-6">Chi Tiết Đăng Ký</h1>

<!-- Display student info and registration details upon confirmation -->
<?php if (isset($_GET['status']) && $_GET['status'] == 'success'): ?>
<div class="mb-4 p-4 bg-green-100 border border-green-400 rounded">
    <h2 class="text-2xl font-bold mb-2">Thông Tin Đăng Ký</h2>
    <p><strong>Mã số sinh viên:</strong> <?php echo $sinhVien['MaSV']; ?></p>
    <p><strong>Họ Tên Sinh Viên:</strong> <?php echo $sinhVien['HoTen']; ?></p>
    <p><strong>Ngày Sinh:</strong> <?php echo $sinhVien['NgaySinh']; ?></p>
    <p><strong>Ngành Học:</strong> <?php echo $sinhVien['MaNganh']; ?></p>
    <p><strong>Ngày Đăng Ký:</strong> <?php echo $_GET['NgayDK']; ?></p>
</div>
<?php endif; ?>

<!-- Display total courses and credits -->
<div class="mb-4">
    <p class="text-lg font-semibold">Tổng số học phần: <?php echo count($hocPhanList); ?></p>
    <p class="text-lg font-semibold">Tổng số tín chỉ: <?php echo $totalCredits; ?></p>
</div>

<!-- Display courses in the cart -->
<table class="table-auto w-full mt-4 border-collapse border border-gray-300">
    <thead>
        <tr class="bg-gray-200">
            <th class="border border-gray-300 px-4 py-2">Mã Học Phần</th>
            <th class="border border-gray-300 px-4 py-2">Tên Học Phần</th>
            <th class="border border-gray-300 px-4 py-2">Số Tín Chỉ</th>
            <th class="border border-gray-300 px-4 py-2">Hành động</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($hocPhanList)): ?>
        <?php foreach ($hocPhanList as $hocPhan): ?>
        <tr class="bg-white hover:bg-gray-100">
            <td class="border border-gray-300 px-4 py-2"><?php echo $hocPhan['MaHP']; ?></td>
            <td class="border border-gray-300 px-4 py-2"><?php echo $hocPhan['TenHP']; ?></td>
            <td class="border border-gray-300 px-4 py-2"><?php echo $hocPhan['SoTinChi']; ?></td>
            <td class="border border-gray-300 px-4 py-2">
                <a href="?remove=<?php echo $hocPhan['MaHP']; ?>" class="text-red-500 hover:underline">Xóa</a>
            </td>
        </tr>
        <?php endforeach; ?>
        <?php else: ?>
        <tr>
            <td colspan="4" class="border border-gray-300 px-4 py-2 text-center">Chưa có học phần nào trong giỏ hàng.
            </td>
        </tr>
        <?php endif; ?>
    </tbody>
</table>

<!-- Buttons for saving or clearing the cart -->
<div class="mt-4 flex space-x-4">
    <a href="?confirm=true" class="bg-green-500 hover:bg-green-700 text-white px-4 py-2 rounded">Xác Nhận</a>
    <a href="?clear=true" class="bg-red-500 hover:bg-red-700 text-white px-4 py-2 rounded">Xóa Tất Cả</a>
</div>
<?php $content = ob_get_clean(); ?>
<?php include './layout.php'; ?>