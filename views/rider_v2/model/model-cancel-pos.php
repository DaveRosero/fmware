<?php
require_once 'model/database/database.php'; // Adjust the path as per your project structure

// Connect to the database
$mysqli = database();

// Get the POST data from the AJAX request
$pos_ref = isset($_POST['pos_ref']) ? $_POST['pos_ref'] : '';
$cancel_reason = isset($_POST['cancel_reason']) ? $_POST['cancel_reason'] : ''; // Get the cancellation reason
$status = 'cancelled'; // Set status to 'canceled'

// Validate input
if (empty($pos_ref)) {
    echo json_encode(['success' => false, 'message' => 'POS reference is missing']);
    exit();
}

if (empty($cancel_reason)) {
    echo json_encode(['success' => false, 'message' => 'Cancellation reason is missing']);
    exit();
}

// Prepare the SQL query to update the POS order's status and cancellation reason
$query = 'UPDATE pos SET status = ?, cncl_reason = ? WHERE pos_ref = ?';

// Initialize prepared statement
$stmt = $mysqli->prepare($query);

// Check if statement preparation was successful
if ($stmt) {
    // Bind parameters (s - string, s - string, s - string for status, reason, and pos_ref)
    $stmt->bind_param('sss', $status, $cancel_reason, $pos_ref);

    // Execute the query
    if ($stmt->execute()) {
        // Check if the query affected any rows
        if ($stmt->affected_rows > 0) {
            echo json_encode(['success' => true, 'message' => 'POS order status updated to canceled']);
        } else {
            echo json_encode(['success' => false, 'message' => 'No POS order found or already canceled']);
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
