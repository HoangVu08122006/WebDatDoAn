<?php
/**
 * F09, F10: Trang xác nhận đặt hàng + chọn địa điểm nhận
 */
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xác nhận đặt hàng – Căn Tin</title>
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

        .alert { padding: 12px 16px; border-radius: 6px; margin-bottom: 16px; font-size: 14px; }
        .alert-error { background: #f8d7da; color: #721c24; border-left: 4px solid #dc3545; }

        .card { background: white; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.08); margin-bottom: 20px; overflow: hidden; }
        .card-header { background: #f8f9fa; padding: 16px 20px; border-bottom: 1px solid #eee; font-weight: bold; font-size: 16px; }
        .card-body { padding: 20px; }

        /* Order summary table */
        table { width: 100%; border-collapse: collapse; font-size: 14px; }
        th { padding: 10px; text-align: left; background: #667eea; color: white; }
        td { padding: 12px 10px; border-bottom: 1px solid #f0f0f0; vertical-align: middle; }
        tr:last-child td { border-bottom: none; }

        .product-info { display: flex; align-items: center; gap: 10px; }
        .product-img  { width: 48px; height: 48px; object-fit: cover; border-radius: 5px; background: #eee; }

        /* Total row */
        .total-row { display: flex; justify-content: flex-end; padding: 14px 0 0; font-size: 16px; }
        .total-row strong { color: #667eea; font-size: 20px; margin-left: 12px; }

        /* Location select */
        .form-group { margin-bottom: 16px; }
        .form-group label { display: block; font-weight: bold; margin-bottom: 8px; font-size: 14px; }
        .form-group select,
        .form-group input[type=text] {
            width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px;
            font-size: 14px; outline: none;
        }
        .form-group select:focus,
        .form-group input[type=text]:focus { border-color: #667eea; box-shadow: 0 0 0 3px rgba(102,126,234,.15); }

        .form-actions { display: flex; gap: 12px; justify-content: flex-end; margin-top: 10px; }
        .btn { padding: 12px 26px; border-radius: 6px; font-size: 14px; font-weight: bold; border: none; cursor: pointer; text-decoration: none; display: inline-block; }
        .btn-primary { background: #667eea; color: white; }
        .btn-primary:hover { background: #764ba2; }
        .btn-outline  { background: white; color: #667eea; border: 2px solid #667eea; }
        .btn-outline:hover  { background: #667eea; color: white; }
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
    <div class="page-title">✅ Xác nhận đặt hàng</div>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-error"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <!-- Tóm tắt giỏ hàng -->
    <div class="card">
        <div class="card-header">🛒 Danh sách món đặt</div>
        <div class="card-body">
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
                    <?php foreach ($cartItems as $item):
                        $unitPrice = $item['sale_price'] ?? $item['price'];
                        $lineTotal = $unitPrice * $item['quantity'];
                    ?>
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
                        <td><?php echo number_format($unitPrice, 0, ',', '.'); ?>đ</td>
                        <td><?php echo $item['quantity']; ?></td>
                        <td><strong><?php echo number_format($lineTotal, 0, ',', '.'); ?>đ</strong></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div class="total-row">
                Tổng cộng: <strong><?php echo number_format($total, 0, ',', '.'); ?>đ</strong>
            </div>
        </div>
    </div>

    <!-- F10: Chọn địa điểm nhận hàng -->
    <div class="card">
        <div class="card-header">📍 Chọn địa điểm nhận hàng</div>
        <div class="card-body">
            <form method="POST" action="?c=order&a=placeOrder">
                <div class="form-group">
                    <label for="delivery_location">Địa điểm nhận đồ ăn *</label>
                    <select name="delivery_location" id="delivery_location" required>
                        <option value="">-- Chọn địa điểm --</option>
                        <?php foreach ($locations as $loc): ?>
                            <option value="<?php echo htmlspecialchars($loc); ?>">
                                <?php echo htmlspecialchars($loc); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="delivery_note">Ghi chú (tùy chọn)</label>
                    <input type="text" name="delivery_note" id="delivery_note"
                           placeholder="VD: Lớp 301A, phòng cuối hành lang...">
                </div>

                <div class="form-actions">
                    <a href="?c=cart&a=index" class="btn btn-outline">← Quay lại giỏ hàng</a>
                    <button type="submit" class="btn btn-primary">🍱 Xác nhận đặt hàng</button>
                </div>
            </form>
        </div>
    </div>
</div>

</body>
</html>
