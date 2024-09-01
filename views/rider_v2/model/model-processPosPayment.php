<?php
require_once 'model/database/database.php'; // Adjust the path as needed

// Connect to the database
$mysqli = database();

// Retrieve POST data
$pos_ref = $_POST['pos_ref'] ?? '';
$status = $_POST['status'] ?? '';
$payment_type = $_POST['payment_type'] ?? '';
$cash_received = $_POST['cash_received'] ?? 0;

// Ensure all necessary data is provided
if (empty($pos_ref) || empty($status) || empty($payment_type)) {
    echo json_encode(['success' => false, 'message' => 'Missing data']);
    $mysqli->close();
    exit;
}

// Fetch payment_type_id from payment_type table
$payment_type_id = 0;
$sql = "SELECT id FROM payment_type WHERE id = ? AND active = 1";
$stmt = $mysqli->prepare($sql);

if ($stmt) {
    $stmt->bind_param('i', $payment_type);
    $stmt->execute();
    $stmt->bind_result($payment_type_id);
    $stmt->fetch();
    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to prepare payment type query: ' . $mysqli->error]);
    $mysqli->close();
    exit;
}

// Ensure payment_type_id was found
if ($payment_type_id == 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid payment type']);
    $mysqli->close();
    exit;
}

// Update the POS status, payment type, and cash received
$sql = "UPDATE pos 
        SET status = ?, 
            paid = 'paid', 
            payment_type_id = ?, 
            cash = ? 
        WHERE pos_ref = ?";
$stmt = $mysqli->prepare($sql);

if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Failed to prepare update statement: ' . $mysqli->error]);
    $mysqli->close();
    exit;
}

$stmt->bind_param('sdis', $status, $payment_type_id, $cash_received, $pos_ref);

if ($stmt->execute()) {
    $response = ['success' => true];
} else {
    $response = ['success' => false, 'message' => 'Failed to update POS: ' . $stmt->error];
}

$stmt->close();
$mysqli->close();

// Send JSON response
header('Content-Type: application/json');
echo json_encode($response);
?>
