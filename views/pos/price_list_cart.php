<?php
require_once 'model/database/database.php';
include_once 'session.php';


$mysqli = database();

$id = $_POST['id'];
$user_id = $_SESSION['user_id'];
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
            (user_id, product_id, qty, price)
         VALUES (?,?,?,?)';
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('iiii', $user_id, $id, $qty, $price);
    $stmt->execute();
    $stmt->close();
}

include_once 'cart-body.php';
?>