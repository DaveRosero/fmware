<?php
require_once 'model/database/database.php';

// Get order_ref from URL path
$order_ref = $_GET['order_ref'] ?? null; // Use null coalescing operator to handle missing parameter

if (!$order_ref) {
    http_response_code(400);
    echo json_encode(['error' => 'No order_ref provided']);
    exit;
}

// Connect to the database
$mysqli = database();

// SQL query to fetch order details and associated items
$query = '
  SELECT orders.id,
         orders.order_ref,
         orders.firstname,
         orders.lastname,
         orders.phone,
         orders.date,
         orders.gross,
         orders.delivery_fee,
         orders.vat,
         orders.discount,
         orders.paid,
         orders.status,
         orders.code,
         orders.rider_id,
         user.firstname AS user_firstname,
         user.lastname AS user_lastname,
         user.phone AS user_phone,
         address.house_no,
         address.street,
         address.brgy,
         address.municipality,
         address.description AS address_description,
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
  LEFT JOIN user ON orders.user_id = user.id
  LEFT JOIN address ON orders.address_id = address.id
  WHERE orders.order_ref = ?';

// Prepare and execute the statement
$stmt = $mysqli->prepare($query);
$stmt->bind_param('s', $order_ref);
$stmt->execute();
$result = $stmt->get_result();

// Initialize arrays to hold order and item data
$orderData = null;
$orderItems = [];

// Fetch the results
while ($row = $result->fetch_assoc()) {
    if (!$orderData) {
        // Initialize order data
        $orderData = [
            'order_ref' => $row['order_ref'],
            'date' => $row['date'],
            'gross' => $row['gross'],
            'delivery_fee' => $row['delivery_fee'],
            'vat' => $row['vat'],
            'discount' => $row['discount'],
            'paid' => $row['paid'],
            'status' => $row['status'],
            'user_name' => $row['user_firstname'] . ' ' . $row['user_lastname'],
            'user_phone' => $row['user_phone'],
            'address' => [
                'house_no' => $row['house_no'],
                'street' => $row['street'],
                'brgy' => $row['brgy'],
                'municipality' => $row['municipality'],
                'description' => $row['address_description']
            ],
            'items' => []
        ];
    }
    
    // Append item data
    if ($row['product_name']) {
        $orderItems[] = [
            'product_name' => $row['product_name'] ?? "N/A",
            'variant_name' => $row['variant_name'] ?? "N/A",
            'unit_name' => $row['unit_name'] ?? "N/A",
            'qty' => $row['qty'] ?? 0,
            'unit_price' => $row['unit_price'] ?? 0,
            'total_price' => ($row['qty'] ?? 0) * ($row['unit_price'] ?? 0)
        ];
    }
}

// Combine order and items
if ($orderData) {
    $orderData['items'] = $orderItems;
}

// Close MySQL connection
$mysqli->close();

// Send JSON response
header('Content-Type: application/json');
echo json_encode($orderData);
?>
