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
    // sdghgsghghdfsdf
    // US01: Xử lý đăng nhập
    public function handleLogin()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ?c=auth&a=login');
            exit;
        }

        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            $_SESSION['error'] = 'Email và password không được để trống';
            header('Location: ?c=auth&a=login');
            exit;
        }

        $user = $this->userModel->login($email, $password);
        if (!$user) {
            $_SESSION['error'] = 'Email hoặc password không chính xác';
            header('Location: ?c=auth&a=login');
            exit;
        }

        // Lưu session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_role'] = $user['role'];
        $_SESSION['user_email'] = $user['email'];

        // Redirect theo role
        if ($user['role'] === 'admin') {
            header('Location: ?c=adminProduct&a=list');
        } else {
            header('Location: ?c=product&a=list');
        }
        exit;
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
            header('Location: ?c=auth&a=register');
            exit;
        }

        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';

        if (empty($name) || empty($email) || empty($password) || empty($confirm_password)) {
            $_SESSION['error'] = 'Vui lòng điền đầy đủ thông tin';
            header('Location: ?c=auth&a=register');
            exit;
        }

        if ($password !== $confirm_password) {
            $_SESSION['error'] = 'Mật khẩu không trùng khớp';
            header('Location: ?c=auth&a=register');
            exit;
        }

        if ($this->userModel->register($name, $email, $password)) {
            $_SESSION['success'] = 'Đăng ký thành công! Vui lòng đăng nhập.';
            header('Location: ?c=auth&a=login');
            exit;
        }

        $_SESSION['error'] = 'Email đã tồn tại hoặc lỗi hệ thống';
        header('Location: ?c=product&a=list');
        exit;
    }
    // xin chao
    // Đăng xuất
    public function logout()
    {
        session_destroy();
        return header('Location: ?c=product&a=list');
    }
}
