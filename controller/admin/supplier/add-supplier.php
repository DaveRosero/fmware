<?php
    include_once 'session.php';
    require_once 'model/admin/supplierClass.php';

    $supplier = new Supplier();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $supplier->addSupplier($_POST['supplier'], $_POST['email'], $_POST['contact'], $_POST['phone'], $_POST['address']);
    } else {
        header('Location: /404');
        exit();
    }
?>