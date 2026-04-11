<?php
/**
 * Chi tiết một đơn hàng (Sinh viên xem)
 */

function statusBadge($status) {
    $map = [
        'pending'   => ['🕐 Chờ xác nhận', '#f39c12'],
        'confirmed' => ['✅ Đã xác nhận',  '#2ecc71'],
        'shipping'  => ['🚴 Đang giao',     '#3498db'],
        'completed' => ['🎉 Hoàn thành',    '#27ae60'],
        'cancelled' => ['❌ Đã hủy',        '#e74c3c'],
    ];
    [$label, $color] = $map[$status] ?? ['❓ Không rõ', '#95a5a6'];
    return "<span style=\"background:{$color};color:white;padding:5px 14px;border-radius:14px;font-size:13px;font-weight:bold;\">{$label}</span>";
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết đơn hàng #<?php echo $order['id']; ?> – Căn Tin</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f5f5f5; color: #333; }
        .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 18px 24px; }
        .navbar { display: flex; gap: 24px; padding: 12px 24px; background: white; border-bottom: 1px solid #ddd; flex-wrap: wrap; }
        .navbar a { text-decoration: none; color: #333; font-weight: bold; font-size: 14px; }
        .navbar a:hover { color: #667eea; }
        .navbar .user-info { margin-left: auto; color: #667eea; font-size: 14px; }

        .container { max-width: 800px; margin: 30px auto; padding: 0 16px; }
        .page-title { font-size: 22px; font-weight: bold; margin-bottom: 20px; }

        .card { background: white; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.08); margin-bottom: 20px; overflow: hidden; }
        .card-header { background: #f8f9fa; padding: 16px 20px; border-bottom: 1px solid #eee; font-weight: bold; font-size: 15px; }
        .card-body { padding: 20px; }

        .info-row { display: flex; padding: 10px 0; border-bottom: 1px solid #f5f5f5; font-size: 14px; }
        .info-row:last-child { border-bottom: none; }
        .info-label { width: 160px; color: #666; font-weight: bold; }
        .info-value { flex: 1; }

        table { width: 100%; border-collapse: collapse; font-size: 14px; }
        thead th { background: #667eea; color: white; padding: 12px 14px; text-align: left; }
        tbody td { padding: 12px 14px; border-bottom: 1px solid #f0f0f0; vertical-align: middle; }
        tbody tr:last-child td { border-bottom: none; }

        .product-info { display: flex; align-items: center; gap: 10px; }
        .product-img  { width: 48px; height: 48px; object-fit: cover; border-radius: 5px; background: #eee; }

        .total-row { display: flex; justify-content: flex-end; padding: 14px 0 0; border-top: 2px solid #eee; margin-top: 4px; font-size: 16px; }
        .total-row strong { color: #667eea; font-size: 20px; margin-left: 12px; }

        .btn { padding: 10px 22px; border-radius: 6px; font-size: 14px; font-weight: bold; text-decoration: none; display: inline-block; }
        .btn-outline { background: white; color: #667eea; border: 2px solid #667eea; }
        .btn-outline:hover { background: #667eea; color: white; }
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
    <div class="page-title">🧾 Chi tiết đơn hàng #<?php echo $order['id']; ?></div>

    <!-- Thông tin đơn hàng -->
    <div class="card">
        <div class="card-header">📋 Thông tin đơn hàng</div>
        <div class="card-body">
            <div class="info-row">
                <span class="info-label">Mã đơn</span>
                <span class="info-value"><strong>#<?php echo $order['id']; ?></strong></span>
            </div>
            <div class="info-row">
                <span class="info-label">Ngày đặt</span>
                <span class="info-value"><?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">📍 Địa điểm nhận</span>
                <span class="info-value"><?php echo htmlspecialchars($order['delivery_location'] ?? '—'); ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Trạng thái</span>
                <span class="info-value"><?php echo statusBadge($order['status']); ?></span>
            </div>
        </div>
    </div>

    <!-- Danh sách món -->
    <div class="card">
        <div class="card-header">🍽️ Danh sách món</div>
        <div class="card-body" style="padding:0;">
            <table>
                <thead>
                    <tr>
                        <th style="width:50%">Món ăn</th>
                        <th>Đơn giá</th>
                        <th>SL</th>
                        <th>Thành tiền</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orderItems as $item): ?>
                    <tr>
                        <td>
                            <div class="product-info">
                                <?php if ($item['image']): ?>
                                    <img class="product-img"
                                         src="assets/images/products/<?php echo htmlspecialchars($item['image']); ?>"
                                         alt="<?php echo htmlspecialchars($item['name']); ?>">
                                <?php else: ?>
                                    <div class="product-img" style="display:flex;align-items:center;justify-content:center;font-size:26px;">🍽️</div>
                                <?php endif; ?>
                                <?php echo htmlspecialchars($item['name']); ?>
                            </div>
                        </td>
                        <td><?php echo number_format($item['price'], 0, ',', '.'); ?>đ</td>
                        <td><?php echo $item['quantity']; ?></td>
                        <td><strong><?php echo number_format($item['price'] * $item['quantity'], 0, ',', '.'); ?>đ</strong></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div style="padding: 0 14px;">
                <div class="total-row">
                    Tổng cộng: <strong><?php echo number_format($order['total_price'], 0, ',', '.'); ?>đ</strong>
                </div>
            </div>
            <div style="padding: 16px 14px;">
                <a href="?c=order&a=history" class="btn btn-outline">← Quay lại lịch sử</a>
            </div>
        </div>
    </div>
</div>

</body>
</html>
