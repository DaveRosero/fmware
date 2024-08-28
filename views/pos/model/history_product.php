<?php
require_once 'model/database/database.php';

if (!isset($_GET['pos_ref'])) {
    die('Transaction ID not specified');
}

$mysqli = database();


$pos_ref = $mysqli->real_escape_string($_GET['pos_ref']);

$query = "SELECT  product.name AS product_name,
                  product.unit_value AS product_uvalue,
                  unit.name AS unit_name,
                  variant.name AS variant_name,
                  pos_items.total AS product_subtotal,
                  pos_items.qty AS product_qty

          FROM pos_items 
          INNER JOIN product on product.id = pos_items.product_id
          INNER JOIN unit on unit.id = product.unit_id
          INNER JOIN variant on variant.id = product.variant_id
          INNER JOIN price_list on price_list.product_id = product.id
          where pos_items.pos_ref = '$pos_ref'";

$result = $mysqli->query($query);

if (!$result) {
    die('Error fetching transaction details: ' . $mysqli->error);
}



// Fetch the transaction details

$content = '';

while ($row = $result->fetch_assoc()) {
    $price = ($row['product_qty'] != 0) ? $row['product_subtotal'] / $row['product_qty'] : 0;
    $content .= '<tr>
    <td cclass="text-center">' . $row['product_name'] . '</td>
    <td class="text-center">' . $row['product_uvalue'] . ' ' . $row['unit_name'] . '</td>
    <td class="text-center">' . $row['variant_name'] . '</td>
    <td class="text-center">₱' . number_format($price, 2)  . '</td>
    <td class="text-center">' . $row['product_qty'] . '</td>
    <td class="text-center">₱' . number_format($row['product_subtotal'], 2) . '</td>
    </tr>';
}
echo $content;

// Close the connection
$mysqli->close();