<?php
require_once 'model/database/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mysqli = database();
    $refund_items = [];

    $pos_ref = $mysqli->real_escape_string($_POST['pos_ref']);
    $total_refund_value = $mysqli->real_escape_string($_POST['total_refund_value']);
    $refund_items = $_POST['refund_items']; // This should be an array of items 
    $refund_reason = $mysqli->real_escape_string($_POST['refund_reason']); // Capture refund reason from POST data

    // Check if refund record already exists
    $check_refund_query = "SELECT id FROM refunds WHERE pos_ref = '$pos_ref'";
    $check_result = $mysqli->query($check_refund_query);

    if ($check_result && $check_result->num_rows > 0) {
        // Update existing refund record
        $refund_row = $check_result->fetch_assoc();
        $refund_id = $refund_row['id'];

        $refund_query = "UPDATE refunds SET total_refund_value = '$total_refund_value' WHERE id = '$refund_id'";
        if ($mysqli->query($refund_query) === FALSE) {
            echo "Error updating refund: " . $mysqli->error;
            exit;
        }
    } else {
        // Insert new refund record
        $refund_query = "INSERT INTO refunds (pos_ref, total_refund_value, reason) VALUES ('$pos_ref', '$total_refund_value', '$refund_reason')";
        if ($mysqli->query($refund_query) === TRUE) {
            $refund_id = $mysqli->insert_id; // Get the ID of the inserted refund record
        } else {
            echo "Error inserting refund: " . $mysqli->error;
            exit;
        }
    }

    // Insert or update refund items
    foreach ($refund_items as $item) {
        $product_id = $mysqli->real_escape_string($item['product_id']);
        $refund_qty = $mysqli->real_escape_string($item['refund_qty']);
        $condition = $mysqli->real_escape_string($item['condition']);

        // Check if refund item exists for current refund
        $check_item_query = "SELECT id FROM refund_items WHERE refund_id = '$refund_id' AND product_id = '$product_id'";
        $check_item_result = $mysqli->query($check_item_query);

        if ($check_item_result && $check_item_result->num_rows > 0) {
            // Update existing refund item
            $item_row = $check_item_result->fetch_assoc();
            $item_id = $item_row['id'];

            $update_item_query = "UPDATE refund_items SET refund_qty = refund_qty + '$refund_qty' WHERE id = '$item_id'";
            if ($mysqli->query($update_item_query) === FALSE) {
                echo "Error updating refund item: " . $mysqli->error;
                exit;
            }
        } else {
            // Insert new refund item
            $item_query = "INSERT INTO refund_items (refund_id, product_id, refund_qty) VALUES ('$refund_id', '$product_id', '$refund_qty')";
            if ($mysqli->query($item_query) === FALSE) {
                echo "Error inserting refund item: " . $mysqli->error;
                exit;
            }
        }

        // Update pos_items table to subtract refund_qty from qty
        $update_pos_query = "UPDATE pos_items SET qty = qty - $refund_qty WHERE pos_ref = '$pos_ref' AND product_id = $product_id";
        if ($mysqli->query($update_pos_query) === FALSE) {
            echo "Error updating pos_items: " . $mysqli->error;
            exit;
        }

        // Update stock table if condition is '1' (Good condition)
        if ($condition === '1') {
            $update_stock_query = "UPDATE stock SET qty = qty + $refund_qty WHERE product_id = $product_id";
            if ($mysqli->query($update_stock_query) === FALSE) {
                echo "Error updating stock: " . $mysqli->error;
                exit;
            }
        }
    }

    $update_status_query = "UPDATE pos SET status = 'refunded' WHERE pos_ref = '$pos_ref'";
    if ($mysqli->query($update_status_query) === FALSE) {
        echo "Error updating transaction status: " . $mysqli->error;
        exit;
    }


    echo "Refund processed successfully.";
    // Close the connection
    $mysqli->close();
} else {
    echo "Invalid request method.";
}
