<?php
require_once 'model/database/database.php';

$mysqli = database();

// SQL query to fetch all order details along with related data
$query = 'SELECT orders.id,
                 orders.order_ref,
                 orders.firstname,
                 orders.lastname,
                 orders.date,
                 orders.gross,
                 orders.delivery_fee,
                 orders.vat,
                 orders.discount,
                 orders.paid,
                 orders.status,
                 transaction_type.name AS transaction_type_name,
                 payment_type.name AS payment_type_name
          FROM orders
          LEFT JOIN transaction_type ON orders.transaction_type_id = transaction_type.id
          LEFT JOIN payment_type ON orders.payment_type_id = payment_type.id
          ORDER BY orders.date DESC'; // You can adjust the sorting as needed

$result = $mysqli->query($query);

$orders = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $orders[] = $row;
    }
}

header('Content-Type: application/json');
echo json_encode($orders);
?>
