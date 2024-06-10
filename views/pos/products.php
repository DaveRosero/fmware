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
while ($stmt->fetch()) {
  $disabled = ($qty == 0) ? 'disabled' : '';
  echo '
    <div class="col item-card">
        <div class="card h-100">
            <div>
            <img src="asset/images/products/' . $image . '" class="card-img-top" alt="' . $name . '">    
            <h5 class="card-title p-2 w-50 bg-success text-white"> ₱ '. number_format($unit_price) .' </h5>
            </div>                           
            <div class="card-body">
                <h5 class="card-title">' . $name . ' ('. $variant .')</h5>
                <div class="item-info">
                  <div class="d-flex justify-content-between">
                    <p> '. $unit_value .' '. strtoupper($unit) .'</p>
                    <div class="d-flex">
                      <p>Stock:</p>
                      <p> '. $qty .' </p>
                    </div>
                  </div>
                </div>
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
        </div>
  </div>';
}
$stmt->close();



