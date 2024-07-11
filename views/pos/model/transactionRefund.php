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
            $qty = $mysqli->real_escape_string($item['qty']);
            $condition = $mysqli->real_escape_string($item['condition']);


            $item_query = "INSERT INTO refund_items (refund_id, product_id, qty) VALUES ('$refund_id', '$product_id', '$qty')";
            $mysqli->query($item_query);

            if ($condition === '1') {
                $stock_query = "UPDATE stock SET qty = qty + $qty WHERE product_id = $product_id";
                $mysqli->query($stock_query);
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