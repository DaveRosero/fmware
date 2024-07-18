<?php
    require_once 'session.php';
    require_once 'model/admin/userListClass.php';

    $user = new UserList();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $user->updateUserStatus($_POST['user_id'], $_POST['status']);
    } else {
        header('Location: /404');
        exit();
    }
?>