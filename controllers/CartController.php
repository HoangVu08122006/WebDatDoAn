<?php

/**
 * CartController
 * Quản lý giỏ hàng – F07 (Thêm), F08 (Chỉnh sửa)
 */
class CartController
{
    private $cartModel;
    private $productModel;

    public function __construct()
    {
        $this->checkLogin();
        $this->cartModel    = new Cart();
        $this->productModel = new Product();
    }

    // Yêu cầu đăng nhập
    private function checkLogin()
    {
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = 'Vui lòng đăng nhập để sử dụng giỏ hàng';
            header('Location: ?c=auth&a=login');
            exit;
        }
    }

    // F07: Hiển thị giỏ hàng
    public function index()
    {
        $cart      = $this->cartModel->getOrCreateCart($_SESSION['user_id']);
        $cartItems = $this->cartModel->getCartItems($cart['id']);
        $total     = $this->cartModel->getTotal($cart['id']);

        return require_once PATH_VIEW . 'cart/index.php';
    }

    // F07: Thêm món vào giỏ (POST)
    public function add()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ?c=product&a=list');
            exit;
        }

        $product_id = (int) ($_POST['product_id'] ?? 0);
        $quantity   = max(1, (int) ($_POST['quantity'] ?? 1));

        if (!$product_id) {
            $_SESSION['error'] = 'Sản phẩm không hợp lệ';
            header('Location: ?c=product&a=list');
            exit;
        }

        // Kiểm tra sản phẩm tồn tại
        $product = $this->productModel->getById($product_id);
        if (!$product) {
            $_SESSION['error'] = 'Sản phẩm không tồn tại';
            header('Location: ?c=product&a=list');
            exit;
        }

        $cart = $this->cartModel->getOrCreateCart($_SESSION['user_id']);
        $this->cartModel->addItem($cart['id'], $product_id, $quantity);

        $_SESSION['success'] = "Đã thêm \"{$product['name']}\" vào giỏ hàng!";

        // Redirect về trang trước hoặc giỏ hàng
        $redirect = $_POST['redirect'] ?? '?c=cart&a=index';
        header('Location: ' . $redirect);
        exit;
    }

    // F08: Cập nhật số lượng item (POST)
    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ?c=cart&a=index');
            exit;
        }

        $item_id  = (int) ($_POST['item_id']  ?? 0);
        $quantity = (int) ($_POST['quantity'] ?? 1);

        if (!$item_id) {
            $_SESSION['error'] = 'Item không hợp lệ';
            header('Location: ?c=cart&a=index');
            exit;
        }

        $this->cartModel->updateItem($item_id, $quantity);
        header('Location: ?c=cart&a=index');
        exit;
    }

    // F08: Xóa một item khỏi giỏ (GET)
    public function remove()
    {
        $item_id = (int) ($_GET['item_id'] ?? 0);
        if (!$item_id) {
            $_SESSION['error'] = 'Item không hợp lệ';
            header('Location: ?c=cart&a=index');
            exit;
        }

        $this->cartModel->removeItem($item_id);
        $_SESSION['success'] = 'Đã xóa món khỏi giỏ hàng';
        header('Location: ?c=cart&a=index');
        exit;
    }

    // Xóa toàn bộ giỏ hàng
    public function clear()
    {
        $cart = $this->cartModel->getOrCreateCart($_SESSION['user_id']);
        $this->cartModel->clearCart($cart['id']);
        $_SESSION['success'] = 'Đã xóa toàn bộ giỏ hàng';
        header('Location: ?c=cart&a=index');
        exit;
    }
}
