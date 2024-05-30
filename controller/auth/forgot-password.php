<?php
    include_once 'session.php';
    require_once 'model/user/loginClass.php';

    $login = new Login();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $login->forgotPassword($_POST['email']);
    } else {
        header('Location: /404');
        exit();
    }
?>