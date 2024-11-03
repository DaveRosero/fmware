<?php
session_start();
require_once 'model/database/database.php';
require_once 'model/admin/logsClass.php';

date_default_timezone_set('Asia/Manila');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mysqli = database();
    $logs = new Logs(); // Create a new instance of Logs

    $replaced_items = [];

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

        // Check if replacement record already exists
        $check_replace_query = "SELECT id, total_replace_value FROM replacements WHERE pos_ref = ?";
        $stmt = prepareAndExecute($mysqli, $check_replace_query, [$pos_ref], 's', "Error checking replacement: ");
        $check_result = $stmt->get_result();
    
        if ($check_result && $check_result->num_rows > 0) {
            // Update existing replacement record
            $replace_row = $check_result->fetch_assoc();
            $replace_id = $replace_row['id'];
            $current_total_replace_value = $replace_row['total_replace_value'];
    
            // Add the new replacement value to the current total
            $new_total_replace_value = $current_total_replace_value + $total_replace_value;
    
            // Update the replacement record with the new total
            $replace_query = "UPDATE replacements SET total_replace_value = ? WHERE id = ?";
            prepareAndExecute($mysqli, $replace_query, [$new_total_replace_value, $replace_id], 'di', "Error updating replacement: ");
            $action_log = 'Updated replacement for Transaction ' . $pos_ref . ', New Total Amount: ₱' . number_format($new_total_replace_value,2);
        } else {
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
            $action_log = 'Created new replacement for Transaction ' . $pos_ref . ', Amount: ₱' . number_format($total_replace_value,2);
        }

    // Insert or update replacement items
    // Separate items into 'Good' and 'Broken'
    $good_items = [];
    $broken_items = [];
    foreach ($replaced_items as $item) {
        $product_id = $mysqli->real_escape_string($item['product_id']);
        $replace_qty = $mysqli->real_escape_string($item['refund_qty']);
        $condition = $mysqli->real_escape_string($item['condition']);

        // Check if item already exists in replacement_items with the same condition
        $check_item_query = "SELECT id, replace_qty FROM replacement_items WHERE replacement_id = ? AND product_id = ? AND item_condition = ?";
        $stmt = prepareAndExecute($mysqli, $check_item_query, [$replace_id, $product_id, $condition], 'iis', "Error checking replacement item: ");
        $check_result = $stmt->get_result();

        if ($check_result && $check_result->num_rows > 0) {
            // Update existing replacement item record
            $item_row = $check_result->fetch_assoc();
            $existing_replace_qty = $item_row['replace_qty'];
            $new_replace_qty = $existing_replace_qty + $replace_qty;

            $update_item_query = "UPDATE replacement_items SET replace_qty = ? WHERE id = ?";
            prepareAndExecute($mysqli, $update_item_query, [$new_replace_qty, $item_row['id']], 'ii', "Error updating replacement item: ");
        } else {
            // Insert new replacement item record
            $item_query = "INSERT INTO replacement_items (replacement_id, product_id, replace_qty, item_condition) VALUES (?, ?, ?, ?)";
            prepareAndExecute($mysqli, $item_query, [$replace_id, $product_id, $replace_qty, $condition], 'iiis', "Error inserting replacement item: ");
        }

        // Update pos_items table to subtract replace_qty from qty
        $update_pos_query = "UPDATE pos_items SET qty = qty - ? WHERE pos_ref = ? AND product_id = ?";
        prepareAndExecute($mysqli, $update_pos_query, [$replace_qty, $pos_ref, $product_id], 'isi', "Error updating pos_items: ");
        
        $update_order_query = "UPDATE order_items SET qty = qty - ? WHERE order_ref = ? AND product_id = ?";
        prepareAndExecute($mysqli, $update_order_query, [$replace_qty, $pos_ref, $product_id], 'isi', "Error updating pos_items: ");

        // Update stock table
        $update_stock_query = "UPDATE stock SET qty = qty + ? WHERE product_id = ?";
        prepareAndExecute($mysqli, $update_stock_query, [$replace_qty, $product_id], 'ii', "Error updating stock: ");
    }

    // Process Good Items
    foreach ($good_items as $item) {
        $product_id = $item['product_id'];
        $replace_qty = $item['replace_qty'];

        // Insert or update replacement item
        $item_query = "INSERT INTO replacement_items (replacement_id, product_id, replace_qty, item_condition) VALUES (?, ?, ?, 'Good')
                       ON DUPLICATE KEY UPDATE replace_qty = replace_qty + VALUES(replace_qty)";
        prepareAndExecute($mysqli, $item_query, [$replace_id, $product_id, $replace_qty], 'iii', "Error inserting or updating replacement item: ");

        // Update pos_items table to subtract replace_qty from qty
        $update_pos_query = "UPDATE pos_items SET qty = qty - ? WHERE pos_ref = ? AND product_id = ?";
        prepareAndExecute($mysqli, $update_pos_query, [$replace_qty, $pos_ref, $product_id], 'isi', "Error updating pos_items: ");

        $update_order_query = "UPDATE order_items SET qty = qty - ? WHERE order_ref = ? AND product_id = ?";
        prepareAndExecute($mysqli, $update_order_query, [$replace_qty, $pos_ref, $product_id], 'isi', "Error updating pos_items: ");

        // Update stock table to add replaced qty for good items
        $update_stock_query = "UPDATE stock SET qty = qty + ? WHERE product_id = ?";
        prepareAndExecute($mysqli, $update_stock_query, [$replace_qty, $product_id], 'ii', "Error updating stock: ");
    }

    // Process Broken Items
    foreach ($broken_items as $item) {
        $product_id = $item['product_id'];
        $replace_qty = $item['replace_qty'];

        // Insert or update replacement item
        $item_query = "INSERT INTO replacement_items (replacement_id, product_id, replace_qty, item_condition) VALUES (?, ?, ?, 'Broken')
                       ON DUPLICATE KEY UPDATE replace_qty = replace_qty + VALUES(replace_qty)";
        prepareAndExecute($mysqli, $item_query, [$replace_id, $product_id, $replace_qty], 'iii', "Error inserting or updating replacement item: ");

        // Update pos_items table to subtract replace_qty from qty
        $update_pos_query = "UPDATE pos_items SET qty = qty - ? WHERE pos_ref = ? AND product_id = ?";
        prepareAndExecute($mysqli, $update_pos_query, [$replace_qty, $pos_ref, $product_id], 'isi', "Error updating pos_items: ");

        $update_order_query = "UPDATE order_items SET qty = qty - ? WHERE order_ref = ? AND product_id = ?";
        prepareAndExecute($mysqli, $update_order_query, [$replace_qty, $pos_ref, $product_id], 'isi', "Error updating pos_items: ");
    }

    // Update transaction status
    $update_status_query = "UPDATE pos SET status = ? WHERE pos_ref = ?";
    prepareAndExecute($mysqli, $update_status_query, [$newStatus, $pos_ref], 'ss', "Error updating transaction status: ");

    $update_status_query = "UPDATE orders SET status = ? WHERE order_ref = ?";
    prepareAndExecute($mysqli, $update_status_query, [$newStatus, $pos_ref], 'ss', "Error updating transaction status: ");
    $logs->newLog($action_log, $user_id, date('F j, Y g:i A')); // Log the replacement action
    echo "Replacement processed successfully.";
    // Close the connection
    $mysqli->close();
} else {
    echo "Invalid request method.";
}
?>
