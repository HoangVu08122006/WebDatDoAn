<?php

if (!function_exists('debug')) {
    function debug($data)
    {
        echo '<pre>';
        print_r($data);
        die;
    }
}

if (!function_exists('upload_file')) {
    function upload_file($folder, $file)
    {
        $targetDir = PATH_ASSETS_UPLOADS . $folder;
        // Kiểm tra và tạo thư mục nếu chưa tồn tại
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true); // Tạo thư mục, true = tạo cả cha nếu thiếu
        }
        // Đặt tên file
        $targetFile = $folder . '/' . time() . '-' . $file["name"];
        $fullPath = PATH_ASSETS_UPLOADS . $targetFile;
        
        if (move_uploaded_file($file["tmp_name"], $fullPath)) {
            return $targetFile; // Trả về đường dẫn lưu DB (tương đối so với assets/uploads)
        }

        throw new Exception('Upload file không thành công!');
    }
}
