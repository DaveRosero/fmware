<?php
require_once 'model/database/database.php';

$mysqli = database();

$id = $_POST['id'];

$query = 'DELETE FROM pos_cart WHERE product_id = ?';
$stmt = $mysqli->prepare($query);
$stmt->bind_param('i', $id);
$stmt->execute();
$stmt->close();

include_once 'cart-body.php';
