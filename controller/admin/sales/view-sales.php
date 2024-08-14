<?php
    require_once 'session.php';
    require_once 'model/admin/salesClass.php';

    $sales = new Sales();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $sales->viewSales($_POST['pos_ref']);
    } else {
        header('Location: /404');
        exit();
    }
?>