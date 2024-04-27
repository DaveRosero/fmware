<?php
    require_once 'model/database/database.php';

    $mysqli = database();

    $query = 'DELETE FROM pos_cart';
    $stmt = $mysqli->prepare($query);
    $stmt->execute();
    $stmt->close();
?>