<?php
    include_once 'session.php';
    require_once 'model/admin/supplierClass.php';

    $supplier = new Supplier();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $supplier->adddSupplier($_POST['supplier'], $_POST['email'], $_POST['phone'], $_POST['address']);
    } else {
        header('Location: /404');
        exit();
    }
?>