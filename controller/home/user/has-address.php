<?php
    include_once 'session.php';
    require_once 'model/user/addressClass.php';

    $user = new Address();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = $_POST['id'];
        $address = $user->hasAddress($id);
        echo json_encode($address);
    } else {
        header('Location: /fmware/404');
        exit();
    }
?>