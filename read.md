🎯 US01 - Đăng nhập User
✅ Tạo form đăng nhập (login.php)
✅ Xác thực email/password
✅ Lưu session người dùng và phân quyền admin/user
🎯 US02 - Xem danh sách món ăn
✅ Hiển thị tất cả sản phẩm (list.php)
✅ Lọc theo danh mục
✅ Tìm kiếm sản phẩm
✅ Xem chi tiết sản phẩm (detail.php)
🎯 US09 - Admin quản lý tài khoản
✅ Danh sách toàn bộ users (list.php)
✅ Sửa thông tin & quyền user (edit.php)
✅ Xóa user
🎯 US10 - Admin quản lý sản phẩm
✅ Danh sách sản phẩm (list.php)
✅ Thêm sản phẩm mới + upload ảnh (add.php)
✅ Sửa sản phẩm (edit.php)
✅ Xóa sản phẩm
🚀 HƯỚNG DẪN CHẠY
Cài đặt XAMPP và khởi động Apache + MySQL
Import database:
Truy cập ứng dụng:
Trang chủ: http://localhost/WebDatDoAn
Đăng nhập: http://localhost/WebDatDoAn/?c=auth&a=login
Admin: http://localhost/WebDatDoAn/?c=adminProduct&a=list
📁 CẤP TRÚC PROJECT MỚI
WebDatDoAn/
├── database.sql              (Schema database)
├── index.php                 (File chính)
├── configs/
│   ├── env.php              (Cấu hình DB - đã cập nhật)
│   └── helper.php
├── models/
│   ├── BaseModel.php
│   ├── User.php             ✅ MỚI
│   ├── Category.php         ✅ MỚI
│   └── Product.php          ✅ MỚI
├── controllers/
│   ├── HomeController.php   ✅ CẬP NHẬT
│   ├── AuthController.php   ✅ MỚI
│   ├── ProductController.php ✅ MỚI
│   ├── AdminUserController.php ✅ MỚI
│   └── AdminProductController.php ✅ MỚI
├── views/
│   ├── auth/
│   │   ├── login.php        ✅ MỚI
│   │   └── register.php     ✅ MỚI
│   ├── products/
│   │   ├── list.php         ✅ MỚI
│   │   └── detail.php       ✅ MỚI
│   └── admin/
│       ├── users/
│       │   ├── list.php     ✅ MỚI
│       │   └── edit.php     ✅ MỚI
│       └── products/
│           ├── list.php     ✅ MỚI
│           ├── add.php      ✅ MỚI
│           └── edit.php     ✅ MỚI
└── routes/
    └── index.php            ✅ CẬP NHẬT