<?php
require_once 'model/database/database.php';

$mysqli = database();

// Check if search parameter is set
$search = isset($_GET['search']) ? $_GET['search'] : '';


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
  <link rel="stylesheet" href="asset/css/pos/pos.css">
</head>

<body style="overflow: hidden;">
  <nav class="navbar navbar-expand-lg border-bottom">
    <div class="container-fluid">
      <a class="navbar-brand" href="#"><img class="rounded-circle me-2 logo-img" src="asset/images/store/logo.png" alt="logo-img" style="width: 30px;" />FMWare|POS</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <!--Open Return & Refunds Modal (transaction search modal)-->
            <a class="nav-link" data-bs-target="#transactionSearch-Modal" data-bs-toggle="modal">Replacements & Refunds</a>
          </li>
          <li class="nav-item">
            <!--Pick Up Modal (for pick up transaction search modal)-->
            <a class="nav-link" data-bs-target="#pickup-searchModal" data-bs-toggle="modal">Pick-Up</a>
          </li>
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
  <div class="container-fluid main-content">
    <div class="row">
      <div class="col right-section border-end">
        <div class="col-body mt-2">
          <div class="table-container">

            <form class="d-flex" role="search" id="barcode-form">
              <input class="form-control me-2" type="text" name="barcode" id="barcode" placeholder="Search">
              <button class="btn btn-outline-success cart-button" type="submit">Search</button>
            </form>
            <br>
            <tbody>
              <div class="row row-cols-4 g-2 item-list">
                <?php include_once 'products.php' ?>
              </div>
            </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="col-5 left-section">
        <div class="col-body mt-2">
          <div class="cartSection-header mb-3">
            <h3>CART</h3>
          </div>
          <!--Cart List-->
          <div class="col cart-list">
            <table class="table align-middle">
              <thead class="table-secondary">
                <tr>
                  <th>Item Name</th>
                  <th>Variant</th>
                  <th>Unit</th>
                  <th>Quantity</th>
                  <th>Price</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody id="cart-body">
              </tbody>
            </table>
          </div>
          <div class="row cartSection-footer border-top">
            <h5 class="text-end" id="cart-total">Subtotal: ₱0</h5>
            <div class="d-grid gap-2">
              <button class="btn btn-danger reset-cart">Clear</button>
              <!-- Checkout Button -->
              <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#checkoutModal" id="checkout-button">
                Checkout
              </button>
            </div>
          </div>
          <?php include_once 'modal/checkout-modal.php' ?>
        </div>
      </div>
    </div>
    <!--Transaction Search Modal-->
    <?php include_once 'modal/transaction-searchModal.php' ?>
    <!--Transaction View Modal-->
    <?php include_once 'modal/transaction-viewModal.php' ?>
    <!--Confirm Return Modal-->
    <?php include_once 'modal/replace-confirmationModal.php' ?>
    <!--Confirm Return Modal-->
    <?php include_once 'modal/refund-confirmationModal.php' ?>
    <!--Pickup Search Modal-->
    <?php include_once 'modal/pickup-searchModal.php' ?>
    <!--Pickup View Modal-->
    <?php include_once 'modal/pickup-viewModal.php' ?>
    <!--Pickup Confirmation Modal-->
    <?php include_once 'modal/pickup-confirmationModal.php' ?>



  </div>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-beta.1/js/select2.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>
  <script src="asset/js/pos/pos.js"></script>
  <script src="asset/js/pos/checkout_modal.js"></script>
</body>

</html>