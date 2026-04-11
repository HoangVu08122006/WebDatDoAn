<?php
/**
 * US09: Chi tiết tài khoản người dùng (Admin)
 */
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết tài khoản – <?php echo htmlspecialchars($user['name']); ?></title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f5f5f5; }
        .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 20px; }
        .navbar { display: flex; gap: 20px; padding: 15px 20px; background: white; border-bottom: 1px solid #ddd; }
        .navbar a { text-decoration: none; color: #333; font-weight: bold; }
        .navbar a:hover { color: #667eea; }
        .container { max-width: 700px; margin: 30px auto; padding: 20px; }
        .card { background: white; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); overflow: hidden; }
        .card-header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 20px 25px; }
        .card-body { padding: 25px; }
        .info-row { display: flex; padding: 14px 0; border-bottom: 1px solid #f0f0f0; align-items: center; }
        .info-row:last-child { border-bottom: none; }
        .info-label { width: 160px; font-weight: bold; color: #555; font-size: 14px; }
        .info-value { flex: 1; color: #222; }
        .badge { display: inline-block; padding: 5px 14px; border-radius: 20px; font-size: 13px; font-weight: bold; color: white; }
        .badge-admin { background: #667eea; }
        .badge-user  { background: #95a5a6; }
        .actions { display: flex; gap: 12px; margin-top: 25px; }
        .btn { padding: 10px 22px; border-radius: 6px; text-decoration: none; font-size: 14px; font-weight: bold; display: inline-block; }
        .btn-primary { background: #667eea; color: white; }
        .btn-primary:hover { background: #764ba2; }
        .btn-outline { background: white; color: #667eea; border: 2px solid #667eea; }
        .btn-outline:hover { background: #667eea; color: white; }
    </style>
</head>
<body>
    <div class="header">
        <h1>👨‍💼 Quản lý hệ thống</h1>
    </div>

    <div class="navbar">
        <a href="?c=adminProduct&a=list">📦 Quản lý sản phẩm</a>
        <a href="?c=adminUser&a=list">👤 Quản lý tài khoản</a>
        <a href="?c=product&a=list">🏠 Trang chủ</a>
        <a href="?c=auth&a=logout" style="margin-left: auto;">Đăng xuất</a>
    </div>

    <div class="container">
        <div class="card">
            <div class="card-header">
                <h2>👤 Chi tiết tài khoản</h2>
                <p style="margin-top:5px; opacity:.85;">Thông tin chi tiết người dùng #<?php echo $user['id']; ?></p>
            </div>
            <div class="card-body">
                <div class="info-row">
                    <span class="info-label">🆔 ID</span>
                    <span class="info-value"><?php echo $user['id']; ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">👤 Họ và tên</span>
                    <span class="info-value"><?php echo htmlspecialchars($user['name']); ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">📧 Email</span>
                    <span class="info-value"><?php echo htmlspecialchars($user['email']); ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">🎭 Vai trò</span>
                    <span class="info-value">
                        <span class="badge <?php echo $user['role'] === 'admin' ? 'badge-admin' : 'badge-user'; ?>">
                            <?php echo $user['role'] === 'admin' ? '👨‍💼 Admin' : '👤 Sinh viên'; ?>
                        </span>
                    </span>
                </div>
                <div class="info-row">
                    <span class="info-label">📅 Ngày đăng ký</span>
                    <span class="info-value"><?php echo date('d/m/Y H:i', strtotime($user['created_at'])); ?></span>
                </div>

                <div class="actions">
                    <a href="?c=adminUser&a=edit&id=<?php echo $user['id']; ?>" class="btn btn-primary">✏️ Chỉnh sửa</a>
                    <a href="?c=adminUser&a=list" class="btn btn-outline">← Quay lại danh sách</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
