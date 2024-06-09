<?php
    require_once 'session.php';
    require_once 'model/admin/manageClass.php';
    $manage = new Manage();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $manage->updateDeliveryFee($_POST['df'], $_POST['municipal']);
    } else {
        header('Location: /404');
        exit();
    }
?>