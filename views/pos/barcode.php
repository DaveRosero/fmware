<?php
require_once 'model/database/database.php';

$mysqli = database();

$query = 'SELECT product.id, price_list.unit_price FROM product 
    INNER JOIN price_list ON price_list.product_id = product.id
    WHERE barcode = ?';
$stmt = $mysqli->prepare($query);
$stmt->bind_param('s', $_POST['barcode']);
$stmt->execute();
$stmt->bind_result($id, $price);
// Fetch the result
if ($stmt->fetch()) {
    $json = array(
        'id' => $id,
        'price' => $price
    );
} else {
    // Handle the case where no product is found
    $json = array(
        'error' => 'Product not found'
    );
}

$stmt->close();
$mysqli->close();

echo json_encode($json);
?>