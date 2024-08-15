<?php
session_start();
require_once 'model/database/database.php';

// Check if the user is logged in and is a rider
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

// Retrieve the logged-in rider's ID from the session
$rider_id = $_SESSION['user_id'];

// Send JSON response
echo json_encode(['success' => true, 'rider_id' => $rider_id]);
?>
