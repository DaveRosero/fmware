<?php
    include_once 'session.php';
    require_once 'model/admin/userListClass.php';

    $user = new UserList();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $json = $user->newStaff();
        echo $json;
    } else {
        header('Location: /404');
        exit();
    }
?>