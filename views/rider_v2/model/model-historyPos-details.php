<?php
require_once 'model/database/database.php';

// Get pos_ref from query string
$pos_ref = $_GET['pos_ref'];

// Connect to the database
$mysqli = database();

// SQL query to fetch POS details
$query = '
  SELECT pos.pos_ref,
         pos.date,
         pos.subtotal,
         pos.total,
         pos.discount,
         pos.cash,
         pos.changes,
         pos.delivery_fee,
         pos.rider_id,
         pos.contact_no,
         pos.firstname,  // Fetch from pos table
         pos.lastname,   // Fetch from pos table
         pos.address,
         pos.status,
         pos.paid,
         transaction_type.name AS transaction_type_name,
         payment_type.name AS payment_type_name
  FROM pos
  LEFT JOIN transaction_type ON pos.transaction_type_id = transaction_type.id
  LEFT JOIN payment_type ON pos.payment_type_id = payment_type.id
  WHERE pos.pos_ref = ?';

// Prepare and execute the statement
$stmt = $mysqli->prepare($query);
$stmt->bind_param('s', $pos_ref);
$stmt->execute();
$result = $stmt->get_result();

// Fetch the POS details
$posData = null;
if ($row = $result->fetch_assoc()) {
    $posData = [
        'pos_ref' => $row['pos_ref'],
        'date' => $row['date'],
        'subtotal' => $row['subtotal'],
        'total' => $row['total'],
        'discount' => $row['discount'],
        'cash' => $row['cash'],
        'changes' => $row['changes'],
        'delivery_fee' => $row['delivery_fee'],
        'rider_id' => $row['rider_id'],
        'contact_no' => $row['contact_no'],  // From pos table
        'user_name' => trim($row['firstname'] . ' ' . $row['lastname']),  // Combine names
        'transaction_type_name' => $row['transaction_type_name'],
        'payment_type_name' => $row['payment_type_name'],
        'address' => $row['address'],
        'status' => $row['status'],
        'paid' => $row['paid'],
        'items' => []  // Initialize items array
    ];
}

// Fetch POS items
$itemQuery = '
  SELECT pos_items.product_id,
         product.name AS product_name,
         pos_items.qty,
         pos_items.total AS total_price,
         price_list.unit_price,
         variant.name AS variant_name,
         unit.name AS unit_name
  FROM pos_items
  LEFT JOIN product ON pos_items.product_id = product.id
  LEFT JOIN price_list ON product.id = price_list.product_id
  LEFT JOIN variant ON product.variant_id = variant.id
  LEFT JOIN unit ON product.unit_id = unit.id
  WHERE pos_items.pos_ref = ?';

// Prepare and execute the statement for items
$itemStmt = $mysqli->prepare($itemQuery);
$itemStmt->bind_param('s', $pos_ref);
$itemStmt->execute();
$itemResult = $itemStmt->get_result();

// Fetch items
while ($itemRow = $itemResult->fetch_assoc()) {
    $posData['items'][] = [
        'product_name' => $itemRow['product_name'],
        'qty' => $itemRow['qty'],
        'total_price' => $itemRow['total_price'],
        'unit_price' => $itemRow['unit_price'],
        'variant_name' => $itemRow['variant_name'],
        'unit_name' => $itemRow['unit_name']
    ];
}

// Close MySQL connections
$stmt->close();
$itemStmt->close();
$mysqli->close();

// Send JSON response
header('Content-Type: application/json');
echo json_encode($posData);
?>
