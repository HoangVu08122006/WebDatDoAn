<?php
/**
 * Chi tiết sản phẩm
 */
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $product['name']; ?></title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f5f5f5; }
        .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 20px; text-align: center; }
        .navbar { display: flex; justify-content: space-between; align-items: center; padding: 15px 20px; background: white; border-bottom: 1px solid #ddd; }
        .container { max-width: 1000px; margin: 20px auto; padding: 20px; background: white; border-radius: 10px; }
        .back-btn { display: inline-block; margin-bottom: 20px; padding: 10px 20px; background: #667eea; color: white; text-decoration: none; border-radius: 5px; }
        .product-detail { display: grid; grid-template-columns: 1fr 1fr; gap: 30px; }
        .product-image { width: 100%; height: 400px; background: #e0e0e0; display: flex; align-items: center; justify-content: center; border-radius: 10px; }
        .product-image img { max-width: 100%; max-height: 100%; object-fit: contain; }
        .product-info h1 { margin-bottom: 15px; }
        .product-price { font-size: 24px; color: #e74c3c; margin: 15px 0; }
        .product-category { color: #999; margin-bottom: 15px; }
        .product-stock { margin: 15px 0; }
        .product-desc { line-height: 1.6; margin: 20px 0; }
        .btn { padding: 12px 30px; background: #667eea; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; }
        .btn:hover { background: #764ba2; }
        @media (max-width: 768px) {
            .product-detail { grid-template-columns: 1fr; }
            .product-image { height: 300px; }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🍔 Cửa hàng Đặt đồ ăn</h1>
    </div>

    <div class="navbar">
        <a href="?c=product&a=list">← Quay lại</a>
    </div>

    <div class="container">
        <a href="?c=product&a=list" class="back-btn">← Quay lại danh sách</a>

        <div class="product-detail">
            <div class="product-image">
                <?php if ($product['image']): ?>
                    <img src="<?php echo BASE_ASSETS_UPLOADS . $product['image']; ?>" alt="<?php echo $product['name']; ?>">
                <?php else: ?>
                    📸 Không có ảnh
                <?php endif; ?>
            </div>

            <div class="product-info">
                <h1><?php echo $product['name']; ?></h1>
                <div class="product-category">📋 <?php echo $product['category_name'] ?? 'Không có'; ?></div>

                <div class="product-price">
                    Giá: <?php echo number_format($product['sale_price'] ?? $product['price'], 0, ',', '.'); ?> VNĐ
                </div>

                <?php if ($product['sale_price']): ?>
                    <div style="color: #999; text-decoration: line-through;">
                        Giá gốc: <?php echo number_format($product['price'], 0, ',', '.'); ?> VNĐ
                    </div>
                <?php endif; ?>

                <div class="product-stock">
                    <strong>Tồn kho:</strong> <?php echo $product['stock']; ?> sản phẩm
                </div>

                <div class="product-desc">
                    <strong>Mô tả:</strong><br>
                    <?php echo nl2br($product['description']); ?>
                </div>

                <?php if ($product['stock'] > 0): ?>
                    <div>
                        <input type="number" id="quantity" value="1" min="1" max="<?php echo $product['stock']; ?>" style="padding: 10px; width: 80px; margin-right: 10px;">
                        <button class="btn" onclick="addToCart(<?php echo $product['id']; ?>)">🛒 Thêm vào giỏ</button>
                    </div>
                <?php else: ?>
                    <button class="btn" disabled style="background: #999; cursor: not-allowed;">❌ Hết hàng</button>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        function addToCart(productId) {
            const quantity = document.getElementById('quantity').value;
            alert('Thêm ' + quantity + ' sản phẩm vào giỏ hàng!');
            // TODO: Triển khai thêm vào giỏ hàng
        }
    </script>
</body>
</html>
