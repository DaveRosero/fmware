<?php
session_start();
require_once 'model/database/database.php';
require_once 'model/admin/logsClass.php';

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

    // Function to execute query and check for errors
    function executeQuery($mysqli, $query, $errorMessage)
    {
        if ($mysqli->query($query) === FALSE) {
            echo $errorMessage . $mysqli->error;
            exit;
        }
    }

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

     // Check if refund record already exists
    $check_refund_query = "SELECT id FROM refunds WHERE pos_ref = ?";
    $stmt = prepareAndExecute($mysqli, $check_refund_query, [$pos_ref], 's', "Error checking refund: ");
    $check_result = $stmt->get_result();

    if ($check_result && $check_result->num_rows > 0) {
        // Update existing refund record
        $refund_row = $check_result->fetch_assoc();
        $refund_id = $refund_row['id'];

        // Retrieve the current total refund value
        $current_refund_value_query = "SELECT total_refund_value FROM refunds WHERE id = ?";
        $stmt = prepareAndExecute($mysqli, $current_refund_value_query, [$refund_id], 'i', "Error retrieving current refund value: ");
        $current_refund_value_result = $stmt->get_result();
        $current_refund_value_row = $current_refund_value_result->fetch_assoc();
        $current_total_refund_value = $current_refund_value_row['total_refund_value'];

        // Add the new refund value to the current total
        $new_total_refund_value = $current_total_refund_value + $total_refund_value;

        // Update the refund record with the new total
        $refund_query = "UPDATE refunds SET total_refund_value = ? WHERE id = ?";
        prepareAndExecute($mysqli, $refund_query, [$new_total_refund_value, $refund_id], 'di', "Error updating refund: ");
        $action_log = 'Updated refund for Transaction ' . $pos_ref . ', New Total Amount: ₱' . $new_total_refund_value;
    } else {
        // Fetch the discount from the pos table
        $discount_query = "SELECT discount FROM pos WHERE pos_ref = ?";
        $stmt = prepareAndExecute($mysqli, $discount_query, [$pos_ref], 's', "Error retrieving discount: ");
        $discount_result = $stmt->get_result();
        $discount_row = $discount_result->fetch_assoc();
        $discount = $discount_row['discount'] ?? 0; // If discount is null, set it to 0

        // Subtract discount from total refund value
        $total_refund_value -= $discount;

        // Insert new refund record
        $refund_query = "INSERT INTO refunds (pos_ref, total_refund_value, reason) VALUES (?, ?, ?)";
        $stmt = prepareAndExecute($mysqli, $refund_query, [$pos_ref, $total_refund_value, $refund_reason], 'sds', "Error inserting refund: ");
        $refund_id = $mysqli->insert_id; // Get the ID of the inserted refund record
        $action_log = 'Created new refund for Transaction ' . $pos_ref . ', Amount: ₱' . $total_refund_value;
    }

    // Separate items into 'Good' and 'Broken'
    $good_items = [];
    $broken_items = [];
    foreach ($refund_items as $item) {
        $product_id = $mysqli->real_escape_string($item['product_id']);
        $refund_qty = $mysqli->real_escape_string($item['refund_qty']);
        $condition = $mysqli->real_escape_string($item['condition']);

        // Check if item already exists in refund_items with the same condition
        $check_item_query = "SELECT id, refund_qty FROM refund_items WHERE refund_id = ? AND product_id = ? AND item_condition = ?";
        $stmt = prepareAndExecute($mysqli, $check_item_query, [$refund_id, $product_id, $condition], 'iis', "Error checking refund item: ");
        $check_result = $stmt->get_result();

        if ($check_result && $check_result->num_rows > 0) {
            // Update existing refund item record
            $item_row = $check_result->fetch_assoc();
            $existing_refund_qty = $item_row['refund_qty'];
            $new_refund_qty = $existing_refund_qty + $refund_qty;

            $update_item_query = "UPDATE refund_items SET refund_qty = ? WHERE id = ?";
            prepareAndExecute($mysqli, $update_item_query, [$new_refund_qty, $item_row['id']], 'ii', "Error updating refund item: ");
        } else {
            // Insert new refund item record
            $item_query = "INSERT INTO refund_items (refund_id, product_id, refund_qty, item_condition) VALUES (?, ?, ?, ?)";
            prepareAndExecute($mysqli, $item_query, [$refund_id, $product_id, $refund_qty, $condition], 'iiis', "Error inserting refund item: ");
        }

        // Update pos_items table to subtract refund_qty from qty
        $update_pos_query = "UPDATE pos_items SET qty = qty - ? WHERE pos_ref = ? AND product_id = ?";
        prepareAndExecute($mysqli, $update_pos_query, [$refund_qty, $pos_ref, $product_id], 'isi', "Error updating pos_items: ");

        // Update pos_items table to subtract refund_qty from qty
        $update_order_query = "UPDATE order_items SET qty = qty - ? WHERE order_ref = ? AND product_id = ?";
        prepareAndExecute($mysqli, $update_order_query, [$refund_qty, $pos_ref, $product_id], 'isi', "Error updating pos_items: ");

        // Update stock table
        $update_stock_query = "UPDATE stock SET qty = qty + ? WHERE product_id = ?";
        prepareAndExecute($mysqli, $update_stock_query, [$refund_qty, $product_id], 'ii', "Error updating stock: ");
    }

    // Process Good Items
    foreach ($good_items as $item) {
        $product_id = $item['product_id'];
        $refund_qty = $item['refund_qty'];

        // Insert or update refund item
        $item_query = "INSERT INTO refund_items (refund_id, product_id, refund_qty, item_condition) VALUES (?, ?, ?, 'Good')
                       ON DUPLICATE KEY UPDATE refund_qty = refund_qty + VALUES(refund_qty)";
        prepareAndExecute($mysqli, $item_query, [$refund_id, $product_id, $refund_qty], 'iii', "Error inserting or updating refund item: ");

        // Update pos_items table to subtract refund_qty from qty
        $update_pos_query = "UPDATE pos_items SET qty = qty - ? WHERE pos_ref = ? AND product_id = ?";
        prepareAndExecute($mysqli, $update_pos_query, [$refund_qty, $pos_ref, $product_id], 'isi', "Error updating pos_items: ");

        $update_order_query = "UPDATE order_items SET qty = qty - ? WHERE order_ref = ? AND product_id = ?";
        prepareAndExecute($mysqli, $update_order_query, [$refund_qty, $pos_ref, $product_id], 'isi', "Error updating pos_items: ");

        // Update stock table
        $update_stock_query = "UPDATE stock SET qty = qty + ? WHERE product_id = ?";
        prepareAndExecute($mysqli, $update_stock_query, [$refund_qty, $product_id], 'ii', "Error updating stock: ");
    }

    // Process Broken Items
    foreach ($broken_items as $item) {
        $product_id = $item['product_id'];
        $refund_qty = $item['refund_qty'];

        // Insert or update refund item
        $item_query = "INSERT INTO refund_items (refund_id, product_id, refund_qty, item_condition) VALUES (?, ?, ?, 'Broken')
                           ON DUPLICATE KEY UPDATE refund_qty = refund_qty + VALUES(refund_qty)";
        prepareAndExecute($mysqli, $item_query, [$refund_id, $product_id, $refund_qty], 'iii', "Error inserting or updating refund item: ");

        // Update pos_items table to subtract refund_qty from qty
        $update_pos_query = "UPDATE pos_items SET qty = qty - ? WHERE pos_ref = ? AND product_id = ?";
        prepareAndExecute($mysqli, $update_pos_query, [$refund_qty, $pos_ref, $product_id], 'isi', "Error updating pos_items: ");

        $update_order_query = "UPDATE order_items SET qty = qty - ? WHERE order_ref = ? AND product_id = ?";
        prepareAndExecute($mysqli, $update_order_query, [$refund_qty, $pos_ref, $product_id], 'isi', "Error updating pos_items: ");
    }

    $update_status_query = "UPDATE pos SET status = ? WHERE pos_ref = ?";
    prepareAndExecute($mysqli, $update_status_query, [$newStatus, $pos_ref], 'ss', "Error updating transaction status: ");
    $update_status_query = "UPDATE orders SET status = ? WHERE order_ref = ?";
    prepareAndExecute($mysqli, $update_status_query, [$newStatus, $pos_ref], 'ss', "Error updating transaction status: ");
    $logs->newLog($action_log, $user_id, date('F j, Y g:i A')); // Log the refund action
    echo "Refund processed successfully.";
    // Close the connection
    $mysqli->close();
} else {
    echo "Invalid request method.";
}
?>
