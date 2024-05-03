<?php 
    include_once 'session.php'; 
    require_once 'model/user/registerClass.php';

    $register = new Register();
    $register->verifyCode($code, $email);
    header('Location: /verify-success');
?>