<?php
/**
 * US10: Danh sách sản phẩm (Admin)
 */
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý sản phẩm</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f5f5f5; }
        .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 20px; }
        .navbar { display: flex; gap: 20px; padding: 15px 20px; background: white; border-bottom: 1px solid #ddd; }
        .navbar a { text-decoration: none; color: #333; font-weight: bold; }
        .navbar a:hover { color: #667eea; }
        .container { max-width: 1200px; margin: 20px auto; padding: 20px; }
        .page-title { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .btn { padding: 10px 20px; background: #667eea; color: white; border: none; border-radius: 5px; text-decoration: none; cursor: pointer; }
        .btn:hover { background: #764ba2; }
        .btn-danger { background: #e74c3c; }
        .btn-danger:hover { background: #c0392b; }
        .success { background: #d4edda; color: #155724; padding: 12px; border-radius: 5px; margin-bottom: 15px; }
        table { width: 100%; border-collapse: collapse; background: white; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); }
        th { background: #667eea; color: white; padding: 15px; text-align: left; }
        td { padding: 12px 15px; border-bottom: 1px solid #ddd; }
        tr:hover { background: #f9f9f9; }
        .actions { display: flex; gap: 10px; }
        .actions a { padding: 5px 10px; font-size: 12px; }
        .product-img { max-width: 50px; }
        .price { color: #e74c3c; font-weight: bold; }
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
        <div class="page-title">
            <h2>📋 Danh sách sản phẩm</h2>
            <a href="?c=adminProduct&a=add" class="btn">➕ Thêm sản phẩm</a>
        </div>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
        <?php endif; ?>

        <?php if (empty($products)): ?>
            <p>Không có sản phẩm nào</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Ảnh</th>
                        <th>Tên sản phẩm</th>
                        <th>Danh mục</th>
                        <th>Giá</th>
                        <th>Tồn kho</th>
                        <th>Ngày tạo</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $product): ?>
                        <tr>
                            <td><?php echo $product['id']; ?></td>
                            <td>
                                <?php if ($product['image']): ?>
                                    <img src="<?php echo BASE_ASSETS_UPLOADS . $product['image']; ?>" alt="" class="product-img">
                                <?php else: ?>
                                    📸
                                <?php endif; ?>
                            </td>
                            <td><?php echo $product['name']; ?></td>
                            <td><?php echo $product['category_name']; ?></td>
                            <td class="price"><?php echo number_format($product['price'], 0, ',', '.'); ?> VNĐ</td>
                            <td><?php echo $product['stock']; ?></td>
                            <td><?php echo date('d/m/Y', strtotime($product['created_at'])); ?></td>
                            <td>
                                <div class="actions">
                                    <a href="?c=adminProduct&a=edit&id=<?php echo $product['id']; ?>" class="btn">✏️ Sửa</a>
                                    <a href="?c=adminProduct&a=delete&id=<?php echo $product['id']; ?>" class="btn btn-danger" onclick="return confirm('Bạn chắc chắn muốn xóa?')">🗑️ Xóa</a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>
