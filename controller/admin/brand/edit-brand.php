<?php
    include_once 'session.php';
    require_once 'model/admin/brandsClass.php';

    $brand = new Brands();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $brand->editBrand();
    } else {
        header('Location: /fmware/404');
        exit();
    }
?>