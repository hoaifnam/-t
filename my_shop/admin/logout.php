<?php
// Bắt đầu session
session_start();

// Hủy tất cả session
session_unset();

// Hủy session hiện tại
session_destroy();

// Chuyển hướng về trang login
header("Location: login.php");
exit();
?>
