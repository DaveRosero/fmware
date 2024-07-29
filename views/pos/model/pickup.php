<?php
require_once 'model/database/database.php';

$mysqli = database();


$query = 'SELECT order.pos_ref,
                 order.date,
                 order.total,
                 transaction_type.name,
                 order.status
            FROM order
            LEFT JOIN transaction_type ON pos.transaction_type_id = transaction_type.id';
$result = $mysqli->query($query);

$pickups = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $pickups[] = $row;
    }
}
?>