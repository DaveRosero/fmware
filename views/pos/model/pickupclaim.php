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
        echo json_encode(['status' => 'error', 'message' => "Missing POST variable $key"]);
        exit;
    }
}


$order_ref = $_POST['order_ref'];
$cash=  $_POST['cash'];
$changes = $_POST['changes'];

// Prepare and execute the query to update the order status
$query = "UPDATE orders SET status = 'claimed', paid = 'paid', cash = ?, changes = ? WHERE order_ref=?";
$stmt = $mysqli->prepare($query);
if ($stmt) {
    $stmt->bind_param('dds', $cash, $changes, $order_ref);

    if ($stmt->execute()) {
        $stmt->close();

        $action_log = 'Transaction ' . $order_ref . ' has been Claimed';
        $date_log = date('F j, Y g:i A');
        $logs->newLog($action_log, $user_id, $date_log);

        // Fetch order items
        $query = 'SELECT product_id, qty FROM order_items WHERE order_ref = ?';
        $stmt = $mysqli->prepare($query);
        if ($stmt) {
            $stmt->bind_param('s', $order_ref);
            if ($stmt->execute()) {
                $stmt->bind_result($product_id, $qty);
                $products = [];
                while ($stmt->fetch()) {
                    $products[] = [
                        'id' => $product_id,
                        'qty' => $qty
                    ];
                }
                $stmt->close();

                // Update stock
                foreach ($products as $product) {
                    $query = 'UPDATE stock SET qty = qty - ? WHERE product_id = ?';
                    $stmt = $mysqli->prepare($query);
                    if ($stmt) {
                        $stmt->bind_param('ii', $product['qty'], $product['id']);
                        if (!$stmt->execute()) {
                            echo json_encode(['status' => 'error', 'message' => "Error in executing statement: " . $stmt->error]);
                            exit;
                        }
                        $stmt->close();
                    } else {
                        echo json_encode(['status' => 'error', 'message' => "Error in preparing statement: " . $mysqli->error]);
                        exit;
                    }
                }
                echo json_encode(['status' => 'success', 'message' => "Transaction successful."]);
            } else {
                echo json_encode(['status' => 'error', 'message' => "Error in executing statement: " . $stmt->error]);
                exit;
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => "Error in preparing statement: " . $mysqli->error]);
            exit;
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => "Error in executing statement: " . $stmt->error]);
        exit;
    }
} else {
    echo json_encode(['status' => 'error', 'message' => "Error in preparing statement: " . $mysqli->error]);
    exit;
}
?>
