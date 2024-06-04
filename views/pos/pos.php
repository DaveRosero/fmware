<?php
require_once 'model/database/database.php';

$mysqli = database();

// Check if search parameter is set
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Query with search parameter
$query = 'SELECT price_list.unit_price,
                  product.image,
                  product.name,
                  product.barcode,
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
$stmt->bind_result($unit_price, $image, $name, $barcode, $unit_value, $qty, $id, $unit, $variant);
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
      <a class="navbar-brand" href="#"><img class="rounded-circle me-2 logo-img" src="asset/images/store/logo.png" alt="logo-img" style="width: 30px;" />FMWare</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="#">Home</a>
          </li>
        </ul>
        <div class="dropdown">
          <button class="btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            Username
          </button>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="#">Profile</a></li>
            <li><a class="dropdown-item" href="#">Logout</a></li>
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
            <!-- <div class="col">
                <form id="barcode-form">
                    <label for="barcode">Scan Barcode here</label>
                    <input type="text" name="barcode" id="barcode">
                    <button type="submit" class="cart-button">Search</button>
                </form>
            </div> -->

            <form class="d-flex" role="search" id="barcode-form">
              <input class="form-control me-2" type="text" name="barcode" id="barcode" placeholder="Search">
              <button class="btn btn-outline-success cart-button" type="submit">Search</button>
            </form>
            <br>
            <table class="table table-hover">
              <thead class="sticky-header" style="position: sticky;top: 0;">
                <tr class="table-secondary">
                  <td>Image</td>
                  <td>Product Name</td>
                  <td>Unit</td>
                  <td>Variant</td>
                  <td>Barcode</td>
                  <td>Stock</td>
                  <td>Price</td>
                  <td>Action</td>
                </tr>
              </thead>
              <tbody>
                <?php
                while ($stmt->fetch()) {
                  $disabled = ($qty == 0) ? 'disabled' : '';

                  echo '<tr>
                                <td class="align-middle"><img src="asset/images/products/' . $image . '" alt="" srcset="" style="width: 90px;"></td>
                                <td class="align-middle">' . $name . '</td>
                                <td class="align-middle">' . $unit_value . ' ' . strtoupper($unit) .'</td>
                                <td class="align-middle">' . $variant . '</td>
                                <td class="align-middle">' . $barcode . '</td>
                                <td class="align-middle">' . $qty . '</td>
                                <td class="align-middle">₱' . number_format($unit_price) . '</td>
                                <td class="align-middle">
                                    <button class="btn btn-primary cart-button" 
                                      data-product-id="' . $id . '"
                                      data-product-price="' . $unit_price . '"
                                      ' . $disabled. '
                                    >
                                    <i class="fas fa-cart-plus"></i>
                                    </button>
                                </td>
                            </tr>';
                }
                $stmt->close();
                ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="col-5 left-section">
        <div class="col-body mt-2">
          <div class="table-container" style="height: calc(100vh - 25vh);overflow-y: auto;">
            <table class="table table-hover">
              <thead class="sticky-header" style="position: sticky;top: 0;">
                <tr class="table-secondary">
                  <td>Product Name</td>
                  <td>Price</td>
                  <td>QTY</td>
                  <td>Discount</td>
                  <td>Total Price</td>
                  <td>Action</td>
                </tr>
              </thead>
              <tbody id="cart-body">
              </tbody>
            </table>
          </div>
          <h2 class="text-end" id="cart-total">Total: ₱0</h2>
          <div class="d-grid gap-2">
            <button class="btn btn-danger reset-cart">Clear</button>
            <button class="btn btn-success print">Print</button>
            <button class="btn btn-success print">Accept Payment</button>
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