<?php
require_once 'model/database/database.php'; // Adjust this to your database connection file
$mysqli = database();

// Query to fetch all transactions
$query = "SELECT p.pos_ref, p.date, p.total, tt.name AS transaction_type, p.status 
          FROM pos p
          LEFT JOIN transaction_type tt ON p.transaction_type_id = tt.id";
$result = $mysqli->query($query);

// Fetch data and store in an array
$transactions = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $transactions[] = $row;
    }
}
?>