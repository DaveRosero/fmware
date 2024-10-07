<?php
require_once 'model/database/database.php';

$mysqli = database();


$query = 'SELECT orders.order_ref,
                 orders.date,
                 orders.gross,
                 transaction_type.name AS transaction_type_name,
                 payment_type.name AS payment_type_name,
                 orders.status
            FROM orders
            LEFT JOIN transaction_type ON orders.transaction_type_id = transaction_type.id
            LEFT JOIN payment_type ON orders.payment_type_id = payment_type.id
            WHERE payment_type.name = "pickup"';

$result = $mysqli->query($query);

$pickups = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $pickups[] = $row;
    }
}
?>