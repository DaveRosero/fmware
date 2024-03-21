<?php
    include_once 'session.php';
    require_once 'model/home/cartClass.php';

    $cart = new Cart();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = $_POST['id'];
        $cart->resetCart($id);
    } else {
        header('Location: /fmware/404');
        exit();
    }
?>