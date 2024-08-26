<?php
require_once 'model/database/database.php'; // Adjust this to your database connection file

if (!isset($_GET['pos_ref'])) {
    die('Transaction ID not specified');
}

$mysqli = database();

// Sanitize input
$pos_ref = $mysqli->real_escape_string($_GET['pos_ref']);

// Check if the reference is in the pos table
$queryPos = "SELECT 
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
    p.contact_no, 
    p.address,
    tt.name AS transaction_type, 
    pt.name AS payment_type,
    user.firstname AS username,
    p.status,
    'pos' AS transaction_type_source
    FROM pos p
    LEFT JOIN payment_type pt ON p.payment_type_id = pt.id
    LEFT JOIN transaction_type tt ON p.transaction_type_id = tt.id
    LEFT JOIN user user ON p.user_id = user.id
    WHERE p.pos_ref = '$pos_ref'";

$resultPos = $mysqli->query($queryPos);

if ($resultPos && $resultPos->num_rows > 0) {
    $transaction = $resultPos->fetch_assoc();
} else {
    // If not found in pos table, check the orders table
    $queryOrder = "SELECT 
        o.order_ref AS pos_ref,
        o.firstname, 
        o.lastname, 
        o.date, 
        o.gross AS total,
        o.discount, 
        o.cash, 
        o.changes, 
        NULL AS delivery_fee, 
        NULL AS deliverer_name, 
        o.phone, 
        a.house_no,
        a.street,
        a.brgy,
        a.municipality,
        tt.name AS transaction_type, 
        pt.name AS payment_type,
        user.firstname AS username,
        o.status,
        'order' AS transaction_type_source
        FROM orders o
        LEFT JOIN address a ON o.address_id = a.id
        LEFT JOIN payment_type pt ON o.payment_type_id = pt.id
        LEFT JOIN transaction_type tt ON o.transaction_type_id = tt.id
        LEFT JOIN user user ON o.user_id = user.id
        WHERE o.order_ref = '$pos_ref'";

    $resultOrder = $mysqli->query($queryOrder);

    if ($resultOrder && $resultOrder->num_rows > 0) {
        $transaction = $resultOrder->fetch_assoc();
    } else {
        die('Transaction not found');
    }
}

echo json_encode($transaction);

// Close the connection
$mysqli->close();
