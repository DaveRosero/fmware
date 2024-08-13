<?php
    include_once 'session.php';
    require_once 'model/admin/reportClass.php';

    $report = new Reports();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $report->createReport($_POST['module'], $_POST['start_date'], $_POST['end_date']);
    } else {
        header('Location: /404');
        exit();
    }
?>