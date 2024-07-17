<?php
require_once 'model/database/database.php';

$mysqli = database();


$query = 'SELECT pos.pos_ref,
                 pos.date,
                 pos.total,
                 transaction_type.name,
                 pos.status
            FROM pos
            LEFT JOIN transaction_type ON pos.transaction_type_id = transaction_type.id';
$result = $mysqli->query($query);

$transactions = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $transactions[] = $row;
    }
}

header('Content-Type: application/json');
echo json_encode($transactions)
?>