<?php
/**
 * F11, F12: Chi tiết đơn hàng + form cập nhật trạng thái (Admin)
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
    <title>Chi tiết đơn hàng #<?php echo $order['id']; ?> – Admin</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f5f5f5; color: #333; }
        .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 20px; }
        .navbar { display: flex; gap: 20px; padding: 15px 20px; background: white; border-bottom: 1px solid #ddd; flex-wrap: wrap; }
        .navbar a { text-decoration: none; color: #333; font-weight: bold; font-size: 14px; }
        .navbar a:hover { color: #667eea; }

        .container { max-width: 900px; margin: 28px auto; padding: 0 16px; }
        .page-title { font-size: 22px; font-weight: bold; margin-bottom: 20px; }

        .alert { padding: 12px 16px; border-radius: 6px; margin-bottom: 16px; font-size: 14px; }
        .alert-success { background: #d4edda; color: #155724; border-left: 4px solid #28a745; }
        .alert-error   { background: #f8d7da; color: #721c24; border-left: 4px solid #dc3545; }

        .card { background: white; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.08); margin-bottom: 20px; overflow: hidden; }
        .card-header { background: #f8f9fa; padding: 16px 20px; border-bottom: 1px solid #eee; font-weight: bold; font-size: 15px; }
        .card-body { padding: 20px; }

        .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 0; }
        .info-row { display: flex; padding: 10px 0; border-bottom: 1px solid #f5f5f5; font-size: 14px; }
        .info-row:last-child { border-bottom: none; }
        .info-label { width: 160px; color: #666; font-weight: bold; flex-shrink: 0; }
        .info-value { flex: 1; }

        table { width: 100%; border-collapse: collapse; font-size: 14px; }
        thead th { background: #667eea; color: white; padding: 12px 14px; text-align: left; }
        tbody td { padding: 12px 14px; border-bottom: 1px solid #f0f0f0; vertical-align: middle; }
        tbody tr:last-child td { border-bottom: none; }
        tbody tr:hover { background: #fafafa; }

        .product-info { display: flex; align-items: center; gap: 10px; }
        .product-img  { width: 48px; height: 48px; object-fit: cover; border-radius: 5px; background: #eee; }

        .total-row { display: flex; justify-content: flex-end; padding: 14px 14px 0; border-top: 2px solid #eee; font-size: 16px; }
        .total-row strong { color: #667eea; font-size: 20px; margin-left: 12px; }

        /* F11: Form cập nhật trạng thái */
        .status-form { display: flex; align-items: center; gap: 12px; flex-wrap: wrap; }
        .status-form select { padding: 10px 14px; border: 2px solid #667eea; border-radius: 6px; font-size: 14px; }
        .status-form button { padding: 10px 22px; background: #667eea; color: white; border: none; border-radius: 6px; font-size: 14px; font-weight: bold; cursor: pointer; }
        .status-form button:hover { background: #764ba2; }
        .current-status { display: flex; align-items: center; gap: 10px; margin-bottom: 16px; font-size: 14px; }

        .btn { padding: 10px 22px; border-radius: 6px; font-size: 14px; font-weight: bold; text-decoration: none; display: inline-block; }
        .btn-outline { background: white; color: #667eea; border: 2px solid #667eea; }
        .btn-outline:hover { background: #667eea; color: white; }
    </style>
</head>
<body>

<div class="header">
    <h1>👨‍💼 Quản lý hệ thống</h1>
</div>

<div class="navbar">
    <a href="?c=adminProduct&a=list">📦 Sản phẩm</a>
    <a href="?c=adminUser&a=list">👤 Tài khoản</a>
    <a href="?c=adminOrder&a=list">📋 Đơn hàng</a>
    <a href="?c=product&a=list">🏠 Trang chủ</a>
    <a href="?c=auth&a=logout" style="margin-left:auto;color:#e74c3c;">Đăng xuất</a>
</div>

<div class="container">
    <div class="page-title">📋 Chi tiết đơn hàng #<?php echo $order['id']; ?></div>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
    <?php endif; ?>
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-error"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <!-- Thông tin đơn hàng và sinh viên -->
    <div class="card">
        <div class="card-header">📋 Thông tin đơn hàng</div>
        <div class="card-body">
            <div class="info-row"><span class="info-label">Mã đơn</span><span class="info-value"><strong>#<?php echo $order['id']; ?></strong></span></div>
            <div class="info-row"><span class="info-label">👤 Sinh viên</span><span class="info-value"><?php echo htmlspecialchars($order['user_name']); ?></span></div>
            <div class="info-row"><span class="info-label">📧 Email</span><span class="info-value"><?php echo htmlspecialchars($order['user_email']); ?></span></div>
            <div class="info-row"><span class="info-label">📍 Địa điểm nhận</span><span class="info-value"><?php echo htmlspecialchars($order['delivery_location'] ?? '—'); ?></span></div>
            <div class="info-row"><span class="info-label">📅 Ngày đặt</span><span class="info-value"><?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?></span></div>
            <div class="info-row"><span class="info-label">Trạng thái hiện tại</span><span class="info-value"><?php echo statusBadge($order['status']); ?></span></div>
        </div>
    </div>

    <!-- F11: Cập nhật trạng thái -->
    <div class="card">
        <div class="card-header">🔄 Cập nhật trạng thái đơn hàng</div>
        <div class="card-body">
            <form method="POST" action="?c=adminOrder&a=updateStatus" class="status-form">
                <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                <select name="status" id="status-select">
                    <option value="pending"   <?php echo $order['status'] === 'pending'   ? 'selected' : ''; ?>>🕐 Chờ xác nhận</option>
                    <option value="confirmed" <?php echo $order['status'] === 'confirmed' ? 'selected' : ''; ?>>✅ Đã xác nhận</option>
                    <option value="shipping"  <?php echo $order['status'] === 'shipping'  ? 'selected' : ''; ?>>🚴 Đang giao</option>
                    <option value="completed" <?php echo $order['status'] === 'completed' ? 'selected' : ''; ?>>🎉 Hoàn thành</option>
                    <option value="cancelled" <?php echo $order['status'] === 'cancelled' ? 'selected' : ''; ?>>❌ Đã hủy</option>
                </select>
                <button type="submit">💾 Cập nhật</button>
            </form>
        </div>
    </div>

    <!-- Danh sách món -->
    <div class="card">
        <div class="card-header">🍽️ Danh sách món đặt</div>
        <div style="overflow:hidden;">
            <table>
                <thead>
                    <tr>
                        <th style="width:45%">Món ăn</th>
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
            <div class="total-row">
                Tổng cộng: <strong><?php echo number_format($order['total_price'], 0, ',', '.'); ?>đ</strong>
            </div>
        </div>
        <div style="padding:16px;">
            <a href="?c=adminOrder&a=list" class="btn btn-outline">← Quay lại danh sách</a>
        </div>
    </div>
</div>

</body>
</html>
