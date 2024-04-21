<?php
    include_once 'session.php';
    require_once 'model/admin/unitClass.php';

    $unit = new Unit();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $unit->newUnit();
    } else {
        header('Location: /404');
        exit();
    }
?>