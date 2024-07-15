<?php
    require_once 'session.php';
    require_once 'model/admin/poClass.php';
    $po = new PO();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $po->updateOthers($_POST['po_ref'], $_POST['others']);
    } else {
        header('Location: /404');
        exit();
    }
?>