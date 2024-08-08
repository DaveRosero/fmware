<?php
    include_once 'session.php';
    require_once 'model/home/cartClass.php';

    $cart = new Cart();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $cart->updateQty($_POST['id'], $_POST['qty']);
    } else {
        header('Location: /404');
        exit();
    }
?>