<?php
    include_once 'session.php';
    require_once 'model/admin/productClass.php';

    $products = new Products();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $products->getBasePrices($_POST['product_id']);
    } else {
        header('Location: /404');
        exit();
    }
?>