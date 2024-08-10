<?php
require_once 'model/database/database.php';

if (!isset($_GET['order_ref'])) {
    die('Transaction ID not specified');
}

$mysqli = database();
$order_ref = $mysqli->real_escape_string($_GET['order_ref']);

$query = "SELECT 
    orders.order_ref, 
    orders.firstname, 
    orders.lastname, 
    orders.date, 
    orders.gross,
    orders.phone,
    orders.cash,
    orders.changes,  
    transaction_type.name AS transaction_type, 
    payment_type.name AS payment_type,
    user.firstname AS username,
    orders.status
    FROM orders
    LEFT JOIN payment_type  ON orders.payment_type_id = payment_type.id
    LEFT JOIN transaction_type  ON orders.transaction_type_id = transaction_type.id
    LEFT JOIN user user ON orders.user_id = user.id
    WHERE orders.order_ref = '$order_ref'";

$result = $mysqli->query($query);

if (!$result) {
    die('Error fetching transaction details: ' . $mysqli->error);
}


$pickup = $result->fetch_assoc();

if (!$pickup) {
    die('Transaction not found');
}


echo json_encode($pickup);


$mysqli->close();
