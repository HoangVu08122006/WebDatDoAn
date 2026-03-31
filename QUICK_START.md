# ⚡ QUICK START - Bắt đầu nhanh

## 5 Bước Setup (5 phút)

### **Bước 1: Cài XAMPP**
- Tải: https://www.apachefriends.org/
- Cài đặt mặc định, nhớ chọn Apache + MySQL

### **Bước 2: Copy project**
```
C:\xampp\htdocs\WebDatDoAn\  (copy thư mục project vào đây)
```

### **Bước 3: Start XAMPP**
- Mở XAMPP Control Panel
- Click "Start" cho Apache & MySQL
- Chờ cho đến khi "Running" (xanh lá)

### **Bước 4: Setup Database**
- **Cách 1 (Khuyên dùng - 1 click)**:
  ```
  http://localhost/WebDatDoAn/setup.php
  → Click "Setup Database"
  → OK!
  ```

- **Cách 2 (Thủ công)**:
  1. Vào `http://localhost/phpmyadmin`
  2. Tạo database mới tên `shop_db`
  3. Import file `database.sql` (SQL tab → Chọn file → Execute)

### **Bước 5: Chạy ứng dụng**
```
http://localhost/WebDatDoAn
```

---

## 🔑 Tài khoản Admin Mặc định

| Field | Value |
|-------|-------|
| Email | `admin@example.com` |
| Password | `123456` |

⚠️ **Hãy đổi mật khẩu sau khi đăng nhập!**

---

## 🗺️ Điều hướng Nhanh

### Trang User
- Trang chủ: `http://localhost/WebDatDoAn`
- Đăng nhập: `http://localhost/WebDatDoAn/?c=auth&a=login`
- Đăng ký: `http://localhost/WebDatDoAn/?c=auth&a=register`

### Trang Admin
- Quản lý sản phẩm: `http://localhost/WebDatDoAn/?c=adminProduct&a=list`
- Quản lý tài khoản: `http://localhost/WebDatDoAn/?c=adminUser&a=list`

---

## ❌ Troubleshooting Nhanh

| Lỗi | Giải pháp |
|-----|----------|
| "Kết nối CSDL thất bại" | Kiểm tra MySQL có start không, DB setup đúng chưa |
| Blank page | Kiểm tra error log, PHP warnings |
| Upload ảnh fail | Set quyền folder: `assets/uploads/` → 777 |
| Button "Setup" không hoạt động | Tạo database `shop_db` rỗng trước, rồi setup lại |

---

## 📞 Cần giúp?

1. Kiểm tra `README.md` chi tiết
2. Xem lại các bước setup
3. Check XAMPP logs (Windows → show details)
4. Clear browser cache (Ctrl+Shift+Delete)

---

**Xong! Bạn đã sẵn sàng 🎉**
