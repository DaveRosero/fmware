<?php
require_once 'model/database/database.php';

$mysqli = database();

if (isset($_GET['pos_ref'])) {
    $pos_ref = $_GET['pos_ref'];

    $query = 'SELECT pos_items.qty,
                     product.name,
                     product.unit_value,
                     unit.name,
                     variant.name
              FROM pos_items
              INNER JOIN product ON pos_items.product_id = product.id
              INNER JOIN unit ON unit.id = product.unit_id
              INNER JOIN variant ON variant.id = product.variant_id
              WHERE pos_ref = ?';
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('s', $pos_ref);
    $stmt->execute();
    $stmt->bind_result($qty, $name, $unit_value, $unit, $variant);
    while ($stmt->fetch()) {
        echo '<tr>
                <td class="align-middle">' . $name . '</td>
                <td class="align-middle">' . $unit_value . ' ' . strtoupper($unit) . '</td>
                <td class="align-middle">' . $variant . '</td>
                <td class="align-middle">' . $qty . '</td>
              </tr>';
    }
    $stmt->close();
} else {
    echo '<tr><td colspan="4" class="align-middle">No products found for this transaction.</td></tr>';
}
?>
