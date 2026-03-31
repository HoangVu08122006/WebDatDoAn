<?php

$c = $_GET['c'] ?? 'product';
$a = $_GET['a'] ?? 'list';

try {
    // Xác định controller và method
    $controllerName = match($c) {
        'auth'          => 'AuthController',
        'product'       => 'ProductController',
        'adminProduct'  => 'AdminProductController',
        'adminUser'     => 'AdminUserController',
        default         => 'HomeController'
    };

    $controller = new $controllerName();
    
    // Kiểm tra method tồn tại
    if (!method_exists($controller, $a)) {
        throw new Exception("Action '{$a}' không tồn tại trong {$controllerName}");
    }

    // Gọi method
    $controller->$a();

} catch (Exception $e) {
    // Hiển thị lỗi
    $_SESSION['error'] = $e->getMessage();
    header('Location: ?c=product&a=list');
}