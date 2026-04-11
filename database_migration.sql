-- Migration: Thêm cột delivery_location vào bảng orders
-- Chạy script này nếu bảng orders đã tồn tại (không cần xóa lại DB)
ALTER TABLE orders
    ADD COLUMN delivery_location VARCHAR(255) NULL AFTER total_price;
