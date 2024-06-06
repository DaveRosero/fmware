<?php
    require_once 'session.php';
    require_once 'model/admin/manageClass.php';
    $manage = new Manage();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $manage->addExpenses($_POST['description'], $_POST['amount'], $_SESSION['user_id']);
    } else {
        header('Location: /404');
        exit();
    }
?>