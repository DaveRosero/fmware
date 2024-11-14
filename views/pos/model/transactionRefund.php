<?php
session_start();
require_once 'model/database/database.php';
require_once 'model/admin/logsClass.php';
require_once 'session.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mysqli = database();
    $logs = new Logs(); // Create a new instance of Logs

    $refund_items = [];

    // Validate and sanitize input
    $pos_ref = $mysqli->real_escape_string($_POST['pos_ref']);
    $total_refund_value = $mysqli->real_escape_string($_POST['total_refund_value']);
    $refund_items = $_POST['refund_items']; // This should be an array of items 
    $refund_reason = $mysqli->real_escape_string($_POST['refund_reason']); // Capture refund reason from POST data
    $newStatus = $mysqli->real_escape_string($_POST['status']); // Changed from 'newStatus' to 'status'
    $user_id = $_SESSION['user_id']; // Capture the user ID from the session

    // Get current timestamp and format it as Y-m-d H:i:s
    $timestamp = time(); // Current timestamp
    $refund_date = date("Y-m-d H:i:s", $timestamp); // Format as "2024-11-14 09:35:11"

    // Function to prepare and execute statement
    function prepareAndExecute($mysqli, $query, $params, $types, $errorMessage)
    {
        $stmt = $mysqli->prepare($query);
        if ($stmt === FALSE) {
            echo $errorMessage . $mysqli->error;
            exit;
        }
        $stmt->bind_param($types, ...$params);
        if ($stmt->execute() === FALSE) {
            echo $errorMessage . $stmt->error;
            exit;
        }
        return $stmt;
    }

    // Insert new refund record with refund_date
    $refund_query = "INSERT INTO refunds (pos_ref, total_refund_value, reason, refund_date) VALUES (?, ?, ?, ?)";
    $stmt = prepareAndExecute($mysqli, $refund_query, [$pos_ref, $total_refund_value, $refund_reason, $refund_date], 'sdss', "Error inserting refund: ");
    $refund_id = $mysqli->insert_id; // Get the ID of the inserted refund record
    $action_log = 'Created new refund for Transaction ' . $pos_ref . ', Amount: ₱' . number_format($total_refund_value, 2);

    // Separate items into 'Good' and 'Broken'
    foreach ($refund_items as $item) {
        $product_id = $mysqli->real_escape_string($item['product_id']);
        $refund_qty = $mysqli->real_escape_string($item['refund_qty']);
        $condition = $mysqli->real_escape_string($item['condition']);

        // Insert new refund item record
        $item_query = "INSERT INTO refund_items (refund_id, product_id, refund_qty, item_condition) VALUES (?, ?, ?, ?)";
        prepareAndExecute($mysqli, $item_query, [$refund_id, $product_id, $refund_qty, $condition], 'iiis', "Error inserting refund item: ");

        // Update pos_items table to subtract refund_qty from qty
        $update_pos_query = "UPDATE pos_items SET qty = qty - ? WHERE pos_ref = ? AND product_id = ?";
        prepareAndExecute($mysqli, $update_pos_query, [$refund_qty, $pos_ref, $product_id], 'isi', "Error updating pos_items: ");

        // Update order_items table to subtract refund_qty from qty
        $update_order_query = "UPDATE order_items SET qty = qty - ? WHERE order_ref = ? AND product_id = ?";
        prepareAndExecute($mysqli, $update_order_query, [$refund_qty, $pos_ref, $product_id], 'isi', "Error updating order_items: ");

        // Update stock table to add refunded quantity
        $update_stock_query = "UPDATE stock SET qty = qty + ? WHERE product_id = ?";
        prepareAndExecute($mysqli, $update_stock_query, [$refund_qty, $product_id], 'ii', "Error updating stock: ");
    }

    // Update transaction status
    $update_status_query = "UPDATE pos SET status = ? WHERE pos_ref = ?";
    prepareAndExecute($mysqli, $update_status_query, [$newStatus, $pos_ref], 'ss', "Error updating transaction status: ");
    $update_status_query = "UPDATE orders SET status = ? WHERE order_ref = ?";
    prepareAndExecute($mysqli, $update_status_query, [$newStatus, $pos_ref], 'ss', "Error updating order status: ");

    // Log the action
    $current_date = date("F j, Y g:i A", $timestamp); // Format the log date as "November 14, 2024 10:14 AM"
    $logs->newLog($action_log, $user_id, $current_date); // Log the refund action
    echo "Refund processed successfully.";

    // Close the connection
    $mysqli->close();
} else {
    echo "Invalid request method.";
}
