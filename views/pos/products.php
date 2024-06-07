<?php
require_once 'model/database/database.php';

$mysqli = database();

$query = 'SELECT price_list.unit_price,
                    product.image,
                    product.name,
                    stock.qty,
                    product.id
            FROM price_list
            INNER JOIN stock ON price_list.product_id = stock.product_id
            INNER JOIN product ON price_list.product_id = product.id';
$stmt = $mysqli->prepare($query);
$stmt->execute();
$stmt->bind_result($unit_price, $image, $name, $qty, $id);
while ($stmt->fetch()) {
    echo '<tr>
              <td class="align-middle"><img src="asset/images/products/' . $image . '" alt="" srcset="" style="width: 90px;"></td>
              <td class="align-middle">' . $name . '</td>
              <td class="align-middle">' . $qty . '</td>
              <td class="align-middle">₱' . number_format($unit_price) . '</td>
              <td class="align-middle">
                  <button class="btn btn-primary cart-button" 
                    data-product-id="' . $id . '"
                    data-product-price="' . $unit_price . '"
                  >
                  <i class="fas fa-cart-plus"></i>
                  </button>
              </td>
          </tr>';
}
