<?php
    include_once 'session.php';
    require_once 'model/admin/supplierClass.php';

    $supplier = new Supplier();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $supplier->updateSupplierStatus($_POST['active'], $_POST['id']);
    } else {
        header('Location: /404');
        exit();
    }
?>