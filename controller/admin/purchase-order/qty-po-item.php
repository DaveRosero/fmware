<?php
    require_once 'session.php';
    require_once 'model/admin/poClass.php';
    $po = new PO();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $po->updateQty($_POST['po_ref'], $_POST['id'], $_POST['qty']);
    } else {
        header('Location: /404');
        exit();
    }
?>