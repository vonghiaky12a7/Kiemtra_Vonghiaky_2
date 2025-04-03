<?php
// Kết nối tới MySQL server (không chọn database cụ thể trước)
$conn = new mysqli("localhost", "root", "", "test1");

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Đặt charset
$conn->set_charset("utf8");

// Kiểm tra xem database Test1 có tồn tại không
$db_name = "test1";
$result = $conn->query("SHOW DATABASES LIKE '$db_name'");
if ($result->num_rows == 0) {
    // Nếu database không tồn tại, tạo mới
    $create_db = "CREATE DATABASE $db_name CHARACTER SET utf8 COLLATE utf8_general_ci";
    if ($conn->query($create_db) === TRUE) {
        echo "Database $db_name created successfully<br>";
    } else {
        die("Error creating database: " . $conn->error);
    }
}
