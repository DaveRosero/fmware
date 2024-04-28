<?php
    require_once 'model/database/database.php';

    $mysqli = database();

    $id = $_POST['id'];
    $qty = $_POST['qty'];

    if ($qty <= 1) {
        exit;
    }

    $newQty = $qty - 1;
    $query = 'UPDATE pos_cart SET qty = ? WHERE product_id = ?';
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('ii', $newQty, $id);
    $stmt->execute();
    $stmt->close();

    include_once 'cart-body.php';
?>