<?php

/**
 * Order Model
 * Quản lý đơn hàng – F09 (Đặt món), F10 (Địa điểm), F13 (Lịch sử)
 */
class Order extends BaseModel
{
    protected $table      = 'orders';
    protected $tableItems = 'order_items';

    // Tạo đơn hàng mới
    public function createOrder($user_id, $total_price, $delivery_location)
    {
        $sql = "INSERT INTO {$this->table} (user_id, total_price, delivery_location, status)
                VALUES (:user_id, :total_price, :delivery_location, 'pending')";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'user_id'           => $user_id,
            'total_price'       => $total_price,
            'delivery_location' => $delivery_location,
        ]);
        return $this->conn->lastInsertId();
    }

    // Thêm item vào đơn hàng
    public function addOrderItem($order_id, $product_id, $quantity, $price)
    {
        $sql  = "INSERT INTO {$this->tableItems} (order_id, product_id, quantity, price)
                 VALUES (:order_id, :product_id, :quantity, :price)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            'order_id'   => $order_id,
            'product_id' => $product_id,
            'quantity'   => $quantity,
            'price'      => $price,
        ]);
    }

    // Lịch sử đơn hàng của một sinh viên
    public function getByUser($user_id)
    {
        $sql  = "SELECT * FROM {$this->table}
                 WHERE user_id = :user_id
                 ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['user_id' => $user_id]);
        return $stmt->fetchAll();
    }

    // Tất cả đơn hàng (admin)
    public function getAll()
    {
        $sql = "SELECT o.*, u.name as user_name, u.email as user_email
                FROM {$this->table} o
                JOIN users u ON o.user_id = u.id
                ORDER BY o.created_at DESC";
        return $this->conn->query($sql)->fetchAll();
    }

    // Chi tiết một đơn hàng
    public function getById($id)
    {
        $sql  = "SELECT o.*, u.name as user_name, u.email as user_email
                 FROM {$this->table} o
                 JOIN users u ON o.user_id = u.id
                 WHERE o.id = :id LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    // Lấy danh sách items của một đơn hàng
    public function getOrderItems($order_id)
    {
        $sql  = "SELECT oi.*, p.name, p.image
                 FROM {$this->tableItems} oi
                 JOIN products p ON oi.product_id = p.id
                 WHERE oi.order_id = :order_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['order_id' => $order_id]);
        return $stmt->fetchAll();
    }

    // Admin cập nhật trạng thái đơn hàng
    public function updateStatus($id, $status)
    {
        $allowed = ['pending', 'confirmed', 'shipping', 'completed', 'cancelled'];
        if (!in_array($status, $allowed)) {
            return false;
        }
        $sql  = "UPDATE {$this->table} SET status = :status WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute(['status' => $status, 'id' => $id]);
    }
}
