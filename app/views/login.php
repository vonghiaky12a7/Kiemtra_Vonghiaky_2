<?php
include '../config/db.php';

session_start();
$error = ""; // Khởi tạo biến $error để tránh lỗi Undefined variable

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $MaSV = $_POST['username'];
    $password = $_POST['password'];

    // Lấy thông tin đăng nhập và vai trò
    $sql = $conn->prepare("SELECT * FROM SinhVien WHERE MaSV = ? AND password = ?");
    $sql->bind_param("ss", $MaSV, $password);
    $sql->execute();
    $result = $sql->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $_SESSION['MaSV'] = $user['MaSV'];
        $_SESSION['role'] = $user['role'];

        // Chuyển hướng dựa trên vai trò
        if ($user['role'] == 'admin') {
            header("Location: index.php");
        } else {
            header("Location: hocphan/hocphan.php");
        }
    } else {
        $error = "Thông tin đăng nhập không chính xác.";
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gradient-to-r from-blue-400 to-blue-600 flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded-lg shadow-lg max-w-sm w-full">
        <h2 class="text-3xl font-extrabold text-center text-gray-700 mb-6">Welcome Back</h2>

        <!-- Error Alert -->
        <?php if (!empty($error)): ?>
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded mb-4">
                <p class="text-sm"><?php echo $error; ?></p>
            </div>
        <?php endif; ?>

        <form method="POST" id="loginForm" class="space-y-4">
            <div>
                <label for="username" class="block text-sm font-medium text-gray-600">Username</label>
                <input type="text" name="username" id="username" placeholder="Enter your username"
                    class="w-full mt-1 p-2 border rounded focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
            </div>
            <div>
                <label for="password" class="block text-sm font-medium text-gray-600">Password</label>
                <input type="password" name="password" id="password" placeholder="Enter your password"
                    class="w-full mt-1 p-2 border rounded focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
            </div>
            <button type="submit"
                class="w-full bg-blue-500 hover:bg-blue-700 text-white font-semibold p-2 rounded transition duration-300">
                Log In
            </button>
        </form>

        <div class="mt-4 text-center">
            <p class="text-sm text-gray-500">Don't have an account? <a href="#"
                    class="text-blue-500 hover:underline">Sign Up</a></p>
        </div>
    </div>
</body>

</html>