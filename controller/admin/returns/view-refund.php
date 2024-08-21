<?php
require_once 'session.php';
require_once 'model/admin/returnClass.php';
$return = new Returns();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $return->viewRefund($_POST['refund_id']);
} else {
    header('Location: /404');
    exit();
}
