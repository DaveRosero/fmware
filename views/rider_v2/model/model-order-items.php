<?php
require_once 'model/database/database.php';

$mysqli = database();

// Get order_ref from query string
$order_ref = $_GET['order_ref'];

// SQL query to fetch order details along with related items
$query = 'SELECT orders.id,
                 orders.order_ref,
                 orders.date,
                 order_items.product_id,
                 order_items.qty,
                 product.name AS product_name,
                 price_list.unit_price,
                 variant.name AS variant_name,
                 unit.name AS unit_name
          FROM orders
          LEFT JOIN order_items ON orders.order_ref = order_items.order_ref
          LEFT JOIN product ON order_items.product_id = product.id
          LEFT JOIN price_list ON product.id = price_list.product_id
          LEFT JOIN variant ON product.variant_id = variant.id
          LEFT JOIN unit ON product.unit_id = unit.id
          WHERE orders.order_ref = ?';

// Prepare and execute statement
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
