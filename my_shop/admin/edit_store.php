<?php
session_start();
include('db.php');

// Kiểm tra nếu có id cửa hàng để sửa
if (!isset($_GET['id'])) {
    header("Location: store_manager.php");
    exit;
}

$storeId = $_GET['id'];

// Lấy thông tin cửa hàng từ cơ sở dữ liệu
$sql = "SELECT * FROM store_info WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $storeId);
$stmt->execute();
$result = $stmt->get_result();
$store = $result->fetch_assoc();

// Nếu không tìm thấy cửa hàng
if (!$store) {
    header("Location: store_manager.php");
    exit;
}

// Sửa thông tin cửa hàng
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_store'])) {
    $storeName = $_POST['store_name'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];

    $sql = "UPDATE store_info SET store_name = ?, address = ?, phone = ?, email = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssssi', $storeName, $address, $phone, $email, $storeId);
    $stmt->execute();

    $_SESSION['message'] = "Store updated successfully!";
    header("Location: store_manager.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Store</title>
    <style>
    /* General Reset */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        color: #333;
        line-height: 1.6;
        padding: 20px;
    }

    h1 {
        text-align: center;
        color: #4CAF50;
        margin-bottom: 30px;
    }

    /* Form Container */
    .form-container {
        max-width: 600px;
        margin: 0 auto;
        background-color: white;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    /* Input Styles */
    input[type="text"], input[type="email"], button {
        width: 100%;
        padding: 15px;
        margin: 10px 0;
        border-radius: 5px;
        border: 1px solid #ccc;
        font-size: 1.1em;
    }

    input[type="text"]:focus, input[type="email"]:focus {
        border-color: #4CAF50;
    }

    button {
        background-color: #4CAF50;
        color: white;
        font-size: 1.2em;
        cursor: pointer;
        border: none;
        transition: background-color 0.3s ease;
    }

    button:hover {
        background-color: #45a049;
    }

    /* Responsive Layout */
    @media (max-width: 768px) {
        .form-container {
            padding: 20px;
        }
        
        input[type="text"], input[type="email"], button {
            font-size: 1em;
        }
    }
</style>

</head>
<body>
    
<div class="form-container">
    <h1>Edit Store</h1>
    <form method="POST">
        <input type="text" name="store_name" value="<?php echo $store['store_name']; ?>" required><br>
        <input type="text" name="address" value="<?php echo $store['address']; ?>" required><br>
        <input type="text" name="phone" value="<?php echo $store['phone']; ?>" required><br>
        <input type="email" name="email" value="<?php echo $store['email']; ?>" required><br>
        <button type="submit" name="edit_store">Update Store</button>
    </form>
</div>


</body>
</html>
