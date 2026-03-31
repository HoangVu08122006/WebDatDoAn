# 🍔 WEB ĐẶT ĐỒ ĂN - SHOP MANAGEMENT SYSTEM

Ứng dụng web quản lý đặt đồ ăn với chức năng đầy đủ cho cả User và Admin.

## ✨ Tính năng

### 👤 User
- ✅ Đăng ký / Đăng nhập
- ✅ Xem danh sách sản phẩm
- ✅ Tìm kiếm và lọc sản phẩm theo danh mục
- ✅ Xem chi tiết sản phẩm

### 👨‍💼 Admin
- ✅ Quản lý tài khoản người dùng (xem, sửa, xóa)
- ✅ Quản lý sản phẩm (thêm, sửa, xóa)
- ✅ Upload hình ảnh sản phẩm
- ✅ Quản lý danh mục

---

## 🛠️ Yêu cầu hệ thống

- **PHP**: 7.4+
- **MySQL**: 5.7+
- **Apache**: Hỗ trợ .htaccess
- **Trình duyệt**: Modern browser (Chrome, Firefox, Safari, Edge)

---

## 📦 Cài đặt

### 1. Tải XAMPP
- Tải từ: https://www.apachefriends.org/
- Cài đặt và chọn Apache + MySQL + PHP

### 2. Clone/Tải project
```bash
# Copy thư mục WebDatDoAn vào C:\xampp\htdocs\
# Hoặc clone từ Git:
git clone <repo-url> C:\xampp\htdocs\WebDatDoAn
cd WebDatDoAn
```

### 3. Cấu hình Database
Mở `configs/env.php` và cập nhật:
```php
define('DB_HOST',     'localhost');
define('DB_PORT',     '3306');
define('DB_USERNAME', 'root');    // Mặc định XAMPP là 'root'
define('DB_PASSWORD', '');        // Mặc định XAMPP trống
define('DB_NAME',     'shop_db');
```

### 4. Setup Database
- Mở XAMPP Control Panel → Start Apache & MySQL
- **Cách dễ nhất**: Truy cập `http://localhost/WebDatDoAn/setup.php` → Click "Setup Database"

Hoặc **thủ công**:
1. Mở phpMyAdmin: `http://localhost/phpmyadmin`
2. Tạo database `shop_db`
3. Import file `database.sql`

---

## 🚀 Chạy ứng dụng

1. **Khởi động XAMPP**
   - Mở XAMPP Control Panel
   - Click "Start" cho Apache & MySQL

2. **Truy cập ứng dụng**
   - Trang chủ: `http://localhost/WebDatDoAn`
   - Hoặc trực tiếp: `http://localhost/WebDatDoAn/?c=product&a=list`

3. **Đăng nhập Admin** (sau setup.php)
   - Email: `admin@example.com`
   - Password: `123456`

---

## 📱 URL Routing

### Public Pages
| URL | Mô tả |
|-----|-------|
| `?c=product&a=list` | Danh sách sản phẩm |
| `?c=product&a=detail&id=1` | Chi tiết sản phẩm |
| `?c=product&a=search&q=cơm` | Tìm kiếm |
| `?c=product&a=filterByCategory&category_id=1` | Lọc theo danh mục |

### Authentication
| URL | Mô tả |
|-----|-------|
| `?c=auth&a=login` | Trang đăng nhập |
| `?c=auth&a=register` | Trang đăng ký |
| `?c=auth&a=logout` | Đăng xuất |

### Admin Pages (Cần đăng nhập Admin)
| URL | Mô tả |
|-----|-------|
| `?c=adminProduct&a=list` | Danh sách sản phẩm |
| `?c=adminProduct&a=add` | Thêm sản phẩm |
| `?c=adminProduct&a=edit&id=1` | Sửa sản phẩm |
| `?c=adminProduct&a=delete&id=1` | Xóa sản phẩm |
| `?c=adminUser&a=list` | Danh sách người dùng |
| `?c=adminUser&a=edit&id=1` | Sửa người dùng |
| `?c=adminUser&a=delete&id=1` | Xóa người dùng |

