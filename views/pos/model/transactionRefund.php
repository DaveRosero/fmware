<?php
require_once 'model/database/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mysqli = database();
    $refund_items = [];
    
    $pos_ref = $mysqli->real_escape_string($_POST['pos_ref']);
    $total_refund_value = $mysqli->real_escape_string($_POST['total_refund_value']);
    $refund_items = $_POST['refund_items']; // This should be an array of items

    // Insert refund record
    $refund_query = "INSERT INTO refunds (pos_ref, total_refund_value) VALUES ('$pos_ref', '$total_refund_value')";
    if ($mysqli->query($refund_query) === TRUE) {
        $refund_id = $mysqli->insert_id; // Get the ID of the inserted refund record
        

        // Insert refund items
        foreach ($refund_items as $item) {
            $product_id = $mysqli->real_escape_string($item['product_id']);
            $refund_qty = $mysqli->real_escape_string($item['refund_qty']);
            $condition = $mysqli->real_escape_string($item['condition']);


            $item_query = "INSERT INTO refund_items (refund_id, product_id, refund_qty) VALUES ('$refund_id', '$product_id', '$refund_qty')";
            if ($mysqli->query($item_query) === FALSE) {
                echo "Error inserting refund item: " . $mysqli->error;
                exit;
            }

            // Update pos_items table to subtract refund_qty from qty
            $update_pos_query = "UPDATE pos_items SET qty = qty - $refund_qty WHERE pos_ref = '$pos_ref' AND product_id = $product_id";
            $mysqli->query($update_pos_query);

            if ($condition === '1') {
                $update_stock_query  = "UPDATE stock SET qty = qty + $refund_qty WHERE product_id = $product_id";
                $mysqli->query($update_stock_query );
            }
        }

        echo "Refund processed successfully.";
    } else {
        echo "Error: " . $refund_query . "<br>" . $mysqli->error;
    }

    // Close the connection
    $mysqli->close();
} else {
    echo "Invalid request method.";
}