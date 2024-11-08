<?php
session_start();
require_once 'model/database/database.php';
require_once 'model/admin/logsClass.php';

date_default_timezone_set('Asia/Manila');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mysqli = database();
    $logs = new Logs(); // Create a new instance of Logs

    // Validate and sanitize input
    $pos_ref = $mysqli->real_escape_string($_POST['pos_ref']);
    $total_replace_value = $mysqli->real_escape_string($_POST['total_refund_value']);
    $replaced_items = $_POST['replaced_items']; // This should be an array of items
    $replacement_reason = $mysqli->real_escape_string($_POST['replacement_reason']); // Capture replacement reason from POST data
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

    // Fetch the discount from the pos table
    $discount_query = "SELECT discount FROM pos WHERE pos_ref = ?";
    $stmt = prepareAndExecute($mysqli, $discount_query, [$pos_ref], 's', "Error retrieving discount: ");
    $discount_result = $stmt->get_result();
    $discount_row = $discount_result->fetch_assoc();
    $discount = $discount_row['discount'] ?? 0; // If discount is null, set it to 0

    // Subtract discount from total replace value
    $total_replace_value -= $discount;

    // Insert new replacement record
    $replace_query = "INSERT INTO replacements (pos_ref, total_replace_value, reason) VALUES (?, ?, ?)";
    $stmt = prepareAndExecute($mysqli, $replace_query, [$pos_ref, $total_replace_value, $replacement_reason], 'sds', "Error inserting replacement: ");
    $replace_id = $mysqli->insert_id; // Get the ID of the inserted replacement record
    $action_log = 'Created new replacement for Transaction ' . $pos_ref . ', Amount: ₱' . number_format($total_replace_value, 2);

    // Insert or update replacement items (process Good and Broken items)
    foreach ($replaced_items as $item) {
        $product_id = $mysqli->real_escape_string($item['product_id']);
        $replace_qty = $mysqli->real_escape_string($item['refund_qty']);
        $condition = $mysqli->real_escape_string($item['condition']);

        // Insert new replacement item record
        $item_query = "INSERT INTO replacement_items (replacement_id, product_id, replace_qty, item_condition) VALUES (?, ?, ?, ?)";
        prepareAndExecute($mysqli, $item_query, [$replace_id, $product_id, $replace_qty, $condition], 'iiis', "Error inserting replacement item: ");

        // Update pos_items table to subtract replace_qty from qty (reflect remaining items)
        $update_pos_query = "UPDATE pos_items SET qty = qty - ? WHERE pos_ref = ? AND product_id = ?";
        prepareAndExecute($mysqli, $update_pos_query, [$replace_qty, $pos_ref, $product_id], 'isi', "Error updating pos_items: ");

        // Update order_items table to subtract replace_qty from qty
        $update_order_query = "UPDATE order_items SET qty = qty - ? WHERE order_ref = ? AND product_id = ?";
        prepareAndExecute($mysqli, $update_order_query, [$replace_qty, $pos_ref, $product_id], 'isi', "Error updating order_items: ");

        // Update stock table to reflect the replacement
        $update_stock_query = "UPDATE stock SET qty = qty + ? WHERE product_id = ?";
        prepareAndExecute($mysqli, $update_stock_query, [$replace_qty, $product_id], 'ii', "Error updating stock: ");
    }

    // Check if all items have qty = 0 in the pos_items table for the transaction
    $check_qty_query = "SELECT qty FROM pos_items WHERE pos_ref = ?";
    $stmt = prepareAndExecute($mysqli, $check_qty_query, [$pos_ref], 's', "Error checking remaining quantities: ");
    $result = $stmt->get_result();

    $fully_replaced = true;

    while ($row = $result->fetch_assoc()) {
        if ($row['qty'] > 0) {
            $fully_replaced = false;
            break;
        }
    }

    // Set status based on whether all items have been replaced (qty = 0)
    if ($fully_replaced) {
        $newStatus = 'fully replaced';
    } else {
        $newStatus = 'partially replaced';
    }

    // Update transaction status in both pos and orders table
    $update_status_query = "UPDATE pos SET status = ? WHERE pos_ref = ?";
    prepareAndExecute($mysqli, $update_status_query, [$newStatus, $pos_ref], 'ss', "Error updating transaction status: ");
    $update_status_query = "UPDATE orders SET status = ? WHERE order_ref = ?";
    prepareAndExecute($mysqli, $update_status_query, [$newStatus, $pos_ref], 'ss', "Error updating order status: ");

    // Log the replacement action
    $logs->newLog($action_log, $user_id, date('F j, Y g:i A'));
    echo "Replacement processed successfully.";

    // Close the connection
    $mysqli->close();
} else {
    echo "Invalid request method.";
}
?>
