<?php
    include_once 'session.php';
    require_once 'model/admin/productClass.php';

    $products = new Products();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $products->disableProduct($_POST['active'], $_POST['id']);
    } else {
        header('Location: /404');
        exit();
    }
?>