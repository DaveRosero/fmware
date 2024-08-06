<?php
require_once 'model/database/database.php';

$mysqli = database();

$order_ref = $_GET['order_ref'];

// Sanitize input
$order_ref = $mysqli->real_escape_string($order_ref);

// SQL query to fetch order items
$query = 'SELECT order_items.product_id, 
                 product.name AS product_name, 
                 order_items.qty, 
                 price_list.unit_price
          FROM order_items
          JOIN product ON order_items.product_id = product.id
          JOIN price_list ON product.id = price_list.product_id
          WHERE order_items.order_ref = ?';

$stmt = $mysqli->prepare($query);
$stmt->bind_param('s', $order_ref);
$stmt->execute();
$result = $stmt->get_result();

$order_items = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $order_items[] = $row;
    }
}

header('Content-Type: application/json');
echo json_encode($order_items);
?>
