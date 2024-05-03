<?php
    include_once 'session.php';
    require_once 'model/admin/orderClass.php';

    $order = new Order();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $qrcode = $order->receiptQrCode($_POST['order_ref']);
        echo $qrcode;
    } else {
        header('Location: /404');
        exit();
    }
?>