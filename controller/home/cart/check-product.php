<?php
    include_once 'session.php';
    require_once 'model/home/cartClass.php';

    $cart = new Cart();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $json = $cart->checkProduct($_POST['user_id'], $_POST['product_id']);
        echo json_encode($json);
    } else {
        header('Location: /fmware/404');
        exit();
    }
?>