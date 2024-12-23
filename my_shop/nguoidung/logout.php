<?php
session_start(); // Bắt đầu session

// Hủy tất cả session
session_unset();

// Hủy session
session_destroy();

// Chuyển hướng về trang chủ sau khi đăng xuất
header("Location: index.php");
exit();
?>
