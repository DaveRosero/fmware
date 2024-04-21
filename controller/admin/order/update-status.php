<?php
    require_once 'session.php';
    require_once 'model/admin/orderClass.php';

    $order = new Order();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $order->updateOrderStatus($_POST['order_ref'], $_POST['status']);
    } else {
        header('Location: /404');
        exit();
    }
?>