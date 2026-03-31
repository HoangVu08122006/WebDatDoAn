<?php
/**
 * US10: Sửa sản phẩm (Admin)
 */
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa sản phẩm</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f5f5f5; }
        .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 20px; }
        .navbar { display: flex; gap: 20px; padding: 15px 20px; background: white; border-bottom: 1px solid #ddd; }
        .navbar a { text-decoration: none; color: #333; font-weight: bold; }
        .container { max-width: 600px; margin: 20px auto; }
        .form-container { background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); }
        h2 { margin-bottom: 20px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input, select, textarea { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; font-size: 14px; font-family: Arial, sans-serif; }
        input:focus, select:focus, textarea:focus { outline: none; border-color: #667eea; }
        .btn { display: inline-block; padding: 10px 20px; background: #667eea; color: white; border: none; border-radius: 5px; cursor: pointer; margin-right: 10px; text-decoration: none; }
        .btn:hover { background: #764ba2; }
        .btn-cancel { background: #95a5a6; }
        .btn-cancel:hover { background: #7f8c8d; }
        .current-image { margin-bottom: 10px; }
        .current-image img { max-width: 150px; border-radius: 5px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>👨‍💼 Quản lý hệ thống</h1>
    </div>

    <div class="navbar">
        <a href="?c=adminProduct&a=list">← Quay lại</a>
    </div>

    <div class="container">
        <div class="form-container">
            <h2>✏️ Sửa sản phẩm: <?php echo $product['name']; ?></h2>

            <form method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="name">Tên sản phẩm: <span style="color: red;">*</span></label>
                    <input type="text" id="name" name="name" value="<?php echo $product['name']; ?>" required>
                </div>

                <div class="form-group">
                    <label for="category_id">Danh mục: <span style="color: red;">*</span></label>
                    <select id="category_id" name="category_id" required>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?php echo $cat['id']; ?>" <?php echo $cat['id'] == $product['category_id'] ? 'selected' : ''; ?>>
                                <?php echo $cat['name']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="price">Giá: <span style="color: red;">*</span></label>
                    <input type="number" id="price" name="price" step="0.01" value="<?php echo $product['price']; ?>" required>
                </div>

                <div class="form-group">
                    <label for="sale_price">Giá bán (nếu có):</label>
                    <input type="number" id="sale_price" name="sale_price" step="0.01" value="<?php echo $product['sale_price'] ?? ''; ?>">
                </div>

                <div class="form-group">
                    <label for="stock">Tồn kho:</label>
                    <input type="number" id="stock" name="stock" value="<?php echo $product['stock']; ?>">
                </div>

                <div class="form-group">
                    <label for="description">Mô tả:</label>
                    <textarea id="description" name="description" rows="5"><?php echo $product['description']; ?></textarea>
                </div>

                <div class="form-group">
                    <?php if ($product['image']): ?>
                        <div class="current-image">
                            <p><strong>Ảnh hiện tại:</strong></p>
                            <img src="<?php echo BASE_ASSETS_UPLOADS . $product['image']; ?>" alt="">
                        </div>
                    <?php endif; ?>
                    <label for="image">Thay đổi ảnh:</label>
                    <input type="file" id="image" name="image" accept="image/*">
                </div>

                <div>
                    <button type="submit" class="btn">💾 Lưu</button>
                    <a href="?c=adminProduct&a=list" class="btn btn-cancel">❌ Hủy</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
