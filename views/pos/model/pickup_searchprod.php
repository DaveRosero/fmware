<?php
require_once 'model/database/database.php';

if (!isset($_GET['pos_ref'])) {
    die('Transaction ID not specified');
}

$mysqli = database();
$pos_ref = $mysqli->real_escape_string($_GET['pos_ref']);

$query = "SELECT 
    order.pos_ref, 
    order.firstname, 
    order.lastname, 
    order.date, 
    order.subtotal, 
    order.total, 
    order.discount, 
    order.cash, 
    order.changes, 
    order.delivery_fee, 
    order.deliverer_name, 
    order.contact_no , 
    order.address,
    transaction_type.name AS transaction_type, 
    payment_type.name AS payment_type,
    user.firstname AS username,
    order.status
    FROM order
    LEFT JOIN payment_type  ON order.payment_type_id = payment_type.id
    LEFT JOIN transaction_type  ON order.transaction_type_id = transaction_type.id
    LEFT JOIN user user ON order.user_id = user.id
    WHERE order.order_ref = '$order_ref'";

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
