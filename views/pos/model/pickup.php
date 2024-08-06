<?php
require_once 'model/database/database.php';

$mysqli = database();


$query = 'SELECT orders.order_ref,
                 orders.date,
                 orders.gross,
                 transaction_type.name,
                 orders.status
            FROM orders
            LEFT JOIN transaction_type ON orders.transaction_type_id = transaction_type.id';
$result = $mysqli->query($query);

$pickups = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $pickups[] = $row;
    }
}
?>