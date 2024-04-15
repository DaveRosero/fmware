<?php
    require_once 'session.php';
    require_once 'model/admin/orderClass.php';

    $order = new Order();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $order->updatePaidStatus($_POST['order_ref'], $_POST['paid']);
    } else {
        header('Location: /fmware/404');
        exit();
    }
?>