<?php
require_once 'model/database/database.php'; // Adjust the path as per your project structure

// Assuming database() function connects to your MySQL database
$mysqli = database();

// SQL query to fetch all POS details along with related data
$query = 'SELECT p.pos_ref,
                 p.firstname,
                 p.lastname,
                 p.date,
                 p.subtotal,
                 p.total,
                 p.discount,
                 p.cash,
                 p.status,
                 p.changes,
                 p.paid,
                 p.delivery_fee,
                 p.rider_id,
                 tt.name AS transaction_type_name,
                 pt.name AS payment_type_name
          FROM pos p
          LEFT JOIN transaction_type tt ON p.transaction_type_id = tt.id
          LEFT JOIN payment_type pt ON p.payment_type_id = pt.id
          ORDER BY p.date DESC'; // Adjust the sorting as needed

$result = $mysqli->query($query);

$pos = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $pos[] = $row;
    }
}

// Close MySQL connection
$mysqli->close();

// Send JSON response
header('Content-Type: application/json');
echo json_encode($pos);
?>
