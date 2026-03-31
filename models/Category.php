<?php

class Category extends BaseModel
{
    protected $table = 'categories';

    // Lấy tất cả danh mục
    public function getAll()
    {
        $sql = "SELECT * FROM {$this->table} ORDER BY created_at DESC";
        return $this->conn->query($sql)->fetchAll();
    }

    // Lấy category theo ID
    public function getById($id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    // Thêm category
    public function add($name, $description)
    {
        $sql = "INSERT INTO {$this->table} (name, description) VALUES (:name, :description)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute(['name' => $name, 'description' => $description]);
    }

    // Cập nhật category
    public function update($id, $name, $description)
    {
        $sql = "UPDATE {$this->table} SET name = :name, description = :description WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute(['name' => $name, 'description' => $description, 'id' => $id]);
    }

    // Xóa category
    public function delete($id)
    {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }
}
