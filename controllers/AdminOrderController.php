<?php

/**
 * AdminOrderController
 * F11: Cập nhật trạng thái đơn hàng
 * F12: Xem danh sách đơn hàng (Admin)
 */
class AdminOrderController
{
    private $orderModel;

    public function __construct()
    {
        $this->checkAdmin();
        $this->orderModel = new Order();
    }

    // Kiểm tra quyền Admin
    private function checkAdmin()
    {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            header('Location: ?c=auth&a=login');
            die;
        }
    }

    // F12: Danh sách tất cả đơn hàng
    public function list()
    {
        $status = $_GET['status'] ?? '';
        $orders = $this->orderModel->getAll();

        // Lọc theo trạng thái nếu có
        if ($status) {
            $orders = array_filter($orders, fn($o) => $o['status'] === $status);
        }

        return require_once PATH_VIEW . 'admin/orders/list.php';
    }

    // Chi tiết một đơn hàng
    public function detail()
    {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            $_SESSION['error'] = 'ID không hợp lệ';
            header('Location: ?c=adminOrder&a=list');
            exit;
        }

        $order = $this->orderModel->getById($id);
        if (!$order) {
            $_SESSION['error'] = 'Đơn hàng không tồn tại';
            header('Location: ?c=adminOrder&a=list');
            exit;
        }

        $orderItems = $this->orderModel->getOrderItems($id);
        return require_once PATH_VIEW . 'admin/orders/detail.php';
    }

    // F11: Admin cập nhật trạng thái đơn hàng (POST)
    public function updateStatus()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ?c=adminOrder&a=list');
            exit;
        }

        $id     = (int) ($_POST['order_id'] ?? 0);
        $status = $_POST['status'] ?? '';

        if (!$id || !$status) {
            $_SESSION['error'] = 'Dữ liệu không hợp lệ';
            header('Location: ?c=adminOrder&a=list');
            exit;
        }

        if ($this->orderModel->updateStatus($id, $status)) {
            $_SESSION['success'] = "Cập nhật trạng thái đơn #$id thành công!";
        } else {
            $_SESSION['error'] = 'Cập nhật thất bại hoặc trạng thái không hợp lệ';
        }

        header('Location: ?c=adminOrder&a=detail&id=' . $id);
        exit;
    }
}
