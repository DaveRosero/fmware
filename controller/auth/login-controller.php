<?php
    include_once 'session.php';
    require_once 'model/user/loginClass.php';  

    $user = new User();
    $login = new Login();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $login->login();
    } else {
        header('Location: /404');
        exit();
    }
?>