<?php
    include_once 'session.php';
    require_once 'model/admin/orderClass.php';

    $order = new Order();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $details = $order->getOrderDetails($_POST['order_ref']);
        echo $details;
    } else {
        header('Location: /404');
        exit();
    }
?>