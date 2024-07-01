<?php
    require_once 'session.php';
    require_once 'model/admin/poClass.php';
    $po = new PO();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $po->redirect($_POST['supplier']);
    } else {
        header('Location: /404');
        exit();
    }
?>