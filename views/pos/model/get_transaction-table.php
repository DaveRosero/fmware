<?php
require_once 'model/database/database.php'; // Adjust this to your database connection file

if (!isset($_GET['pos_ref'])) {
    die('Transaction ID not specified');
}

$mysqli = database();

// Retrieve the transaction details based on pos_ref
$pos_ref = $mysqli->real_escape_string($_GET['pos_ref']); // Sanitize input

$query = "SELECT 
product.name AS product_name,
unit.name AS unit_name,
variant.name AS variant_name,
price_list.unit_price AS product_price,
pos_items.qty AS product_qty

FROM 
pos_items 
INNER JOIN 
product on product.id = pos_items.product_id
INNER JOIN
unit on unit.id = product.unit_id
INNER JOIN
variant on variant.id = product.variant_id
INNER JOIN
price_list on price_list.product_id = product.id



where 
pos_items.pos_ref = '$pos_ref'";

$result = $mysqli->query($query);

if (!$result) {
    die('Error fetching transaction details: ' . $mysqli->error);
}



// Fetch the transaction details

$content = '';

while ($row = $result->fetch_assoc()) {

    $content .= '<tr>
    <td>
        <input class="form-check-input mt-0" type="checkbox" value="" aria-label="Checkbox for following text input">
    </td>
    <td>'. $row['product_name'] .'</td>
    <td>'. $row['unit_name'] .'</td>
    <td>'. $row['variant_name'] .'</td>
    <td>'. $row['product_price'] .'</td>
    <td>'. $row['product_qty'] .'</td>
    <td></td>
    </tr>';
}
echo $content;



// Close the connection
$mysqli->close();
