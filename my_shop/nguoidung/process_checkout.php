<?php
session_start();
include 'db_connection.php'; // File chứa kết nối CSDL

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id']; // ID người dùng từ session
    $payment_method = $_POST['payment_method'];
    $payment_account_id = null;

    // Xử lý khi thanh toán qua Credit Card
    if ($payment_method == 'credit_card' && isset($_POST['payment_account'])) {
        $payment_account_id = $_POST['payment_account'];
        
        // Lấy thông tin tài khoản ngân hàng
        $sql = "SELECT * FROM user_bank_accounts WHERE id = ? AND user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ii', $payment_account_id, $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Tiến hành thanh toán và lưu đơn hàng
            $account = $result->fetch_assoc();
            // Cập nhật thông tin giao dịch, đơn hàng...
        } else {
            echo "Invalid payment account.";
        }
    }

    // Xử lý phương thức thanh toán COD (Cash on Delivery)
    if ($payment_method == 'cash_on_delivery') {
        // Cập nhật thông tin giao dịch COD
    }
}
?>
