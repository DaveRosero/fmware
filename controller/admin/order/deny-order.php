<?php
require_once 'session.php';
require_once 'model/admin/orderClass.php';

$order = new Order();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order->denyOrder($_POST['order_ref']);
} else {
    header('Location: /404');
    exit();
}
