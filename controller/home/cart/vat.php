<?php
    include_once 'session.php';
    require_once 'model/home/cartClass.php';

    $cart = new Cart();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $fee = $cart->getTax();
        echo json_encode($fee);
    } else {
        header('Location: /404');
        exit();
    }
?>