---

## 📁 Cấu trúc thư mục

```
WebDatDoAn/
├── index.php                    # File chính (entry point)
├── setup.php                    # Setup database
├── database.sql                 # Schema database
├── .gitignore
├── README.md
├── configs/
│   ├── env.php                 # Cấu hình database
│   └── helper.php              # Helper functions
├── models/
│   ├── BaseModel.php           # Base class cho tất cả models
│   ├── User.php                # User model
│   ├── Product.php             # Product model
│   └── Category.php            # Category model
├── controllers/
│   ├── HomeController.php      # Home controller
│   ├── AuthController.php      # Auth controller (login/register)
│   ├── ProductController.php   # Product controller
│   ├── AdminProductController.php  # Admin product controller
│   └── AdminUserController.php # Admin user controller
├── views/
│   ├── auth/
│   │   ├── login.php          # Login page
│   │   └── register.php       # Register page
│   ├── products/
│   │   ├── list.php           # Products list
│   │   └── detail.php         # Product detail
│   ├── admin/
│   │   ├── users/
│   │   │   ├── list.php       # Users list
│   │   │   └── edit.php       # Edit user
│   │   └── products/
│   │       ├── list.php       # Products list (admin)
│   │       ├── add.php        # Add product
│   │       └── edit.php       # Edit product
│   └── main.php               # Main layout
├── routes/
│   └── index.php              # Router
└── assets/
    └── uploads/               # Folder lưu ảnh
```

---

## 🔐 Bảo mật

### Điều cần làm ngay
1. ✅ Đổi mật khẩu admin mặc định
2. ✅ Cập nhật `configs/env.php` với thực tế
3. ✅ Đặt quyền thư mục `assets/uploads/` thành `777`

### Các biện pháp bảo mật hiện có
- ✅ Password hashed với `password_hash()` + BCRYPT
- ✅ Session-based authentication
- ✅ Admin permission check
- ✅ SQL injection prevention (Prepared Statements)
- ✅ File upload validation

---

## 🐛 Troubleshooting

### Lỗi: "Kết nối cơ sở dữ liệu thất bại"
- [ ] Kiểm tra XAMPP MySQL đã start?
- [ ] Kiểm tra `configs/env.php` cấu hình?
- [ ] Kiểm tra database `shop_db` đã tồn tại?

### Lỗi: "Class not found"
- [ ] Kiểm tra file naming có đúng không?
- [ ] Kiểm tra autoload đúng không?

### Lỗi Upload ảnh
- [ ] Kiểm tra `assets/uploads/` có tồn tại không?
- [ ] Set quyền folder: `chmod 777 assets/uploads/`

### URL redirect không hoạt động
- [ ] Clear browser cache (Ctrl+Shift+Delete)
- [ ] Kiểm tra session_start() ở đầu index.php?

---

## 📝 Hướng dẫn sử dụng

### Với User
1. Truy cập trang chủ
2. Click "Đăng ký" để tạo tài khoản
3. Xem danh sách sản phẩm
4. Tìm kiếm hoặc lọc theo danh mục
5. Click sản phẩm để xem chi tiết

### Với Admin
1. Đăng nhập admin account
2. Vào "Admin Panel"
3. Quản lý sản phẩm: Thêm, sửa, xóa
4. Quản lý tài khoản: Xem, sửa, xóa

---

## 📞 Liên hệ & Support

Nếu gặp vấn đề, vui lòng kiểm tra:
- [ ] Tất cả file có đủ không?
- [ ] Database setup đúng không?
- [ ] PHP version hợp lệ không?
- [ ] Permissions folder đúng không?

---

## 📄 License

MIT License - Tự do sử dụng cho mục đích học tập

---

**Phiên bản**: 1.0.0  
**Cập nhật lần cuối**: 31/03/2026
