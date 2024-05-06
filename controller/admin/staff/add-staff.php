<?php
    include_once 'session.php';
    require_once 'model/admin/staffClass.php';

    $staff = new Staff();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $json = $staff->regStaff();
        echo $json;
    } else {
        header('Location: /404');
        exit();
    }
?>