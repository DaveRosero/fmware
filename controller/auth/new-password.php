<?php
    include_once 'session.php';
    require_once 'model/user/loginClass.php';

    $login = new Login();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $login->resetPassword($_POST['email'], $_POST['password'], $_POST['confirm']);
    } else {
        header('Location: /404');
        exit();
    }
?>