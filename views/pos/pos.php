<?php
require_once 'model/database/database.php';

$mysqli = database();

// Check if search parameter is set
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Query with search parameter
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
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>FMWARE | POS</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-beta.1/css/select2.min.css">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
  <link rel="stylesheet" href="asset/css/pos.css">
</head>

<body style="max-height: 100vh;overflow-y: hidden;">
  <nav class="navbar navbar-expand-lg border-bottom">
    <div class="container-fluid">
      <a class="navbar-brand" href="#"><img class="rounded-circle me-2 logo-img" src="asset/images/store/logo.png" alt="logo-img" style="width: 30px;" />FMWare|POS</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        </ul>
        <div class="dropdown">
          <button class="btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            Username
          </button>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="/login">Logout</a></li>
          </ul>
        </div>
      </div>
    </div>
  </nav>
  <div class="container-fluid">
    <div class="row">
      <div class="col right-section border-end">
        <div class="col-body mt-2">
          <div class="table-container" style="height: calc(100vh - 12vh);overflow-y: auto;">

            <form class="d-flex" role="search" id="barcode-form">
              <input class="form-control me-2" type="text" name="barcode" id="barcode" placeholder="Search">
              <button class="btn btn-outline-success cart-button" type="submit">Search</button>
            </form>
            <br>
              <tbody>
                <div class="row">
                  <?php
                  while ($stmt->fetch()) {
                      $disabled = ($qty == 0) ? 'disabled' : '';
                      echo '
                        <div class="col-md-4 mb-4">
                            <div class="card" style="width: 18rem;">
                                <img src="asset/images/products/' . $image . '" class="card-img-top" alt="' . $name . '" style="height: 200px; object-fit: contain;">
                                <div class="card-body">
                                    <h5 class="card-title">' . $name . '</h5>
                                    <p class="card-text">Variant: ' . $variant . '</p>
                                    <p class="card-text">Unit: ' . $unit_value . ' ' . strtoupper($unit) . '</p>
                                    <p class="card-text">Stock: ' . $qty . '</p>
                                    <p class="card-text">Price: ₱' . number_format($unit_price) . '</p>
                                    <button class="btn btn-primary cart-button" 
                                        data-product-id="' . $id . '"
                                        data-product-price="' . $unit_price . '"
                                        ' . $disabled . '
                                    >
                                        <i class="fas fa-cart-plus"></i> Add to Cart
                                    </button>
                                </div>
                            </div>
                      </div>';
                  }
                  $stmt->close();
                  ?>
                </div>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="col-5 left-section">
        <div class="col-body mt-2">
          <div class="table-container" style="height: calc(100vh - 31vh);overflow-y: auto;">
            <table class="table table-hover">
              <thead class="sticky-header" style="position: sticky;top: 0;">
                <tr class="table-secondary">
                  <td>Product Name</td>
                  <td>Variant</td>
                  <td>Unit</td>
                  <td>QTY</td>
                  <td>Price</td>
                  <td>Action</td>
                </tr>
              </thead>
              <tbody id="cart-body">
              </tbody>
            </table>
          </div>
          <h5 class="text-end" id="cart-total">Total: ₱0</h5>
          <div class="d-grid gap-2">
            <button class="btn btn-danger reset-cart">Clear</button>
            <button class="btn btn-success print">Print</button>
            <!-- <button class="btn btn-success print">Print</button> -->
          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-beta.1/js/select2.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>
  <script src="asset/js/pos/pos.js"></script>
</body>
</html>