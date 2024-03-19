<?php
    include_once 'session.php';
    require_once 'model/admin/productClass.php';

    $products = new Products();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $products->newProduct();
    } else {
        header('Location: /fmware/404');
        exit();
    }
?>