<?php
    include_once 'session.php';
    require_once 'model/home/cartClass.php';

    $cart = new Cart();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $fee = $cart->getDeliveryFee();
        echo json_encode($fee);
    } else {
        header('Location: /fmware/404');
        exit();
    }
?>