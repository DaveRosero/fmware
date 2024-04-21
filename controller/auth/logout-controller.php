<?php
    include_once 'session.php';
    require_once 'model/user/user.php';
    require_once 'model/user/logoutClass.php';

    $user = new User();
    $logout = new Logout();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $logout->logout();
    } else {
        header('Location: /404');
        exit();
    }
?>