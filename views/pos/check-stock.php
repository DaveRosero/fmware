<?php
require_once 'model/database/database.php';

$mysqli = database();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = $_POST['id'];
    
    $query = 'SELECT qty FROM stock WHERE product_id = ?';
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('i', $product_id);
    $stmt->execute();
    $stmt->bind_result($stock);
    $stmt->fetch();
    $stmt->close();
    
    $response = ['stock' => $stock];
    echo json_encode($response);
}
?>
