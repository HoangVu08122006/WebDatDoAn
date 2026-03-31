<?php
/**
 * US02: Danh sách sản phẩm
 */
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách sản phẩm</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f5f5f5; }
        .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 20px; text-align: center; }
        .navbar { display: flex; justify-content: space-between; align-items: center; padding: 15px 20px; background: white; border-bottom: 1px solid #ddd; }
        .nav-links { display: flex; gap: 20px; }
        .nav-links a { text-decoration: none; color: #333; font-weight: bold; }
        .nav-links a:hover { color: #667eea; }
        .container { max-width: 1200px; margin: 20px auto; padding: 20px; }
        .filters { display: flex; gap: 10px; margin-bottom: 20px; flex-wrap: wrap; }
        .filters input { padding: 10px; border: 1px solid #ddd; border-radius: 5px; }
        .filters button { padding: 10px 20px; background: #667eea; color: white; border: none; border-radius: 5px; cursor: pointer; }
        .products-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 20px; }
        .product-card { background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); transition: transform 0.3s; cursor: pointer; }
        .product-card:hover { transform: translateY(-5px); }
        .product-image { width: 100%; height: 200px; background: #e0e0e0; display: flex; align-items: center; justify-content: center; }
        .product-image img { max-width: 100%; max-height: 100%; object-fit: cover; }
        .product-info { padding: 15px; }
        .product-name { font-weight: bold; margin-bottom: 10px; }
        .product-price { color: #e74c3c; font-size: 18px; margin-bottom: 10px; }
        .sale-price { color: #27ae60; text-decoration: line-through; }
        .product-desc { font-size: 12px; color: #666; margin-bottom: 10px; }
        .btn { padding: 10px 15px; background: #667eea; color: white; border: none; border-radius: 5px; cursor: pointer; text-decoration: none; display: inline-block; }
        .btn:hover { background: #764ba2; }
        .user-menu { display: flex; gap: 10px; }
        .empty { text-align: center; padding: 40px; color: #666; }
    </style>
</head>
<body>
    <div class="header">
        <h1>🍔 Cửa hàng Đặt đồ ăn</h1>
    </div>

    <div class="navbar">
        <div class="nav-links">
            <a href="?c=product&a=list">🏠 Trang chủ</a>
        </div>
        <div class="user-menu">
            <?php if (isset($_SESSION['user_id'])): ?>
                <span>Xin chào, <strong><?php echo $_SESSION['user_name']; ?></strong></span>
                <?php if ($_SESSION['user_role'] === 'admin'): ?>
                    <a href="?c=adminProduct&a=list">👨‍💼 Admin</a>
                <?php endif; ?>
                <a href="?c=auth&a=logout">Đăng xuất</a>
            <?php else: ?>
                <a href="?c=auth&a=login">Đăng nhập</a>
                <a href="?c=auth&a=register">Đăng ký</a>
            <?php endif; ?>
        </div>
    </div>

    <div class="container">
        <div class="filters">
            <input type="text" id="searchInput" placeholder="Tìm kiếm sản phẩm..." value="<?php echo isset($_GET['q']) ? $_GET['q'] : ''; ?>">
            <button onclick="search()">Tìm kiếm</button>
            <select id="categorySelect" onchange="filterByCategory()">
                <option value="">📋 Tất cả danh mục</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?php echo $cat['id']; ?>" <?php echo isset($_GET['category_id']) && $_GET['category_id'] == $cat['id'] ? 'selected' : ''; ?>>
                        <?php echo $cat['name']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <?php if (empty($products)): ?>
            <div class="empty">
                <p>Không tìm thấy sản phẩm nào!</p>
            </div>
        <?php else: ?>
            <div class="products-grid">
                <?php foreach ($products as $product): ?>
                    <div class="product-card" onclick="viewDetail(<?php echo $product['id']; ?>)">
                        <div class="product-image">
                            <?php if ($product['image']): ?>
                                <img src="<?php echo BASE_ASSETS_UPLOADS . $product['image']; ?>" alt="<?php echo $product['name']; ?>">
                            <?php else: ?>
                                📸
                            <?php endif; ?>
                        </div>
                        <div class="product-info">
                            <div class="product-name"><?php echo $product['name']; ?></div>
                            <div class="product-price">
                                <?php echo number_format($product['sale_price'] ?? $product['price'], 0, ',', '.'); ?> VNĐ
                                <?php if ($product['sale_price']): ?>
                                    <div class="sale-price"><?php echo number_format($product['price'], 0, ',', '.'); ?> VNĐ</div>
                                <?php endif; ?>
                            </div>
                            <div class="product-desc"><?php echo substr($product['description'], 0, 50) . '...'; ?></div>
                            <button class="btn" onclick="viewDetail(<?php echo $product['id']; ?>)">Xem chi tiết</button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <script>
        function search() {
            const keyword = document.getElementById('searchInput').value;
            if (keyword) {
                window.location.href = '?c=product&a=search&q=' + encodeURIComponent(keyword);
            }
        }

        function filterByCategory() {
            const categoryId = document.getElementById('categorySelect').value;
            if (categoryId) {
                window.location.href = '?c=product&a=filterByCategory&category_id=' + categoryId;
            } else {
                window.location.href = '?c=product&a=list';
            }
        }

        function viewDetail(productId) {
            window.location.href = '?c=product&a=detail&id=' + productId;
        }

        document.getElementById('searchInput').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') search();
        });
    </script>
</body>
</html>
