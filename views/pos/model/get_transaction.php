<?php
require_once 'model/database/database.php'; // Adjust this to your database connection file

if (!isset($_GET['pos_ref'])) {
    die('Transaction ID not specified');
}

$mysqli = database();

// Retrieve the transaction details based on pos_ref
$pos_ref = $mysqli->real_escape_string($_GET['pos_ref']); // Sanitize input

$query = "SELECT 
    p.pos_ref, 
    p.firstname, 
    p.lastname, 
    p.date, 
    p.subtotal, 
    p.total, 
    p.discount, 
    p.cash, 
    p.changes, 
    p.delivery_fee, 
    p.deliverer_name, 
    p.contact_no , 
    tt.name AS transaction_type, 
    pt.name AS payment_type,
    user.firstname AS username,
    p.status
    FROM pos p
    LEFT JOIN payment_type pt ON p.payment_type_id = pt.id
    LEFT JOIN transaction_type tt ON p.transaction_type_id = tt.id
    LEFT JOIN user user ON p.user_id = user.id
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


echo json_encode($transaction);

// Close the connection
$mysqli->close();
