<?php
require_once 'model/database/database.php';

$mysqli = database();

$query = "SELECT COUNT(*) as count FROM orders WHERE status = 'pending'";
$result = $mysqli->query($query);
$row = $result->fetch_assoc();

echo json_encode(['count' => $row['count']]);
?>