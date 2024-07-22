<?php
session_start();
require_once 'model/database/database.php';
require_once 'model/admin/logsClass.php'; // Include the logging class

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mysqli = database();
    $logs = new Logs(); // Create a new instance of Logs

    $replaced_items = [];

    $pos_ref = $mysqli->real_escape_string($_POST['pos_ref']);
    $total_replace_value = $mysqli->real_escape_string($_POST['total_refund_value']); // Corrected variable name
    $replaced_items = $_POST['replaced_items']; // This should be an array of items
    $replacement_reason = $mysqli->real_escape_string($_POST['replacement_reason']); // Capture refund reason from POST data
    $newStatus = $mysqli->real_escape_string($_POST['status']); // Changed from 'newStatus' to 'status'
    $user_id = $_SESSION['user_id']; // Capture the user ID from the session



    // Check if replacement record already exists
    $check_replace_query = "SELECT id FROM replacements WHERE pos_ref = '$pos_ref'";
    $check_result = $mysqli->query($check_replace_query);

    if ($check_result && $check_result->num_rows > 0) {
        // Update existing replacement record
        $replace_row = $check_result->fetch_assoc();
        $replace_id = $replace_row['id'];

        $replace_query = "UPDATE replacements SET total_replace_value = '$total_replace_value' WHERE id = '$replace_id'";
        if ($mysqli->query($replace_query) === FALSE) {
            echo "Error updating replacement: " . $mysqli->error;
            exit;
        }
        $action_log = 'Updated replacement for Transaction ' . $pos_ref . ', Amount: ₱' . $total_replace_value . ', Reason: ' . $replacement_reason;
    } else {
        // Insert new replacement record
        $replace_query = "INSERT INTO replacements (pos_ref, total_replace_value, reason) VALUES ('$pos_ref', '$total_replace_value', '$replacement_reason')";
        if ($mysqli->query($replace_query) === TRUE) {
            $replace_id = $mysqli->insert_id; // Get the ID of the inserted replacement record
            $action_log = 'Created new replacement for Transaction ' . $pos_ref . ', Amount: ₱' . $total_replace_value . ', Reason: ' . $replacement_reason;
        } else {
            echo "Error inserting replacement: " . $mysqli->error;
            exit;
        }
    }

    // Insert or update replacement items
    foreach ($replaced_items as $item) {
        $product_id = $mysqli->real_escape_string($item['product_id']);
        $replace_qty = $mysqli->real_escape_string($item['refund_qty']);

        // Check if replacement item exists for current replacement
        $check_item_query = "SELECT id FROM replacement_items WHERE replacement_id = '$replace_id' AND product_id = '$product_id'";
        $check_item_result = $mysqli->query($check_item_query);

        if ($check_item_result && $check_item_result->num_rows > 0) {
            // Update existing replacement item
            $item_row = $check_item_result->fetch_assoc();
            $item_id = $item_row['id'];

            $update_item_query = "UPDATE replacement_items SET replace_qty = replace_qty + '$replace_qty' WHERE id = '$item_id'";
            if ($mysqli->query($update_item_query) === FALSE) {
                echo "Error updating replacement item: " . $mysqli->error;
                exit;
            }
        } else {
            // Insert new replacement item
            $item_query = "INSERT INTO replacement_items (replacement_id, product_id, replace_qty) VALUES ('$replace_id', '$product_id', '$replace_qty')";
            if ($mysqli->query($item_query) === FALSE) {
                echo "Error inserting replacement item: " . $mysqli->error;
                exit;
            }
            $item_action_log = 'Inserted new replacement item for Transaction ' . $pos_ref . ', Product ID: ' . $product_id . ', Quantity: ' . $replace_qty;
        }

        // Update pos_items table to subtract replace_qty from qty
        $update_pos_query = "UPDATE pos_items SET qty = qty - $replace_qty WHERE pos_ref = '$pos_ref' AND product_id = $product_id";
        if ($mysqli->query($update_pos_query) === FALSE) {
            echo "Error updating pos_items: " . $mysqli->error;
            exit;
        }
        // Log item action
        $logs->newLog($item_action_log, $user_id, date('F j, Y g:i A')); // Log the replacement item action
        // Update stock table if necessary (optional based on your requirements)
    }

    // Update transaction status to 'replaced'
    $update_status_query = "UPDATE pos SET status = '$newStatus' WHERE pos_ref = '$pos_ref'";
    if ($mysqli->query($update_status_query) === FALSE) {
        echo "Error updating transaction status: " . $mysqli->error;
        exit;
    }

    echo "Replacement processed successfully.";
    // Log the replacement action
    $logs->newLog($action_log, $user_id, date('F j, Y g:i A')); // Log the replacement action
    // Close the connection
    $mysqli->close();
} else {
    echo "Invalid request method.";
}
