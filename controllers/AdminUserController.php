<?php

class AdminUserController
{
    private $userModel;

    public function __construct()
    {
        $this->checkAdmin();
        $this->userModel = new User();
    }

    // Kiểm tra quyền Admin
    private function checkAdmin()
    {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            header('Location: ?c=auth&a=login');
            die;
        }
    }

    // US09: Danh sách users
    public function list()
    {
        $users = $this->userModel->getAll();
        return require_once PATH_VIEW . 'admin/users/list.php';
    }

    // US09: Chi tiết user
    public function detail()
    {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: ?c=adminUser&a=list');
            exit;
        }

        $user = $this->userModel->getById($id);
        if (!$user) {
            $_SESSION['error'] = 'Người dùng không tồn tại';
            header('Location: ?c=adminUser&a=list');
            exit;
        }

        return require_once PATH_VIEW . 'admin/users/detail.php';
    }

    // US09: Sửa user
    public function edit()
    {
        $id = $_GET['id'] ?? null;
        if ($id && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $email = $_POST['email'] ?? '';
            $role = $_POST['role'] ?? 'user';

            if (empty($name) || empty($email)) {
                $_SESSION['error'] = 'Tên và email không được để trống';
                header('Location: ?c=adminUser&a=edit&id=' . $id);
                exit;
            }

            $this->userModel->update($id, [
                'name' => $name,
                'email' => $email,
                'role' => $role
            ]);

            $_SESSION['success'] = 'Cập nhật user thành công!';
            header('Location: ?c=adminUser&a=list');
            exit;
        }

        if (!$id) {
            header('Location: ?c=adminUser&a=list');
            exit;
        }

        $user = $this->userModel->getById($id);
        if (!$user) {
            $_SESSION['error'] = 'Người dùng không tồn tại';
            header('Location: ?c=adminUser&a=list');
            exit;
        }

        return require_once PATH_VIEW . 'admin/users/edit.php';
    }

    // US09: Xóa user
    public function delete()
    {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            $_SESSION['error'] = 'ID không hợp lệ';
            header('Location: ?c=adminUser&a=list');
            exit;
        }

        if ($this->userModel->delete($id)) {
            $_SESSION['success'] = 'Xóa user thành công!';
        } else {
            $_SESSION['error'] = 'Xóa user thất bại';
        }

        header('Location: ?c=adminUser&a=list');
        exit;
    }
}
