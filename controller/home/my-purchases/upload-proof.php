<?php
    include_once 'session.php';
    require_once 'model/admin/orderClass.php';

    $order = new Order();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $json = $order->uploadPayment();
        echo json_encode($json);
    } else {
        header('Location: /404');
        exit();
    }
?>