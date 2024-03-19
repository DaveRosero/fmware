<?php
    include_once 'session.php';
    require_once 'model/admin/pricelistClass.php';

    $price = new PriceList();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $price->newPrice();
    } else {
        echo '1';
    }
?>