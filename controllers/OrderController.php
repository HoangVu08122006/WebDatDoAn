<?php

/**
 * OrderController
 * F09: Đặt món, F10: Địa điểm nhận, F13: Lịch sử đơn hàng
 */
class OrderController
{
    private $orderModel;
    private $cartModel;

    public function __construct()
    {
        $this->checkLogin();
        $this->orderModel = new Order();
        $this->cartModel  = new Cart();
    }

    // Yêu cầu đăng nhập
    private function checkLogin()
    {
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = 'Vui lòng đăng nhập để đặt hàng';
            header('Location: ?c=auth&a=login');
            exit;
        }
    }

    // F09 + F10: Trang xác nhận đặt hàng – chọn địa điểm nhận
    public function checkout()
    {
        $cart      = $this->cartModel->getOrCreateCart($_SESSION['user_id']);
        $cartItems = $this->cartModel->getCartItems($cart['id']);
        $total     = $this->cartModel->getTotal($cart['id']);

        if (empty($cartItems)) {
            $_SESSION['error'] = 'Giỏ hàng của bạn đang trống, hãy thêm món trước!';
            header('Location: ?c=cart&a=index');
            exit;
        }

        // Danh sách địa điểm nhận hàng
        $locations = [
            'Tòa A – Tầng 1', 'Tòa A – Tầng 2', 'Tòa A – Tầng 3',
            'Tòa B – Tầng 1', 'Tòa B – Tầng 2', 'Tòa B – Tầng 3',
            'Tòa C – Tầng 1', 'Tòa C – Tầng 2', 'Tòa C – Tầng 3',
            'Nhà xe',          'Sân trường',
        ];

        return require_once PATH_VIEW . 'order/checkout.php';
    }

    // F09: Xử lý đặt hàng (POST)
    public function placeOrder()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ?c=order&a=checkout');
            exit;
        }

        $delivery_location = trim($_POST['delivery_location'] ?? '');
        if (empty($delivery_location)) {
            $_SESSION['error'] = 'Vui lòng chọn địa điểm nhận hàng';
            header('Location: ?c=order&a=checkout');
            exit;
        }

        $cart      = $this->cartModel->getOrCreateCart($_SESSION['user_id']);
        $cartItems = $this->cartModel->getCartItems($cart['id']);
        $total     = $this->cartModel->getTotal($cart['id']);

        if (empty($cartItems)) {
            $_SESSION['error'] = 'Giỏ hàng trống, không thể đặt hàng';
            header('Location: ?c=cart&a=index');
            exit;
        }

        // Tạo đơn hàng
        $order_id = $this->orderModel->createOrder(
            $_SESSION['user_id'],
            $total,
            $delivery_location
        );

        if (!$order_id) {
            $_SESSION['error'] = 'Đặt hàng thất bại, vui lòng thử lại';
            header('Location: ?c=order&a=checkout');
            exit;
        }

        // Lưu từng item vào order_items
        foreach ($cartItems as $item) {
            $unitPrice = $item['sale_price'] ?? $item['price'];
            $this->orderModel->addOrderItem($order_id, $item['product_id'], $item['quantity'], $unitPrice);
        }

        // Xóa giỏ hàng sau khi đặt thành công
        $this->cartModel->clearCart($cart['id']);

        $_SESSION['success'] = "Đặt hàng thành công! Mã đơn: #{$order_id}";
        header('Location: ?c=order&a=history');
        exit;
    }

    // F13: Xem lịch sử đơn hàng của sinh viên
    public function history()
    {
        $orders = $this->orderModel->getByUser($_SESSION['user_id']);
        return require_once PATH_VIEW . 'order/history.php';
    }

    // Xem chi tiết một đơn hàng
    public function detail()
    {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: ?c=order&a=history');
            exit;
        }

        $order = $this->orderModel->getById($id);
        if (!$order || $order['user_id'] != $_SESSION['user_id']) {
            $_SESSION['error'] = 'Đơn hàng không tồn tại';
            header('Location: ?c=order&a=history');
            exit;
        }

        $orderItems = $this->orderModel->getOrderItems($id);
        return require_once PATH_VIEW . 'order/detail.php';
    }
}
