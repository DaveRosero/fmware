<?php
require_once 'model/database/database.php'; // Adjust the path as per your project structure

// Connect to the database
$mysqli = database();

// Get the POST data from the AJAX request
$order_ref = isset($_POST['order_ref']) ? $_POST['order_ref'] : '';
$cancel_reason = isset($_POST['cancel_reason']) ? $_POST['cancel_reason'] : '';
$status = 'cancelled'; // Set status to 'cancelled'

// Validate input
if (empty($order_ref)) {
    echo json_encode(['success' => false, 'message' => 'Order reference is missing']);
    exit();
}
if (empty($cancel_reason)) {
    echo json_encode(['success' => false, 'message' => 'Cancellation reason is missing']);
    exit();
}

// Prepare the SQL query to update the order's status and reason
$query = 'UPDATE orders SET status = ?, cncl_reason = ? WHERE order_ref = ?';

// Initialize prepared statement
$stmt = $mysqli->prepare($query);

// Check if statement preparation was successful
if ($stmt) {
    // Bind parameters (s - string for status, cncl_reason, and order_ref)
    $stmt->bind_param('sss', $status, $cancel_reason, $order_ref);

    // Execute the query
    if ($stmt->execute()) {
        // Check if the query affected any rows
        if ($stmt->affected_rows > 0) {
            echo json_encode(['success' => true, 'message' => 'Order status updated to canceled with reason']);
        } else {
            echo json_encode(['success' => false, 'message' => 'No order found or already canceled']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to execute query']);
    }

    // Close the statement
    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to prepare statement']);
}

// Close the database connection
$mysqli->close();
?>
