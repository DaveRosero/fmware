<?php
require_once 'model/database/database.php'; // Adjust this to your database connection file

if (!isset($_GET['pos_ref'])) {
    die('Transaction ID not specified');
}

$mysqli = database();

// Retrieve the transaction details based on pos_ref
$pos_ref = $mysqli->real_escape_string($_GET['pos_ref']); // Sanitize input

$query = "SELECT p.pos_ref, p.date, p.total, tt.name AS transaction_type, p.status 
          FROM pos p
          LEFT JOIN transaction_type tt ON p.transaction_type_id = tt.id
          WHERE p.pos_ref = '$pos_ref'";

$result = $mysqli->query($query);

if (!$result) {
    die('Error fetching transaction details: ' . $mysqli->error);
}

// Fetch the transaction details
$transaction = $result->fetch_assoc();

if (!$transaction) {
    die('Transaction not found');
}

// Close the connection
$mysqli->close();

// Pass the transaction data to transaction-viewModal.php
include 'transaction-viewModal.php';
?>
