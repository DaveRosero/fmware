<?php
require_once 'model/database/database.php';

if (!isset($_GET['pos_ref'])) {
    die('Transaction ID not specified');
}

$mysqli = database();

$pos_ref = $mysqli->real_escape_string($_GET['pos_ref']);

// Query for pos_items
$query_pos_items = "SELECT 
                      product.name AS product_name,
                      product.unit_value AS product_uvalue,
                      unit.name AS unit_name,
                      variant.name AS variant_name,
                      price_list.unit_price AS product_price,
                      pos_items.qty AS product_qty,
                      product.id AS product_id
                    FROM pos_items 
                    INNER JOIN product ON product.id = pos_items.product_id
                    INNER JOIN unit ON unit.id = product.unit_id
                    INNER JOIN variant ON variant.id = product.variant_id
                    INNER JOIN price_list ON price_list.product_id = product.id
                    WHERE pos_items.pos_ref = '$pos_ref'";

// Query for order_items
$query_order_items = "SELECT 
                        product.name AS product_name,
                        product.unit_value AS product_uvalue,
                        unit.name AS unit_name,
                        variant.name AS variant_name,
                        price_list.unit_price AS product_price,
                        order_items.qty AS product_qty,
                        product.id AS product_id
                      FROM order_items 
                      INNER JOIN product ON product.id = order_items.product_id
                      INNER JOIN unit ON unit.id = product.unit_id
                      INNER JOIN variant ON variant.id = product.variant_id
                      INNER JOIN price_list ON price_list.product_id = product.id
                      WHERE order_items.order_ref = '$pos_ref'";

// Execute both queries
$result_pos_items = $mysqli->query($query_pos_items);
$result_order_items = $mysqli->query($query_order_items);

if (!$result_pos_items || !$result_order_items) {
    die('Error fetching transaction details: ' . $mysqli->error);
}

$content = '';

// Process pos_items results
while ($row = $result_pos_items->fetch_assoc()) {
    $subtotal = $row['product_price'] * $row['product_qty'];
    $disabled = ($row['product_qty'] == 0) ? 'disabled' : '';
    $content .= '<tr>
    <td>
        <input class="form-check-input selectedItem" type="checkbox" data-price="' . $subtotal . '" data-product-id="' . $row['product_id'] . '" data-product-qty="' . $row['product_qty'] . '" ' . $disabled . '>
    </td>
    <td class="text-center">' . $row['product_name'] . '</td>
    <td class="text-center">' . $row['product_uvalue'] . ' ' . $row['unit_name'] . '</td>
    <td class="text-center">' . $row['variant_name'] . '</td>
    <td class="text-center">₱' . number_format($row['product_price'], 2) . '</td>
    <td class="text-center">' . $row['product_qty'] . '</td>
    <td class="text-center">₱' . number_format($subtotal, 2) . '</td>
    <td class="text-center">
        <input class="form-control refund-quantity" type="number" min="0" max="' . $row['product_qty'] . '" value="0" disabled>
    </td>
    <td>
      <select class="form-select item-condition" aria-label="Default select example" disabled>
        <option value="">Select item condition</option>
        <option value="Good">Wrong Item</option>
        <option value="Broken">Defective</option>
      </select>
    </td>
    </tr>';
}

// Process order_items results
while ($row = $result_order_items->fetch_assoc()) {
    $subtotal = $row['product_price'] * $row['product_qty'];
    $disabled = ($row['product_qty'] == 0) ? 'disabled' : '';
    $content .= '<tr>
    <td>
        <input class="form-check-input selectedItem" type="checkbox" data-price="' . $subtotal . '" data-product-id="' . $row['product_id'] . '" data-product-qty="' . $row['product_qty'] . '" ' . $disabled . '>
    </td>
    <td class="text-center">' . $row['product_name'] . '</td>
    <td class="text-center">' . $row['product_uvalue'] . ' ' . $row['unit_name'] . '</td>
    <td class="text-center">' . $row['variant_name'] . '</td>
    <td class="text-center">₱' . number_format($row['product_price'], 2) . '</td>
    <td class="text-center">' . $row['product_qty'] . '</td>
    <td class="text-center">₱' . number_format($subtotal, 2) . '</td>
    <td class="text-center">
        <input class="form-control refund-quantity" type="number" min="0" max="' . $row['product_qty'] . '" value="0" disabled>
    </td>
    <td>
      <select class="form-select item-condition" aria-label="Default select example" disabled>
        <option value="">Select item condition</option>
        <option value="Good">Wrong Item</option>
        <option value="Broken">Defective</option>
      </select>
    </td>
    </tr>';
}

// Output the content
echo $content;

// Close the connection
$mysqli->close();
