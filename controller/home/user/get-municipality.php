<?php
    include_once 'session.php';
    require_once 'model/user/addressClass.php';

    $user = new Address();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $json = $user->getMunicipality($_POST['brgy']);
        echo json_encode($json);
    } else {
        header('Location: /404');
        exit();
    }
?>