<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?? "Trang Web"; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gradient-to-r from-gray-100 to-gray-300">
    <!-- Navbar -->
    <nav class="bg-blue-600 text-white shadow-lg">
        <div class="container mx-auto flex justify-between items-center py-4 px-6">
            <ul class="flex space-x-8">
                <li><a href="sinhvien.php" class="hover:underline font-semibold">Sinh Viên</a></li>
                <li><a href="hocphan.php" class="hover:underline font-semibold">Học Phần</a></li>
                <li><a href="chitietdangky.php" class="hover:underline font-semibold">Chi Tiết Đăng Ký</a></li>
                <li>
                    <a href="dangky.php" class="hover:underline font-semibold">
                        Đăng Ký (<span><?php echo isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0; ?></span>)
                    </a>
                </li>
            </ul>
            <a href="../login.php"
                class="px-4 py-2 bg-white text-blue-600 font-semibold rounded hover:bg-gray-200 transition duration-200">Đăng
                Nhập</a>
        </div>
    </nav>

    <!-- Content -->
    <main class="container mx-auto p-8 bg-white shadow-md rounded mt-6">
        <?php echo $content ?? ""; ?>
    </main>
</body>

</html>