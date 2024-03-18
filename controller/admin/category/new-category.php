<?php
    include_once 'session.php';
    require_once 'model/admin/categoryClass.php';

    $category = new Category();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $category->newCategory();
    } else {
        header('Location: /fmware/404');
        exit();
    }
?>