<?php

/**
 * Cart Model
 * Quản lý giỏ hàng (F07, F08)
 */
class Cart extends BaseModel
{
    protected $table      = 'carts';
    protected $tableItems = 'cart_items';

    // Lấy giỏ hàng của user, nếu chưa có thì tạo mới
    public function getOrCreateCart($user_id)
    {
        $sql  = "SELECT * FROM {$this->table} WHERE user_id = :user_id LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['user_id' => $user_id]);
        $cart = $stmt->fetch();

        if (!$cart) {
            $sql  = "INSERT INTO {$this->table} (user_id) VALUES (:user_id)";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute(['user_id' => $user_id]);
            $cart_id = $this->conn->lastInsertId();
            return ['id' => $cart_id, 'user_id' => $user_id];
        }

        return $cart;
    }

    // Lấy danh sách items trong giỏ (JOIN product để có tên, giá, ảnh)
    public function getCartItems($cart_id)
    {
        $sql = "SELECT ci.*, p.name, p.price, p.sale_price, p.image
                FROM {$this->tableItems} ci
                JOIN products p ON ci.product_id = p.id
                WHERE ci.cart_id = :cart_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['cart_id' => $cart_id]);
        return $stmt->fetchAll();
    }

    // Lấy một item cụ thể trong giỏ

    // Thêm item vào giỏ, nếu đã có thì tăng số lượng
    public function addItem($cart_id, $product_id, $quantity = 1)
    {
        // Kiểm tra đã có item chưa
        $sql  = "SELECT * FROM {$this->tableItems}
                 WHERE cart_id = :cart_id AND product_id = :product_id LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['cart_id' => $cart_id, 'product_id' => $product_id]);
        $item = $stmt->fetch();

        if ($item) {
            // Tăng số lượng
            $sql  = "UPDATE {$this->tableItems}
                     SET quantity = quantity + :quantity
                     WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute(['quantity' => $quantity, 'id' => $item['id']]);
        }

        // Thêm mới
        $sql  = "INSERT INTO {$this->tableItems} (cart_id, product_id, quantity)
                 VALUES (:cart_id, :product_id, :quantity)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            'cart_id'    => $cart_id,
            'product_id' => $product_id,
            'quantity'   => $quantity,
        ]);
    }

    // Cập nhật số lượng item (đặt cụ thể, không cộng thêm)
    public function updateItem($item_id, $quantity)
    {
        if ($quantity <= 0) {
            return $this->removeItem($item_id);
        }
        $sql  = "UPDATE {$this->tableItems} SET quantity = :quantity WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute(['quantity' => $quantity, 'id' => $item_id]);
    }

    // Xóa một item khỏi giỏ
    public function removeItem($item_id)
    {
        $sql  = "DELETE FROM {$this->tableItems} WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute(['id' => $item_id]);
    }

    // Xóa toàn bộ items trong giỏ (dùng sau khi đặt hàng thành công)
    public function clearCart($cart_id)
    {
        $sql  = "DELETE FROM {$this->tableItems} WHERE cart_id = :cart_id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute(['cart_id' => $cart_id]);
    }
    
    // Đếm tổng số items (dùng cho badge header)
    public function getItemCount($cart_id)
    {
        $sql  = "SELECT COALESCE(SUM(quantity), 0) as total FROM {$this->tableItems} WHERE cart_id = :cart_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['cart_id' => $cart_id]);
        $row = $stmt->fetch();
        return (int) $row['total'];
    }

    // Tính tổng tiền giỏ hàng
    public function getTotal($cart_id)
    {
        $sql = "SELECT COALESCE(SUM(ci.quantity * COALESCE(p.sale_price, p.price)), 0) as total
                FROM {$this->tableItems} ci
                JOIN products p ON ci.product_id = p.id
                WHERE ci.cart_id = :cart_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['cart_id' => $cart_id]);
        $row = $stmt->fetch();
        return (float) $row['total'];
    }

}
