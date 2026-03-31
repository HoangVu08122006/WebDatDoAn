<?php

class AdminProductController
{
    private $productModel;
    private $categoryModel;

    public function __construct()
    {
        $this->checkAdmin();
        $this->productModel = new Product();
        $this->categoryModel = new Category();
    }

    // Kiểm tra quyền Admin
    private function checkAdmin()
    {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            header('Location: ?c=auth&a=login');
            die;
        }
    }

    // US10: Danh sách sản phẩm
    public function list()
    {
        $products = $this->productModel->getAll();
        return require_once PATH_VIEW . 'admin/products/list.php';
    }

    // US10: Thêm sản phẩm
    public function add()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $price = $_POST['price'] ?? 0;
            $sale_price = !empty($_POST['sale_price']) ? $_POST['sale_price'] : NULL;
            $description = $_POST['description'] ?? '';
            $category_id = $_POST['category_id'] ?? null;
            $stock = $_POST['stock'] ?? 0;
            $image = '';

            if (empty($name) || empty($price) || !$category_id) {
                $_SESSION['error'] = 'Vui lòng điền đầy đủ thông tin bắt buộc';
                header('Location: ?c=adminProduct&a=add');
                exit;
            }

            // Upload ảnh
            if (isset($_FILES['image']) && $_FILES['image']['tmp_name']) {
                try {
                    $image = upload_file('products', $_FILES['image']);
                } catch (Exception $e) {
                    $_SESSION['error'] = 'Upload ảnh thất bại: ' . $e->getMessage();
                    header('Location: ?c=adminProduct&a=add');
                    exit;
                }
            }

            if ($this->productModel->add($name, $price, $sale_price, $image, $description, $category_id, $stock)) {
                $_SESSION['success'] = 'Thêm sản phẩm thành công!';
                header('Location: ?c=adminProduct&a=list');
                exit;
            }

            $_SESSION['error'] = 'Thêm sản phẩm thất bại';
            header('Location: ?c=adminProduct&a=add');
            exit;
        }

        $categories = $this->categoryModel->getAll();
        return require_once PATH_VIEW . 'admin/products/add.php';
    }

    // US10: Sửa sản phẩm
    public function edit()
    {
        $id = $_GET['id'] ?? null;

        if ($id && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $price = $_POST['price'] ?? 0;
            $sale_price = !empty($_POST['sale_price']) ? $_POST['sale_price'] : NULL;
            $description = $_POST['description'] ?? '';
            $category_id = $_POST['category_id'] ?? null;
            $stock = $_POST['stock'] ?? 0;

            if (empty($name) || empty($price) || !$category_id) {
                $_SESSION['error'] = 'Vui lòng điền đầy đủ thông tin bắt buộc';
                header('Location: ?c=adminProduct&a=edit&id=' . $id);
                exit;
            }

            $product = $this->productModel->getById($id);
            if (!$product) {
                $_SESSION['error'] = 'Sản phẩm không tồn tại';
                header('Location: ?c=adminProduct&a=list');
                exit;
            }

            $image = $product['image'];

            // Upload ảnh mới
            if (isset($_FILES['image']) && $_FILES['image']['tmp_name']) {
                try {
                    $image = upload_file('products', $_FILES['image']);
                } catch (Exception $e) {
                    $_SESSION['error'] = 'Upload ảnh thất bại: ' . $e->getMessage();
                    header('Location: ?c=adminProduct&a=edit&id=' . $id);
                    exit;
                }
            }

            if ($this->productModel->update($id, $name, $price, $sale_price, $image, $description, $category_id, $stock)) {
                $_SESSION['success'] = 'Cập nhật sản phẩm thành công!';
                header('Location: ?c=adminProduct&a=list');
                exit;
            }

            $_SESSION['error'] = 'Cập nhật sản phẩm thất bại';
            header('Location: ?c=adminProduct&a=edit&id=' . $id);
            $_SESSION['error'] = 'ID không hợp lệ';
            header('Location: ?c=adminProduct&a=list');
            exit;
        }

        if ($this->productModel->delete($id)) {
            $_SESSION['success'] = 'Xóa sản phẩm thành công!';
        } else {
            $_SESSION['error'] = 'Xóa sản phẩm thất bại';
        }

        header('Location: ?c=adminProduct&a=list');
        exit
            $_SESSION['error'] = 'Sản phẩm không tồn tại';
            header('Location: ?c=adminProduct&a=list');
            exit;
        }

        $categories = $this->categoryModel->getAll();
        return require_once PATH_VIEW . 'admin/products/edit.php';
    }

    // US10: Xóa sản phẩm
    public function delete()
    {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            throw new Exception('ID không hợp lệ');
        }

        if ($this->productModel->delete($id)) {
            $_SESSION['success'] = 'Xóa sản phẩm thành công!';
        } else {
            throw new Exception('Xóa sản phẩm thất bại');
        }

        return header('Location: ?c=adminProduct&a=list');
    }
}
