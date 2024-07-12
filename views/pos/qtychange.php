<?php
require_once 'model/database/database.php';

$mysqli = database();

$qty = isset($_POST['qty']) ? intval($_POST['qty']) : 0;
$id = isset($_POST['id']) ? intval($_POST['id']) : 0;

if ($qty > 0 && $id > 0) {
    $query = 'UPDATE pos_cart SET qty = ? WHERE product_id = ?';
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('ii', $qty, $id);
    $stmt->execute();
    $stmt->close();


    include_once 'cart-body.php';
}
?>