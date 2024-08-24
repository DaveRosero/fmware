<?php
require_once 'model/database/database.php';

$mysqli = database();


$query1 = 'SELECT pos.pos_ref,
                 pos.date,
                 pos.total,
                 transaction_type.name,
                 pos.status
            FROM pos
            LEFT JOIN transaction_type ON pos.transaction_type_id = transaction_type.id';
$result1 = $mysqli->query($query1);

$transactions1 = [];
if ($result1->num_rows > 0) {
    while ($row = $result1->fetch_assoc()) {
        $transactions1[] = $row;
    }
}

$query2 = 'SELECT orders.order_ref,
                 orders.date,
                 orders.gross,
                 transaction_type.name,
                 orders.status
            FROM orders
            LEFT JOIN transaction_type ON orders.transaction_type_id = transaction_type.id';
$result2 = $mysqli->query($query2);

$transactions2 = [];
if ($result2->num_rows > 0) {
    while ($row = $result2->fetch_assoc()) {
        $transactions2[] = $row;
    }
}

// Combine yung dalawang database para mafetch
$transactions = array_merge($transactions1, $transactions2);

header('Content-Type: application/json');
echo json_encode($transactions)
?>