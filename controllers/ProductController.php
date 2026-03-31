<?php

class ProductController
{
    private $productModel;
    private $categoryModel;

    public function __construct()
    {
        $this->productModel = new Product();
        $this->categoryModel = new Category();
    }

    // US02: Hiển thị danh sách sản phẩm
    public function list()
    {
        $products = $this->productModel->getAll();
        $categories = $this->categoryModel->getAll();
        
        return require_once PATH_VIEW . 'products/list.php';
    }

    // US02: Chi tiết sản phẩm
    public function detail()
    {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            return header('Location: ?c=product&a=list');
        }

        $product = $this->productModel->getById($id);
        if (!$product) {
            throw new Exception('Sản phẩm không tồn tại');
        }

        return require_once PATH_VIEW . 'products/detail.php';
    }

    // Lọc sản phẩm theo danh mục
    public function filterByCategory()
    {
        $category_id = $_GET['category_id'] ?? null;
        if (!$category_id) {
            return header('Location: ?c=product&a=list');
        }

        $products = $this->productModel->getByCategory($category_id);
        $categories = $this->categoryModel->getAll();
        $currentCategory = $this->categoryModel->getById($category_id);

        return require_once PATH_VIEW . 'products/list.php';
    }

    // Tìm kiếm sản phẩm
    public function search()
    {
        $keyword = $_GET['q'] ?? '';
        $products = [];
        if ($keyword) {
            $products = $this->productModel->search($keyword);
        }
        $categories = $this->categoryModel->getAll();

        return require_once PATH_VIEW . 'products/list.php';
    }
}
