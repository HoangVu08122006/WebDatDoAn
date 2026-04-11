<?php
/**
 * F13: Lịch sử đơn hàng của sinh viên
 */

// Hàm hiển thị badge trạng thái
function statusBadge($status) {
    $map = [
        'pending'   => ['🕐 Chờ xác nhận', '#f39c12'],
        'confirmed' => ['✅ Đã xác nhận',  '#2ecc71'],
        'shipping'  => ['🚴 Đang giao',     '#3498db'],
        'completed' => ['🎉 Hoàn thành',    '#27ae60'],
        'cancelled' => ['❌ Đã hủy',        '#e74c3c'],
    ];
    [$label, $color] = $map[$status] ?? ['❓ Không rõ', '#95a5a6'];
    return "<span style=\"background:{$color};color:white;padding:4px 10px;border-radius:12px;font-size:12px;font-weight:bold;\">{$label}</span>";
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lịch sử đơn hàng – Căn Tin</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f5f5f5; color: #333; }
        .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 18px 24px; }
        .navbar { display: flex; gap: 24px; padding: 12px 24px; background: white; border-bottom: 1px solid #ddd; flex-wrap: wrap; }
        .navbar a { text-decoration: none; color: #333; font-weight: bold; font-size: 14px; }
        .navbar a:hover { color: #667eea; }
        .navbar .user-info { margin-left: auto; color: #667eea; font-size: 14px; }

        .container { max-width: 960px; margin: 30px auto; padding: 0 16px; }
        .page-title { font-size: 22px; font-weight: bold; margin-bottom: 20px; }

        .alert { padding: 12px 16px; border-radius: 6px; margin-bottom: 16px; font-size: 14px; }
        .alert-success { background: #d4edda; color: #155724; border-left: 4px solid #28a745; }

        .card { background: white; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.08); overflow: hidden; }
        table { width: 100%; border-collapse: collapse; font-size: 14px; }
        thead th { background: #667eea; color: white; padding: 13px 16px; text-align: left; }
        tbody td { padding: 13px 16px; border-bottom: 1px solid #f0f0f0; vertical-align: middle; }
        tbody tr:last-child td { border-bottom: none; }
        tbody tr:hover { background: #fafafa; }

        .btn { padding: 7px 16px; border-radius: 5px; font-size: 13px; font-weight: bold; text-decoration: none; display: inline-block; }
        .btn-primary { background: #667eea; color: white; }
        .btn-primary:hover { background: #764ba2; }

        .empty { text-align: center; padding: 60px 20px; color: #999; }
        .empty .icon { font-size: 60px; margin-bottom: 16px; }
    </style>
</head>
<body>

<div class="header">
    <h1>🍱 Căn Tin – Đặt Đồ Ăn</h1>
</div>

<div class="navbar">
    <a href="?c=product&a=list">🏠 Thực đơn</a>
    <a href="?c=cart&a=index">🛒 Giỏ hàng</a>
    <a href="?c=order&a=history">📋 Đơn hàng</a>
    <span class="user-info">👤 <?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
    <a href="?c=auth&a=logout" style="color:#e74c3c;">Đăng xuất</a>
</div>

<div class="container">
    <div class="page-title">📋 Lịch sử đơn hàng</div>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
    <?php endif; ?>

    <?php if (empty($orders)): ?>
        <div class="empty">
            <div class="icon">📭</div>
            <p>Bạn chưa có đơn hàng nào.</p>
            <a href="?c=product&a=list" class="btn btn-primary" style="margin-top:16px;">Xem thực đơn ngay</a>
        </div>
    <?php else: ?>
        <div class="card">
            <table>
                <thead>
                    <tr>
                        <th>Mã đơn</th>
                        <th>Ngày đặt</th>
                        <th>Địa điểm nhận</th>
                        <th>Tổng tiền</th>
                        <th>Trạng thái</th>
                        <th>Chi tiết</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><strong>#<?php echo $order['id']; ?></strong></td>
                        <td><?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?></td>
                        <td>📍 <?php echo htmlspecialchars($order['delivery_location'] ?? '—'); ?></td>
                        <td><strong><?php echo number_format($order['total_price'], 0, ',', '.'); ?>đ</strong></td>
                        <td><?php echo statusBadge($order['status']); ?></td>
                        <td>
                            <a href="?c=order&a=detail&id=<?php echo $order['id']; ?>" class="btn btn-primary">
                                🔍 Xem
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

</body>
</html>
