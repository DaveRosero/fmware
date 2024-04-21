<?php
    include_once 'session.php';
    require_once 'model/user/registerClass.php';

    $register = new Register();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $register->register();
    } else {
        header('Location: /404');
        exit();
    }
?>