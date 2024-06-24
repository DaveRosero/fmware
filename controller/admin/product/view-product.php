<?php
    include_once 'session.php';
    require_once 'model/admin/productClass.php';

    $products = new Products();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $json = $products->viewProduct($_POST['id']);
        echo json_encode($json);
    } else {
        header('Location: /404');
        exit();
    }
?>