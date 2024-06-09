<?php
    require_once 'session.php';
    require_once 'model/admin/manageClass.php';
    $manage = new Manage();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $manage->getDeliveryFee($_POST['id']);
    } else {
        header('Location: /404');
        exit();
    }
?>