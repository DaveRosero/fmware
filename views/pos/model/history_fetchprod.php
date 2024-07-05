<?php
require_once 'model/database/database.php';

$mysqli = database();

if (!isset($_GET['pos_ref'])) {
    die('Transaction ID not specified');
}

$mysqli = database();

$pos_ref = $mysqli->real_escape_string($_GET['pos_ref']);

$query = "SELECT 
    pos.pos_ref, 
    pos.firstname, 
    pos.lastname, 
    pos.date, 
    pos.subtotal, 
    pos.total, 
    pos.discount, 
    pos.cash, 
    pos.changes, 
    pos.delivery_fee, 
    pos.deliverer_name, 
    pos.contact_no , 
    transaction_type.name, 
    payment_type.name,
    user.firstname,
    pos.status
    FROM pos
    LEFT JOIN payment_type ON pos.payment_type_id = payment.id
    LEFT JOIN transaction_type ON pos.transaction_type_id = transaction_type.id
    LEFT JOIN user ON pos.user_id = user.id
    WHERE pos.pos_ref = '$pos_ref'";

$result = $mysqli->query($query);

if (!$result) {
    die('Error fetching transaction details: ' . $mysqli->error);
}


$history = $result->fetch_assoc();

if (!$history) {
    die('Transaction not found');
}


echo json_encode($history);

$mysqli->close();
