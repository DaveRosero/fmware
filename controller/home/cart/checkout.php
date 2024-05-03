<?php
    include_once 'session.php';
    require_once 'model/home/checkoutClass.php';

    $checkout = new Checkout();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $json = $checkout->placeOrder();
        echo json_encode($json);
    } else {
        header('Location: /404');
        exit();
    }
?>