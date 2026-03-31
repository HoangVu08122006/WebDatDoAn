<?php

class AuthController
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    // US01: Hiển thị form đăng nhập
    public function login()
    {
        return require_once PATH_VIEW . 'auth/login.php';
    }

    // US01: Xử lý đăng nhập
    public function handleLogin()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return header('Location: ?c=auth&a=login');
        }

        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            throw new Exception('Email và password không được để trống');
        }

        $user = $this->userModel->login($email, $password);
        if (!$user) {
            throw new Exception('Email hoặc password không chính xác');
        }

        // Lưu session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_role'] = $user['role'];
        $_SESSION['user_email'] = $user['email'];

        // Redirect theo role
        if ($user['role'] === 'admin') {
            return header('Location: ?c=admin&a=dashboard');
        }
        return header('Location: ?c=product&a=list');
    }

    // Đăng ký
    public function register()
    {
        return require_once PATH_VIEW . 'auth/register.php';
    }

    // Xử lý đăng ký
    public function handleRegister()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return header('Location: ?c=auth&a=register');
        }

        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';

        if (empty($name) || empty($email) || empty($password) || empty($confirm_password)) {
            throw new Exception('Vui lòng điền đầy đủ thông tin');
        }

        if ($password !== $confirm_password) {
            throw new Exception('Mật khẩu không trùng khớp');
        }

        if ($this->userModel->register($name, $email, $password)) {
            $_SESSION['success'] = 'Đăng ký thành công! Vui lòng đăng nhập.';
            return header('Location: ?c=auth&a=login');
        }

        throw new Exception('Email đã tồn tại hoặc lỗi hệ thống');
    }

    // Đăng xuất
    public function logout()
    {
        session_destroy();
        return header('Location: ?c=product&a=list');
    }
}
