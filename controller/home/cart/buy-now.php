<?php
include_once 'session.php';
require_once 'model/home/cartClass.php';

$cart = new Cart();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cart->buyNow($_POST['product_id']);
} else {
    header('Location: /404');
    exit();
}
