<?php
/**
 * F12: Danh sách đơn hàng (Admin)
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
    return "<span style=\"background:{$color};color:white;padding:4px 10px;border-radius:12px;font-size:12px;font-weight:bold;white-space:nowrap;\">{$label}</span>";
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý đơn hàng – Admin</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f5f5f5; }
        .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 20px; }
        .navbar { display: flex; gap: 20px; padding: 15px 20px; background: white; border-bottom: 1px solid #ddd; flex-wrap: wrap; }
        .navbar a { text-decoration: none; color: #333; font-weight: bold; font-size: 14px; }
        .navbar a:hover { color: #667eea; }
        .container { max-width: 1200px; margin: 24px auto; padding: 0 16px; }
        .page-title { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 12px; }
        .page-title h2 { font-size: 20px; }

        /* Filter */
        .filter-bar { display: flex; gap: 8px; align-items: center; flex-wrap: wrap; margin-bottom: 16px; background: white; padding: 14px 16px; border-radius: 8px; box-shadow: 0 1px 4px rgba(0,0,0,.08); }
        .filter-bar label { font-size: 14px; font-weight: bold; color: #555; }
        .filter-bar select { padding: 7px 12px; border: 1px solid #ddd; border-radius: 5px; font-size: 14px; }
        .filter-bar button { padding: 8px 16px; background: #667eea; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 14px; }
        .filter-bar button:hover { background: #764ba2; }
        .filter-bar a { padding: 8px 16px; color: #667eea; text-decoration: none; font-size: 14px; }

        /* Alert */
        .alert { padding: 12px 16px; border-radius: 6px; margin-bottom: 16px; font-size: 14px; }
        .alert-success { background: #d4edda; color: #155724; border-left: 4px solid #28a745; }
        .alert-error   { background: #f8d7da; color: #721c24; border-left: 4px solid #dc3545; }

        /* Table */
        .card { background: white; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.08); overflow: hidden; }
        table { width: 100%; border-collapse: collapse; font-size: 14px; }
        thead th { background: #667eea; color: white; padding: 13px 14px; text-align: left; }
        tbody td { padding: 12px 14px; border-bottom: 1px solid #f0f0f0; vertical-align: middle; }
        tbody tr:last-child td { border-bottom: none; }
        tbody tr:hover { background: #fafafa; }

        .btn { padding: 7px 14px; border-radius: 5px; font-size: 13px; font-weight: bold; text-decoration: none; display: inline-block; }
        .btn-primary { background: #667eea; color: white; }
        .btn-primary:hover { background: #764ba2; }

        .empty { text-align: center; padding: 50px 20px; color: #999; }
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
    <div class="page-title">
        <h2>📋 Danh sách đơn hàng</h2>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
    <?php endif; ?>
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-error"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <!-- Lọc theo trạng thái -->
    <form class="filter-bar" method="GET" action="">
        <input type="hidden" name="c" value="adminOrder">
        <input type="hidden" name="a" value="list">
        <label for="status-filter">Lọc trạng thái:</label>
        <select name="status" id="status-filter">
            <option value="">-- Tất cả --</option>
            <option value="pending"   <?php echo ($status ?? '') === 'pending'   ? 'selected' : ''; ?>>🕐 Chờ xác nhận</option>
            <option value="confirmed" <?php echo ($status ?? '') === 'confirmed' ? 'selected' : ''; ?>>✅ Đã xác nhận</option>
            <option value="shipping"  <?php echo ($status ?? '') === 'shipping'  ? 'selected' : ''; ?>>🚴 Đang giao</option>
            <option value="completed" <?php echo ($status ?? '') === 'completed' ? 'selected' : ''; ?>>🎉 Hoàn thành</option>
            <option value="cancelled" <?php echo ($status ?? '') === 'cancelled' ? 'selected' : ''; ?>>❌ Đã hủy</option>
        </select>
        <button type="submit">Lọc</button>
        <a href="?c=adminOrder&a=list">Xem tất cả</a>
    </form>

    <?php if (empty($orders)): ?>
        <div class="empty">Không có đơn hàng nào.</div>
    <?php else: ?>
        <div class="card">
            <table>
                <thead>
                    <tr>
                        <th>Mã đơn</th>
                        <th>Sinh viên</th>
                        <th>Email</th>
                        <th>Địa điểm nhận</th>
                        <th>Tổng tiền</th>
                        <th>Trạng thái</th>
                        <th>Ngày đặt</th>
                        <th>Chi tiết</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><strong>#<?php echo $order['id']; ?></strong></td>
                        <td><?php echo htmlspecialchars($order['user_name']); ?></td>
                        <td><?php echo htmlspecialchars($order['user_email']); ?></td>
                        <td>📍 <?php echo htmlspecialchars($order['delivery_location'] ?? '—'); ?></td>
                        <td><strong><?php echo number_format($order['total_price'], 0, ',', '.'); ?>đ</strong></td>
                        <td><?php echo statusBadge($order['status']); ?></td>
                        <td><?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?></td>
                        <td>
                            <a href="?c=adminOrder&a=detail&id=<?php echo $order['id']; ?>" class="btn btn-primary">
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
