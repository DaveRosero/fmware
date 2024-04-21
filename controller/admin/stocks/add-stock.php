<?php
    include_once 'session.php';
    require_once 'model/admin/stocksClass.php';

    $stocks = new Stocks();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $stocks->addStock();
    } else {
        header('Location: /404');
        exit();
    }
?>