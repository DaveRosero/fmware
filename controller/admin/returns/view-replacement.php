<?php
require_once 'session.php';
require_once 'model/admin/returnClass.php';
$return = new Returns();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $return->viewReplacement($_POST['replacement_id'], $_POST['type']);
} else {
    header('Location: /404');
    exit();
}
