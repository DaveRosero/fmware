<?php
    require_once 'session.php';
    require_once 'model/admin/poClass.php';
    $po = new PO();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $po->updateNewSRP($_POST['po_ref'], $_POST['product_id'], $_POST['selling_price']);
    } else {
        header('Location: /404');
        exit();
    }
?>