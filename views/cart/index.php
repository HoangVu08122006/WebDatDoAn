<?php
/**
 * F07, F08: Trang giỏ hàng – xem, tăng/giảm, xóa món
 */
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giỏ hàng – Căn Tin</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f5f5f5; color: #333; }

        /* ===== HEADER ===== */
        .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 18px 24px; display: flex; align-items: center; justify-content: space-between; }
        .header h1 { font-size: 20px; }
        .navbar { display: flex; gap: 24px; padding: 12px 24px; background: white; border-bottom: 1px solid #ddd; flex-wrap: wrap; }
        .navbar a { text-decoration: none; color: #333; font-weight: bold; font-size: 14px; }
        .navbar a:hover { color: #667eea; }
        .navbar .user-info { margin-left: auto; color: #667eea; font-size: 14px; }

        /* ===== CONTAINER ===== */
        .container { max-width: 960px; margin: 30px auto; padding: 0 16px; }
        .page-title { font-size: 22px; font-weight: bold; margin-bottom: 20px; }

        /* ===== ALERTS ===== */
        .alert { padding: 12px 16px; border-radius: 6px; margin-bottom: 16px; font-size: 14px; }
        .alert-success { background: #d4edda; color: #155724; border-left: 4px solid #28a745; }
        .alert-error   { background: #f8d7da; color: #721c24; border-left: 4px solid #dc3545; }

        /* ===== CART TABLE ===== */
        .cart-wrap { background: white; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.08); overflow: hidden; }
        table { width: 100%; border-collapse: collapse; }
        thead th { background: #667eea; color: white; padding: 14px 16px; text-align: left; font-size: 14px; }
        tbody td { padding: 14px 16px; border-bottom: 1px solid #f0f0f0; vertical-align: middle; font-size: 14px; }
        tbody tr:last-child td { border-bottom: none; }
        tbody tr:hover { background: #fafafa; }

        .product-info { display: flex; align-items: center; gap: 12px; }
        .product-img  { width: 60px; height: 60px; object-fit: cover; border-radius: 6px; background: #eee; }
        .product-name { font-weight: bold; font-size: 14px; }

        /* ===== QTY CONTROL ===== */
        .qty-form { display: flex; align-items: center; gap: 6px; }
        .qty-form button { width: 30px; height: 30px; border: 1px solid #ddd; background: #f5f5f5; border-radius: 4px; cursor: pointer; font-size: 16px; line-height: 1; }
        .qty-form button:hover { background: #667eea; color: white; border-color: #667eea; }
        .qty-form input[type=number] { width: 50px; height: 30px; text-align: center; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; }

        .btn-remove { background: none; border: none; color: #e74c3c; cursor: pointer; font-size: 18px; }
        .btn-remove:hover { color: #c0392b; }

        /* ===== SUMMARY ===== */
        .summary { display: flex; justify-content: flex-end; align-items: center; gap: 20px; padding: 20px; flex-wrap: wrap; }
        .summary .total-label { font-size: 16px; color: #555; }
        .summary .total-price { font-size: 22px; font-weight: bold; color: #667eea; }
        .summary .actions { display: flex; gap: 12px; }
        .btn { padding: 10px 22px; border-radius: 6px; font-size: 14px; font-weight: bold; text-decoration: none; border: none; cursor: pointer; }
        .btn-primary  { background: #667eea; color: white; }
        .btn-primary:hover  { background: #764ba2; }
        .btn-danger   { background: #e74c3c; color: white; }
        .btn-danger:hover   { background: #c0392b; }
        .btn-outline  { background: white; color: #667eea; border: 2px solid #667eea; }
        .btn-outline:hover  { background: #667eea; color: white; }

        .empty-cart { text-align: center; padding: 60px 20px; color: #999; }
        .empty-cart .icon { font-size: 60px; margin-bottom: 16px; }
        .empty-cart p { font-size: 16px; margin-bottom: 20px; }
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
    <div class="page-title">🛒 Giỏ hàng của bạn</div>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
    <?php endif; ?>
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-error"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <?php if (empty($cartItems)): ?>
        <div class="empty-cart">
            <div class="icon">🛒</div>
            <p>Giỏ hàng của bạn đang trống.</p>
            <a href="?c=product&a=list" class="btn btn-primary">Xem thực đơn</a>
        </div>
    <?php else: ?>
        <div class="cart-wrap">
            <table>
                <thead>
                    <tr>
                        <th style="width:40%">Món ăn</th>
                        <th>Đơn giá</th>
                        <th>Số lượng</th>
                        <th>Thành tiền</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cartItems as $item):
                        $unitPrice   = $item['sale_price'] ?? $item['price'];
                        $lineTotal   = $unitPrice * $item['quantity'];
                    ?>
                    <tr>
                        <td>
                            <div class="product-info">
                                <?php if ($item['image']): ?>
                                    <img class="product-img"
                                         src="assets/images/products/<?php echo htmlspecialchars($item['image']); ?>"
                                         alt="<?php echo htmlspecialchars($item['name']); ?>">
                                <?php else: ?>
                                    <div class="product-img" style="display:flex;align-items:center;justify-content:center;font-size:28px;">🍽️</div>
                                <?php endif; ?>
                                <span class="product-name"><?php echo htmlspecialchars($item['name']); ?></span>
                            </div>
                        </td>
                        <td><?php echo number_format($unitPrice, 0, ',', '.'); ?>đ</td>
                        <td>
                            <!-- F08: Form chỉnh sửa số lượng -->
                            <form method="POST" action="?c=cart&a=update" class="qty-form" id="qty-form-<?php echo $item['id']; ?>">
                                <input type="hidden" name="item_id" value="<?php echo $item['id']; ?>">
                                <button type="button" onclick="changeQty(<?php echo $item['id']; ?>, -1)">−</button>
                                <input type="number" name="quantity" id="qty-<?php echo $item['id']; ?>"
                                       value="<?php echo $item['quantity']; ?>" min="1" max="99"
                                       onchange="document.getElementById('qty-form-<?php echo $item['id']; ?>').submit()">
                                <button type="button" onclick="changeQty(<?php echo $item['id']; ?>, 1)">+</button>
                            </form>
                        </td>
                        <td><strong><?php echo number_format($lineTotal, 0, ',', '.'); ?>đ</strong></td>
                        <td>
                            <a href="?c=cart&a=remove&item_id=<?php echo $item['id']; ?>"
                               class="btn-remove"
                               title="Xóa món"
                               onclick="return confirm('Xóa món này khỏi giỏ hàng?')">🗑️</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div class="summary">
                <span class="total-label">Tổng cộng:</span>
                <span class="total-price"><?php echo number_format($total, 0, ',', '.'); ?>đ</span>
                <div class="actions">
                    <a href="?c=cart&a=clear"
                       class="btn btn-danger"
                       onclick="return confirm('Xóa toàn bộ giỏ hàng?')">🗑️ Xóa tất cả</a>
                    <a href="?c=product&a=list" class="btn btn-outline">← Tiếp tục mua</a>
                    <a href="?c=order&a=checkout" class="btn btn-primary">✅ Đặt hàng</a>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<script>
function changeQty(itemId, delta) {
    const input = document.getElementById('qty-' + itemId);
    let val = parseInt(input.value) + delta;
    if (val < 1) val = 1;
    if (val > 99) val = 99;
    input.value = val;
    document.getElementById('qty-form-' + itemId).submit();
}
</script>
</body>
</html>
