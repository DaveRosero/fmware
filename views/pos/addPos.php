<?php
    require_once 'model/database/database.php';

    $mysqli = database();

    $query = 'SELECT COUNT(*) FROM pos_cart WHERE product_id = ?';
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('i', $_POST['product_id']);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    if ($count > 0 ){
        exit;
    }

    $query = 'INSERT INTO pos_cart (product_id, qty, price) VALUES (?,?,?)';
    $stmt = $mysqli->prepare($query);
    $qty = 1;
    $stmt->bind_param('iii', $_POST['product_id'], $qty, $_POST['price']);
    $stmt->execute();
    $stmt->bind_result($id);
    $stmt->fetch();
    $stmt->close();

    include_once 'cart-body.php';
?>