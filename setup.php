<?php
/**
 * File setup.php - Tự động import database
 * Truy cập: http://localhost/WebDatDoAn/setup.php
 */

require_once './configs/env.php';

if ($_POST['action'] ?? '' !== 'setup') {
    ?>
    <!DOCTYPE html>
    <html lang="vi">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Setup Shop Database</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 20px;
            }
            .container {
                background: white;
                padding: 40px;
                border-radius: 10px;
                box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
                max-width: 600px;
            }
            h1 { color: #333; margin-bottom: 20px; }
            .info { background: #e3f2fd; padding: 15px; border-radius: 5px; margin: 15px 0; border-left: 4px solid #2196F3; }
            .success { background: #d4edda; padding: 15px; border-radius: 5px; margin: 15px 0; border-left: 4px solid #28a745; }
            .error { background: #f8d7da; padding: 15px; border-radius: 5px; margin: 15px 0; border-left: 4px solid #dc3545; }
            .btn { padding: 12px 30px; background: #667eea; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; font-weight: bold; width: 100%; }
            .btn:hover { background: #764ba2; }
            code { background: #f5f5f5; padding: 5px 10px; border-radius: 3px; font-family: monospace; }
        </style>
    </head>
    <body>
        <div class="container">
            <h1>🔧 Setup Shop Database</h1>

            <div class="info">
                <strong>Kết nối Database:</strong><br>
                Host: <?php echo DB_HOST; ?><br>
                Database: <?php echo DB_NAME; ?><br>
                User: <?php echo DB_USERNAME; ?>
            </div>

            <p>Click nút "<strong>Setup Database</strong>" để tự động tạo các bảng và dữ liệu mẫu:</p>

            <form method="POST">
                <input type="hidden" name="action" value="setup">
                <button type="submit" class="btn">▶️ Setup Database</button>
            </form>

            <div class="info" style="margin-top: 20px; font-size: 12px;">
                <strong>Lưu ý:</strong> Nếu database chưa tồn tại, vui lòng tạo <code><?php echo DB_NAME; ?></code> trong phpMyAdmin trước
            </div>
        </div>
    </body>
    </html>
    <?php
    exit;
}

// Xử lý setup
try {
    $dsn = sprintf('mysql:host=%s;port=%s;dbname=%s;charset=utf8', DB_HOST, DB_PORT, DB_NAME);
    $conn = new PDO($dsn, DB_USERNAME, DB_PASSWORD);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // SQL để tạo bảng
    $sql = file_get_contents('./database.sql');
    
    // Tách các câu lệnh SQL
    $statements = array_filter(array_map('trim', preg_split('/;/', $sql)));
    
    $count = 0;
    foreach ($statements as $statement) {
        if (!empty($statement) && !preg_match('/^--/', $statement)) {
            $conn->exec($statement);
            $count++;
        }
    }

    // Thêm dữ liệu mẫu
    $seedData = [
        // Admin user
        "INSERT IGNORE INTO users (name, email, password, role) VALUES 
        ('Admin', 'admin@example.com', '" . password_hash('123456', PASSWORD_BCRYPT) . "', 'admin')",
        
        // Sample categories
        "INSERT IGNORE INTO categories (name, description) VALUES 
        ('Cơm', 'Các món cơm ngon'),
        ('Nước uống', 'Nước uống mát lạnh'),
        ('Dessert', 'Các loại tráng miệng')",
        
        // Sample products
        "INSERT IGNORE INTO products (name, price, sale_price, description, category_id, stock) VALUES 
        ('Cơm Tấm Sài Gòn', 45000, 40000, 'Cơm tấm nóng hổi kèm sườn nướng, trứng ốp la', 1, 50),
        ('Cơm Chiên Tôm', 55000, NULL, 'Cơm chiên với tôm tươi, trứng gà', 1, 40),
        ('Nước Cam Ép', 25000, 20000, 'Nước cam tươi ép ngay', 2, 100),
        ('Cà Phê Đen', 20000, 18000, 'Cà phê đen đậm đà', 2, 100),
        ('Kem Sô-cô-la', 30000, NULL, 'Kem sô-cô-la mềm mịn', 3, 50)"
    ];

    foreach ($seedData as $seed) {
        try {
            $conn->exec($seed);
        } catch (PDOException $e) {
            // Bỏ qua nếu dữ liệu đã tồn tại
        }
    }

    ?>
    <!DOCTYPE html>
    <html lang="vi">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Setup Thành công</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 20px;
            }
            .container {
                background: white;
                padding: 40px;
                border-radius: 10px;
                box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
                max-width: 600px;
                text-align: center;
            }
            .success { color: #28a745; font-size: 24px; margin-bottom: 20px; }
            a { display: inline-block; margin-top: 20px; padding: 12px 30px; background: #667eea; color: white; text-decoration: none; border-radius: 5px; }
            a:hover { background: #764ba2; }
            .info { background: #f5f5f5; padding: 15px; border-radius: 5px; margin: 15px 0; text-align: left; }
            code { background: #e0e0e0; padding: 5px 10px; border-radius: 3px; }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="success">✅ Setup Database Thành Công!</div>

            <div class="info">
                <strong>Thông tin tài khoản Admin:</strong><br>
                Email: <code>admin@example.com</code><br>
                Password: <code>123456</code><br><br>
                <em style="color: #666; font-size: 12px;">⚠️ Vui lòng đổi mật khẩu sau khi đăng nhập!</em>
            </div>

            <div class="info">
                <strong>Dữ liệu mẫu đã được tạo:</strong><br>
                ✓ 3 danh mục (Cơm, Nước uống, Dessert)<br>
                ✓ 5 sản phẩm mẫu<br>
                ✓ Admin account
            </div>

            <a href="?c=product&a=list">👉 Vào trang chủ ứng dụng</a>
        </div>
    </body>
    </html>
    <?php

} catch (PDOException $e) {
    ?>
    <!DOCTYPE html>
    <html lang="vi">
    <head>
        <meta charset="UTF-8">
        <title>Lỗi Setup</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                background: #f5f5f5;
                padding: 20px;
            }
            .error {
                background: #f8d7da;
                color: #721c24;
                padding: 20px;
                border-radius: 5px;
                border-left: 4px solid #dc3545;
            }
            code { background: #fff3cd; padding: 10px; display: block; margin: 10px 0; border-radius: 3px; }
        </style>
    </head>
    <body>
        <div class="error">
            <h3>❌ Lỗi Setup Database</h3>
            <p><?php echo $e->getMessage(); ?></p>
            <code><?php echo $e->getFile() . ':' . $e->getLine(); ?></code>
        </div>
    </body>
    </html>
    <?php
}
