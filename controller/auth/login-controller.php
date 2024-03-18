<?php
    include_once 'session.php';
    require_once 'model/user/loginClass.php';  

    $user = new User();
    $login = new Login();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if ($_POST['action'] == 'login') {
            $login->login();
        }
    } else {
        header('Location: /fmware/404');
        exit();
    }
?>