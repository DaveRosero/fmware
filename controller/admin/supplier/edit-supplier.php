<?php
    include_once 'session.php';
    require_once 'model/admin/supplierClass.php';

    $supplier = new Supplier();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $supplier->editSupplier($_POST['edit-supplier'], $_POST['edit-email'], $_POST['edit-contact'], $_POST['edit-phone'], $_POST['edit-address'], $_POST['edit-id']);
    } else {
        header('Location: /404');
        exit();
    }
?>