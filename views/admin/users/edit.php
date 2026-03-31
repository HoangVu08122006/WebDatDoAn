<?php
/**
 * US09: Sửa tài khoản (Admin)
 */
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa tài khoản</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f5f5f5; }
        .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 20px; }
        .navbar { display: flex; gap: 20px; padding: 15px 20px; background: white; border-bottom: 1px solid #ddd; }
        .navbar a { text-decoration: none; color: #333; font-weight: bold; }
        .container { max-width: 600px; margin: 20px auto; }
        .form-container { background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); }
        h2 { margin-bottom: 20px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input, select { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; font-size: 14px; }
        input:focus, select:focus { outline: none; border-color: #667eea; }
        .btn { display: inline-block; padding: 10px 20px; background: #667eea; color: white; border: none; border-radius: 5px; cursor: pointer; margin-right: 10px; text-decoration: none; }
        .btn:hover { background: #764ba2; }
        .btn-cancel { background: #95a5a6; }
        .btn-cancel:hover { background: #7f8c8d; }
        .back-btn { display: inline-block; margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>👨‍💼 Quản lý hệ thống</h1>
    </div>

    <div class="navbar">
        <a href="?c=adminUser&a=list">← Quay lại</a>
    </div>

    <div class="container">
        <div class="form-container">
            <h2>✏️ Sửa tài khoản: <?php echo $user['name']; ?></h2>

            <form method="POST">
                <div class="form-group">
                    <label for="name">Tên:</label>
                    <input type="text" id="name" name="name" value="<?php echo $user['name']; ?>" required>
                </div>

                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" value="<?php echo $user['email']; ?>" required>
                </div>

                <div class="form-group">
                    <label for="role">Vai trò:</label>
                    <select id="role" name="role" required>
                        <option value="user" <?php echo $user['role'] === 'user' ? 'selected' : ''; ?>>👤 User</option>
                        <option value="admin" <?php echo $user['role'] === 'admin' ? 'selected' : ''; ?>>👨‍💼 Admin</option>
                    </select>
                </div>

                <div>
                    <button type="submit" class="btn">💾 Lưu</button>
                    <a href="?c=adminUser&a=list" class="btn btn-cancel">❌ Hủy</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
