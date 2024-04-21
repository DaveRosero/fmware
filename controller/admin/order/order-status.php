<?php
    require_once 'session.php';
    require_once 'model/admin/orderClass.php';

    $order = new Order();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $json = $order->orderStatus($_POST['order_ref']);
        echo json_encode($json);
    } else {
        header('Location: /404');
        exit();
    }
?>