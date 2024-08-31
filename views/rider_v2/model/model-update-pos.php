<?php
require_once 'model/database/database.php';

// Retrieve the logged-in rider's ID from the session
$rider_id = $_POST['rider_id'];
$pos_ref = $_POST['pos_ref']; // Use null coalescing operator to handle missing values

// Check if pos_ref is provided
if (!$pos_ref) {
    echo json_encode(['success' => false, 'message' => 'POS reference is missing']);
    exit;
}

// Connect to the database
$mysqli = database();

// Prepare the SQL statement
$query = "UPDATE `pos` SET status = 'delivering', rider_id = ? WHERE pos_ref = ? AND status = 'pending' AND (rider_id IS NULL OR rider_id = '')";
$stmt = $mysqli->prepare($query);

if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Failed to prepare statement: ' . $mysqli->error]);
    $mysqli->close();
    exit;
}

// Bind parameters and execute
$stmt->bind_param('is', $rider_id, $pos_ref);
$success = $stmt->execute();

if (!$success) {
    echo json_encode(['success' => false, 'message' => 'Failed to execute statement: ' . $stmt->error]);
}

// Close MySQL connection
$stmt->close();
$mysqli->close();

// Send JSON response
echo json_encode(['success' => $success, 'message' => $success ? 'POS order accepted' : 'Failed to accept POS order']);
?>
