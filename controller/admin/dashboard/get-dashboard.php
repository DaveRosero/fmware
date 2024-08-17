<?php
    include_once 'session.php';
    require_once 'model/admin/dashboardClass.php';

    $dashboard = new Dashboard();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $dashboard->getDashboard($_POST['sort']);
    } else {
        header('Location: /404');
        exit();
    }
?>