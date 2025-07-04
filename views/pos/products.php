<?php
require_once 'model/database/database.php';

$mysqli = database();

$query = 'SELECT price_list.unit_price,
                  product.image,
                  product.name,
                  product.unit_value,
                  stock.qty,
                  stock.critical_level,
                  product.id,
                  product.barcode,
                  unit.name,
                  variant.name
                  FROM price_list
                  INNER JOIN stock ON price_list.product_id = stock.product_id
                  INNER JOIN product ON price_list.product_id = product.id
                  INNER JOIN variant ON variant.id = product.variant_id
                  INNER JOIN unit ON unit.id = product.unit_id
                  INNER JOIN brand ON product.brand_id = brand.id
                  WHERE (product.name LIKE CONCAT("%", ?, "%") 
                        OR product.barcode LIKE CONCAT("%", ?, "%")
                        OR variant.name LIKE CONCAT("%", ?, "%")
                        OR brand.name LIKE CONCAT("%", ?, "%"))
                        AND stock.qty > 0
                  ORDER BY product.name ASC';

$stmt = $mysqli->prepare($query);
$stmt->bind_param('ssss', $search, $search, $search, $search); // Bind search parameter twice for both placeholders
$stmt->execute();
$stmt->bind_result($unit_price, $image, $name, $unit_value, $qty, $critical_level, $id, $barcode, $unit, $variant);

$output = '';

while ($stmt->fetch()) {
  $disabled = ($qty <= 0) ? 'disabled' : '';
  $output .= '
    <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
      <div class="card border-secondary shadow-sm rounded">
        <img src="asset/images/products/' . $image . '" class="card-img-top img-fluid" alt="' . $name . '">
        <div class="card-body d-flex flex-column">
          <h5 class="card-title text-dark product-name" data-bs-toggle="tooltip" data-bs-placement="top" title="' . $name . ' (' . $variant . ')">
          ' . $name . ' <small class="text-muted">(' . $variant . ')</small></h5>
          <p class="card-text">Unit: <strong>' . $unit_value . ' ' . strtoupper($unit) . '</strong></p>
          <p class="card-text">Stock: <span class="badge ' . ($qty > $critical_level ? 'bg-success' : 'bg-danger') . '">' . $qty . '</span></p>
          <input type="hidden" value="' . $barcode . '" class="product-barcode">
          <div class="d-grid">
            <button class="btn btn-primary btn-lg' . ($disabled ? ' disabled' : '') . ' cart-button" 
                    data-product-id="' . $id . '"
                    data-product-price="' . $unit_price . '">
              <i class="fas fa-cart-plus"></i> Add to Cart
            </button>
          </div>
        </div>
        <div class="card-footer text-center">
          <h5 class="m-0 text-success">₱ ' . number_format($unit_price, 2) . '</h5>
        </div>
      </div>
    </div>';
}
$stmt->close();

echo $output;
?>