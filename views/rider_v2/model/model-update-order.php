<?php
require_once 'model/database/database.php';

// Retrieve the logged-in rider's ID from the session
$rider_id = $_POST['rider_id'];
$order_ref = $_POST['order_ref']; // Use null coalescing operator to handle missing values

// Check if order_ref is provided
if (!$order_ref) {
    echo json_encode(['success' => false, 'message' => 'Order reference is missing']);
    exit;
}

// Connect to the database
$mysqli = database();

// Prepare the SQL statement
$query = "UPDATE `orders` SET status = 'delivering', rider_id = ? WHERE order_ref = ? AND status = 'pending' AND (rider_id IS NULL OR rider_id = '')";
$stmt = $mysqli->prepare($query);

if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Failed to prepare statement: ' . $mysqli->error]);
    $mysqli->close();
    exit;
}

// Bind parameters and execute
$stmt->bind_param('is', $rider_id, $order_ref);
$success = $stmt->execute();

if (!$success) {
    echo json_encode(['success' => false, 'message' => 'Failed to execute statement: ' . $stmt->error]);
}

// Close MySQL connection
$stmt->close();
$mysqli->close();

// Send JSON response
echo json_encode(['success' => $success, 'message' => $success ? 'Order accepted' : 'Failed to accept order']);
?>
