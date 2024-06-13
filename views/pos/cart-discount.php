<?php
require_once 'model/database/database.php';

$mysqli = database();

$id = $_POST['id'];
$discount = $_POST['discount'];

$query = 'UPDATE pos_cart SET discount = ? WHERE product_id =?';
$stmt = $mysqli->prepare($query);
$stmt->bind_param('ii', $discount, $id);
$stmt->execute();
$stmt->close();
?>
