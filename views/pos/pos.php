<?php
require_once 'model/database/database.php';
require_once 'model/user/addressClass.php';



$mysqli = database();

$posaddress = new Address();


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
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              Returns & Refunds
            </a>
            <ul class="dropdown-menu">
              <!--return modal toggle-->
              <li><a class="dropdown-item" data-bs-target="#retunsModaltoggle" data-bs-toggle="modal">Returns</a></li>
              <!--refund modal toggle-->
              <li><a class="dropdown-item" href="#">Refunds</a></li>
            </ul>
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
    <!--Return&Refund Modal-->
    <div class="modal fade" id="retunsModaltoggle" aria-hidden="true" aria-labelledby="retunsModaltoggleLabel" tabindex="-1">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalToggleLabel">Returns</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <!--searchbar-->
            <form class="invoice-search mb-3" action="">
              <div class="input-group">
                <input type="text" class="form-control" placeholder="Search">
                <button class="btn btn-outline-success" type="button" id="button-addon2">Search</button>
              </div>
            </form>
            <!--results-->
            <table class="table align-middle">
              <thead class="table-secondary">
                <tr>
                  <th>Invoice #</th>
                  <th>Date</th>
                  <th>Total Price</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>
                    1234567890
                  </td>
                  <td>09/11/2001</td>
                  <td>$999.00</td>
                  <td>
                    <!--Sale Info modal toggle-->
                    <button class="btn btn-primary" data-bs-target="#viewSalesToggle" data-bs-toggle="modal">View</button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
          </div>
        </div>
      </div>
    </div>
    <!--Sale Info  modal-->
    <div class="modal fade" id="viewSalesToggle" aria-hidden="true" aria-labelledby="viewSalesToggleLabel" tabindex="-1">
      <div class="modal-dialog modal-xl">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalToggleLabel2">1234567890</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body returnModal-content">
            <div class="row ">
              <div class="col returnModal-list">
                <table class="table text-center align-middle">
                  <thead class="table-secondary">
                    <tr>
                      <th></th>
                      <th>Item Name</th>
                      <th>Unit</th>
                      <th>Variant</th>
                      <th>Price</th>
                      <th>Quantity</th>
                      <th>Quantity Returned</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>
                        <div class="form-check ">
                          <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                        </div>
                      </td>
                      <td>Boysen Paint (W)</td>
                      <td>1 liter</td>
                      <td>White</td>
                      <td>$99.00</td>
                      <td>50</td>
                      <td>
                        <div class="input-group my-auto mb-3">
                          <button class="btn btn-outline-secondary" type="button" id="button-addon1"><i class="fas fa-minus"></i></button>
                          <input type="number" class="form-control text-center" placeholder="0" style="max-width: 50px;">
                          <button class="btn btn-outline-secondary" type="button" id="button-addon1"><i class="fas fa-plus"></i></button>
                        </div>
                      </td>
                    </tr>

                  </tbody>
                </table>
              </div>
              <div class="d-flex justify-content-end mb-3">
                <div class="d-flex justify-content-between mb-3">
                  <h5>Returned Value:</h5>
                  <h1>$999.00</h1>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button class="btn btn-danger">Cancel</button>
            <button class="btn btn-success">Return Items</button>
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
  <script src="asset/js/pos/checkout_modal.js"></script>
</body>

</html>