<?php
require_once 'model/database/database.php';

$mysqli = database();

// Ensure the order_ref parameter is provided
$order_ref = $_GET['order_ref'] ?? ''; // Get the order reference from the request

if (empty($order_ref)) {
    http_response_code(400); // Bad Request
    echo json_encode(['error' => 'Order reference is required']);
    exit();
}

// SQL query to fetch items of the specific order
$query = 'SELECT order_items.id,
                 order_items.order_ref,
                 product.name AS product_name,
                 product.variant_id,
                 variant.name AS variant_name,
                 order_items.qty,
                 price_list.unit_price AS price,
                 unit.name AS unit_name
          FROM order_items
          LEFT JOIN product ON order_items.product_id = product.id
          LEFT JOIN price_list ON order_items.product_id = price_list.product_id
          LEFT JOIN variant ON product.variant_id = variant.id
          LEFT JOIN unit ON product.unit_id = unit.id
          WHERE order_items.order_ref = ?
          ORDER BY product.name'; // Order by product name or any other field you prefer

$stmt = $mysqli->prepare($query);
if (!$stmt) {
    http_response_code(500); // Internal Server Error
    echo json_encode(['error' => 'Database query error']);
    exit();
}

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
