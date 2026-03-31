<?php

class Product extends BaseModel
{
    protected $table = 'products';

    // Lấy tất cả sản phẩm
    public function getAll()
    {
        $sql = "SELECT p.*, c.name as category_name FROM {$this->table} p 
                LEFT JOIN categories c ON p.category_id = c.id 
                ORDER BY p.created_at DESC";
        return $this->conn->query($sql)->fetchAll();
    }

    // Lấy sản phẩm theo danh mục
    public function getByCategory($category_id)
    {
        $sql = "SELECT p.*, c.name as category_name FROM {$this->table} p 
                LEFT JOIN categories c ON p.category_id = c.id 
                WHERE p.category_id = :category_id 
                ORDER BY p.created_at DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['category_id' => $category_id]);
        return $stmt->fetchAll();
    }

    // Lấy sản phẩm theo ID
    public function getById($id)
    {
        $sql = "SELECT p.*, c.name as category_name FROM {$this->table} p 
                LEFT JOIN categories c ON p.category_id = c.id 
                WHERE p.id = :id LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    // Thêm sản phẩm
    public function add($name, $price, $sale_price, $image, $description, $category_id, $stock)
    {
        $sql = "INSERT INTO {$this->table} (name, price, sale_price, image, description, category_id, stock) 
                VALUES (:name, :price, :sale_price, :image, :description, :category_id, :stock)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            'name' => $name,
            'price' => $price,
            'sale_price' => $sale_price,
            'image' => $image,
            'description' => $description,
            'category_id' => $category_id,
            'stock' => $stock
        ]);
    }

    // Cập nhật sản phẩm
    public function update($id, $name, $price, $sale_price, $image, $description, $category_id, $stock)
    {
        $sql = "UPDATE {$this->table} 
                SET name = :name, price = :price, sale_price = :sale_price, image = :image, 
                    description = :description, category_id = :category_id, stock = :stock 
                WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            'name' => $name,
            'price' => $price,
            'sale_price' => $sale_price,
            'image' => $image,
            'description' => $description,
            'category_id' => $category_id,
            'stock' => $stock,
            'id' => $id
        ]);
    }

    // Xóa sản phẩm
    public function delete($id)
    {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }

    // Tìm kiếm sản phẩm
    public function search($keyword)
    {
        $sql = "SELECT p.*, c.name as category_name FROM {$this->table} p 
                LEFT JOIN categories c ON p.category_id = c.id 
                WHERE p.name LIKE :keyword OR p.description LIKE :keyword 
                ORDER BY p.created_at DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['keyword' => "%$keyword%"]);
        return $stmt->fetchAll();
    }
}
