<?php
    include_once 'session.php';
    require_once 'model/home/cartClass.php';

    $cart = new Cart();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $cart->addQty();
    } else {
        header('Location: /404');
        exit();
    }
?>