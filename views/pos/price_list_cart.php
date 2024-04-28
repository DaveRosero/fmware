<?php
require_once 'model/database/database.php';

$mysqli = database();

$id = $_POST['id'];
$price = $_POST['price'];
$qty = 1;
    
$query = 'SELECT COUNT(*) FROM pos_cart WHERE product_id = ?';
$stmt = $mysqli->prepare($query);
$stmt->bind_param('i', $id);
$stmt->execute();
$stmt->bind_result($count);
$stmt->fetch();
$stmt->close();
    
if ($count == 0) {
    $query = 'INSERT INTO pos_cart
            (product_id, qty, price)
         VALUES (?,?,?)';
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('iii', $id, $qty, $price);
    $stmt->execute();
    $stmt->close();
}

include_once 'cart-body.php';
?>