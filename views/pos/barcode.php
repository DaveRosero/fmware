<?php
    require_once 'model/database/database.php';

    $mysqli = database();

    $query = 'SELECT product.id, price_list.unit_price FROM product 
    INNER JOIN price_list ON price_list.product_id = product.id
    WHERE barcode = ?';
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('i', $_POST['barcode']);
    $stmt->execute();
    $stmt->bind_result($id, $price);
    $stmt->fetch();
    $stmt->close();

    $json = array(
    'id' => $id,
    'price' => $price
    );

    echo json_encode($json);
?>