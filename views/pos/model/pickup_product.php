<?php
require_once 'model/database/database.php';

if (!isset($_GET['order_ref'])) {
    die('Transaction ID not specified');
}

$mysqli = database();
$order_ref = $mysqli->real_escape_string($_GET['order_ref']);

$query = "SELECT  product.name AS product_name,
                  product.unit_value AS product_uvalue,
                  unit.name AS unit_name,
                  variant.name AS variant_name,
                  price_list.unit_price AS product_price,
                  order_items.qty AS product_qty

          FROM order_items 
          INNER JOIN product on product.id = order_items.product_id
          INNER JOIN unit on unit.id = product.unit_id
          INNER JOIN variant on variant.id = product.variant_id
          INNER JOIN price_list on price_list.product_id = product.id
          where order_items.order_ref = '$order_ref'";

$result = $mysqli->query($query);

if (!$result) {
    die('Error fetching transaction details: ' . $mysqli->error);
}

$content = '';

while ($row = $result->fetch_assoc()) {
    $subtotal = $row['product_price'] * $row['product_qty'];
    $content .= '<tr>
    <td cclass="text-center">' . $row['product_name'] . '</td>
    <td class="text-center">' . $row['product_uvalue'] . ' ' . $row['unit_name'] . '</td>
    <td class="text-center">' . $row['variant_name'] . '</td>
    <td class="text-center">₱' . number_format($row['product_price'], 2) . '</td>
    <td class="text-center">' . $row['product_qty'] . '</td>
    <td class="text-center">₱' . number_format($subtotal, 2) . '</td>
    </tr>';
}
echo $content;
$mysqli->close();