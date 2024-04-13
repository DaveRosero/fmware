<?php
    include_once 'session.php';
    require_once 'model/home/cartClass.php';

    $cart = new Cart();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = $_POST['id'];
        if (isset($_POST['delivery_fee'])) {
            $delivery_fee = $_POST['delivery_fee'];
        } else {
            $delivery_fee = 0;
        }
        $total = $cart->getCartTotal($id, $delivery_fee);
        echo json_encode($total);
    } else {
        header('Location: /fmware/404');
        exit();
    }
?>