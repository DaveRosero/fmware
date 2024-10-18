<?php
session_start();

require_once 'model/database/database.php';
require_once 'model/admin/logsClass.php';

date_default_timezone_set('Asia/Manila'); 

$user_id = $_SESSION['user_id'];
$mysqli = database();
$logs = new Logs();

// Check if POST variables are set
$post_keys = ['order_ref'];
foreach ($post_keys as $key) {
    if (!isset($_POST[$key])) {
        die("Error: Missing POST variable $key");
    }
}

$order_ref = $_POST['order_ref'];

$query = "UPDATE orders SET status = 'prepared' WHERE order_ref=?";

$stmt = $mysqli->prepare($query);
if ($stmt) {
    $stmt->bind_param('s', $order_ref);

    if ($stmt->execute()) {
        $stmt->close();

        $action_log = 'Transaction ' . $order_ref . ' has set to Prepared ';
        $date_log = date('F j, Y g:i A');
        $logs->newLog($action_log, $user_id, $date_log);
    } else {
        die("Error in executing statement: " . $stmt->error);
    }
} else {
    die("Error in preparing statement: " . $mysqli->error);
}
?>