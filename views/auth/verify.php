<?php 
    include_once 'session.php'; 
    require_once 'model/user/registerClass.php';

    $register = new Register();
    $register->verifyCode($code, $_SESSION['user_id']);
?>