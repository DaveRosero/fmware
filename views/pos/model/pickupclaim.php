<?php
session_start();

require_once 'model/database/database.php';
require_once 'model/admin/logsClass.php';

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

$query = "UPDATE order SET status = 'Claimed' WHERE order_ref=?";

$stmt = $mysqli->prepare($query);
if ($stmt) {
    $stmt->bind_param('s', $order_ref);

    if ($stmt->execute()) {
        $stmt->close();

        $action_log = 'Transaction ' . $order_ref . ' has been Claimed ';
        $date_log = date('F j, Y g:i A');
        $logs->newLog($action_log, $user_id, $date_log);

        $query = 'SELECT product_id, qty FROM order_items WHERE order_ref = ?';
        $stmt = $mysqli->prepare($query);
        if ($stmt) {
            $stmt->bind_param('s', $order_ref);
            if ($stmt->execute()) {
                $stmt->bind_result($product_id, $qty);
                $products = array();
                while ($stmt->fetch()) {
                    $products[] = array(
                        'id' => $product_id,
                        'qty' => $qty
                    );
                }
                $stmt->close();
                // Update stock 
                foreach ($products as $product) {
                    $query = 'UPDATE stock SET qty = qty - ? WHERE product_id = ?';
                    $stmt = $mysqli->prepare($query);
                    if ($stmt) {
                        $stmt->bind_param('ii', $product['qty'], $product['id']);
                        if ($stmt->execute()) {
                            $stmt->close();
                        } else {
                            die("Error in executing statement: " . $stmt->error);
                        }
                    } else {
                        die("Error in preparing statement: " . $mysqli->error);
                    }
                }
                echo "Transaction successful.";
            } else {
                die("Error in executing statement: " . $stmt->error);
            }
        } else {
            die("Error in preparing statement: " . $mysqli->error);
        }
    } else {
        die("Error in executing statement: " . $stmt->error);
    }
} else {
    die("Error in preparing statement: " . $mysqli->error);
}
?>