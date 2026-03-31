<?php

class HomeController
{
    public function index() 
    {
        // Redirect đến trang danh sách sản phẩm
        return header('Location: ?c=product&a=list');
    }
}