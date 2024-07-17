<?php
require_once 'model/database/database.php';

$mysqli = database();

$query = 'SELECT price_list.unit_price,
                  product.image,
                  product.name,
                  product.unit_value,
                  stock.qty,
                  product.id,
                  unit.name,
                  variant.name
                  FROM price_list
                  INNER JOIN stock ON price_list.product_id = stock.product_id
                  INNER JOIN product ON price_list.product_id = product.id
                  INNER JOIN variant ON variant.id = product.variant_id
                  INNER JOIN unit ON unit.id = product.unit_id
                  WHERE product.name LIKE CONCAT("%", ?, "%") OR product.barcode = ?
                  ORDER BY product.name ASC';

$stmt = $mysqli->prepare($query);
$stmt->bind_param('ss', $search, $search); // Bind search parameter twice for both placeholders
$stmt->execute();
$stmt->bind_result($unit_price, $image, $name, $unit_value, $qty, $id, $unit, $variant);

$output = '';

while ($stmt->fetch()) {
  $disabled = ($qty == 0) ? 'disabled' : '';
  $output .= '
    <div class="item-card">
      <div class="card">
        <img src="asset/images/products/' . $image . '" class="card-img-top" alt="' . $name . '">
        <div class="card-body">
          <p class="card-title h6">' . $name . '(' . $variant . ')</p>
          <p class="card-text">Unit: ' . $unit_value . ' ' . strtoupper($unit) . '</p>
          <p class="card-text">Stock: ' . $qty . '</p>
          <div class="d-grid">
            <button class="btn btn-primary cart-button" 
                    data-product-id="' . $id . '"
                    data-product-price="' . $unit_price . '"
                    ' . $disabled . '
            >
              <i class="fas fa-cart-plus"></i> Add to Cart
            </button>
          </div>
        </div>
        <div class="card-footer">
          <h5 class="card-title p-2 w-50 bg-success text-white"> ₱ ' . number_format($unit_price) . ' </h5>
        </div>
      </div>
    </div>';
}
$stmt->close();

echo $output;
?>