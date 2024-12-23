<?php
// Kết nối cơ sở dữ liệu
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "phone_store";

$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Kiểm tra nếu có ID sản phẩm và dữ liệu gửi từ form
if (isset($_POST['id']) && isset($_POST['name']) && isset($_POST['type']) && isset($_POST['brand']) && isset($_POST['price']) && isset($_POST['description']) && isset($_POST['category']) && isset($_POST['quantity'])) {
    $productId = intval($_POST['id']);
    $productName = $_POST['name'];
    $productType = $_POST['type'];
    $productBrand = $_POST['brand'];
    $productPrice = $_POST['price'];
    $productDescription = $_POST['description'];
    $productCategory = $_POST['category'];
    $productQuantity = $_POST['quantity'];

    // Kiểm tra xem có upload ảnh mới không
    $productImage = $_FILES['image']['name'];
    $uploadOk = 1; // Biến dùng để kiểm tra xem có thể upload ảnh không

    if ($productImage) {
        // Xử lý ảnh mới nếu có
        $targetDir = "uploads/";  // Thư mục lưu ảnh
        $targetFile = $targetDir . basename($productImage);
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
        
        // Kiểm tra kích thước ảnh
        if ($_FILES['image']['size'] > 5000000) {
            echo "Kích thước ảnh quá lớn.";
            $uploadOk = 0;
        }
        
        // Kiểm tra kiểu ảnh (chỉ cho phép jpg, jpeg, png)
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
            echo "Chỉ cho phép upload ảnh định dạng JPG, JPEG, PNG.";
            $uploadOk = 0;
        }
        
        // Kiểm tra xem có lỗi khi upload không
        if ($uploadOk == 0) {
            echo "Ảnh không thể upload.";
        } else {
            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                // Cập nhật thông tin sản phẩm, bao gồm đường dẫn ảnh
                $productImage = $targetFile;
            } else {
                echo "Có lỗi trong quá trình tải ảnh lên.";
                $uploadOk = 0;
            }
        }
    } else {
        // Nếu không có ảnh mới, giữ nguyên ảnh cũ
        // Lấy ảnh cũ từ cơ sở dữ liệu
        $sql = "SELECT image_url FROM products WHERE id = $productId";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $productImage = $row['image_url'];
        }
    }

    // Cập nhật thông tin sản phẩm vào cơ sở dữ liệu
    if ($uploadOk == 1) {
        $sql = "UPDATE products SET 
            name = '$productName',
            type = '$productType',
            brand = '$productBrand',
            price = '$productPrice',
            description = '$productDescription',
            category = '$productCategory',
            quantity = '$productQuantity',
            image_url = '$productImage' 
            WHERE id = $productId";
        
        if ($conn->query($sql) === TRUE) {
            // Chuyển hướng về trang quản lý sản phẩm sau khi cập nhật thành công
            header("Location: Admin Panel.php");
            exit;
        } else {
            echo "Lỗi khi cập nhật sản phẩm: " . $conn->error;
        }
    }
} else {
    echo "Dữ liệu không hợp lệ.";
}

// Đóng kết nối
$conn->close();
?>
