<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quên mật khẩu</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="forgot-password-container">
        <h2>Quên mật khẩu</h2>
        <form action="send_reset_code.php" method="POST">
            <div class="input-group">
                <label for="email">Email đăng ký:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <button type="submit">Tiếp tục</button>
        </form>
    </div>
</body>
</html>
