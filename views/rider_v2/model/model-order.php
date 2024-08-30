<?php
require_once 'model/database/database.php'; // Adjust the path as needed

// Connect to the database
$mysqli = database();

// SQL query to fetch online orders
$queryOnlineOrders = 'SELECT o.id,
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
                     ORDER BY o.date DESC';

// Fetch online orders
$resultOnlineOrders = $mysqli->query($queryOnlineOrders);

$onlineOrders = [];
if ($resultOnlineOrders && $resultOnlineOrders->num_rows > 0) {
    while ($row = $resultOnlineOrders->fetch_assoc()) {
        $onlineOrders[] = $row;
    }
}

// SQL query to fetch POS orders
$queryPosOrders = 'SELECT p.id,
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
                        p.rider_id,
                        p.contact_no,
                        p.transaction_type_id,
                        p.payment_type_id,
                        p.user_id,
                        p.address,
                        p.status,
                        p.paid,
                        tt.name AS transaction_type_name,
                        pt.name AS payment_type_name
                 FROM pos p
                 LEFT JOIN transaction_type tt ON p.transaction_type_id = tt.id
                 LEFT JOIN payment_type pt ON p.payment_type_id = pt.id
                 ORDER BY p.date DESC';

// Fetch POS orders
$resultPosOrders = $mysqli->query($queryPosOrders);

$posOrders = [];
if ($resultPosOrders && $resultPosOrders->num_rows > 0) {
    while ($row = $resultPosOrders->fetch_assoc()) {
        $posOrders[] = $row;
    }
}

// Close MySQL connection
$mysqli->close();

// Combine both results
$data = [
    'onlineOrders' => $onlineOrders,
    'posOrders' => $posOrders
];

// Send JSON response
header('Content-Type: application/json');
echo json_encode($data);
?>