<?php
require_once 'model/database/database.php'; // Adjust the path as per your project structure

// Assuming database() function connects to your MySQL database
$mysqli = database();

// SQL query to fetch all order details along with related data
$query = 'SELECT o.id,
                 o.order_ref,
                 o.firstname,
                 o.lastname,
                 o.phone,
                 o.date,
                 o.gross,
                 o.delivery_fee,
                 o.vat,
                 o.discount,
                 o.paid,
                 o.status,
                 o.code,
                 o.rider_id,
                 tt.name AS transaction_type_name,
                 pt.name AS payment_type_name
          FROM orders o
          LEFT JOIN transaction_type tt ON o.transaction_type_id = tt.id
          LEFT JOIN payment_type pt ON o.payment_type_id = pt.id
          ORDER BY o.date DESC'; // Adjust the sorting as needed

$result = $mysqli->query($query);

$orders = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $orders[] = $row;
    }
}

// Close MySQL connection
$mysqli->close();

// Send JSON response
header('Content-Type: application/json');
echo json_encode($orders);
?>
