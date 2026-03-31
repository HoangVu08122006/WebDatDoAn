<?php 

session_start();

spl_autoload_register(function ($class) {    
    $fileName = "$class.php";

    $fileModel              = PATH_MODEL . $fileName;
    $fileController         = PATH_CONTROLLER . $fileName;

    if (is_readable($fileModel)) {
        require_once $fileModel;
    } 
    else if (is_readable($fileController)) {
        require_once $fileController;
    }
});

require_once './configs/env.php';
require_once './configs/helper.php';

// Điều hướng
try {
    require_once './routes/index.php';
} catch (Exception $e) {
    $_SESSION['error'] = $e->getMessage();
    // Log error nếu cần
    error_log($e->getMessage());
    header('Location: ?c=product&a=list');
}
