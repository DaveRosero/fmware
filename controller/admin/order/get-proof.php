<?php
    require_once 'session.php';
    require_once 'model/admin/orderClass.php';

    $order = new Order();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $proof = $order->getProofCOD($_POST['order_ref']);
        echo $proof;
    } else {
        header('Location: /404');
        exit();
    }
?>