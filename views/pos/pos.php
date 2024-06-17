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
            <!--Open Return & Refunds Modal-->
            <a class="nav-link" data-bs-target="#transactionSearch-Modal" data-bs-toggle="modal">Returns & Refunds</a>
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
    <div class="modal fade" id="transactionSearch-Modal" aria-hidden="true" aria-labelledby="transactionSearch-ModalLabel" tabindex="-1">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="etransactionSearch-ModalLabel">Transaction Search</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <!--Search using Transaction/Invoice # -->
            <div class="input-group mb-3">
              <input type="text" class="form-control" placeholder="Search" aria-label="Recipient's username" aria-describedby="button-addon2">
              <button class="btn btn-outline-success" type="button" id="button-addon2">Search</button>
            </div>
            <!--Show Search Result & transaction Details-->
            <table class="table align-middle">
              <thead class="table-secondary">
                <tr>
                  <th scope="col">Transaction #</th>
                  <th scope="col">Date</th>
                  <th scope="col">Total Price</th>
                  <th scope="col">Action</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <th scope="row">1234567890</th>
                  <td>09/11/2001</td>
                  <td>$100.00</td>
                  <td>
                    <button class="btn btn-primary" data-bs-target="#transactionView" data-bs-toggle="modal">View</button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    <!--Transaction View Modal-->
    <div class="modal fade" id="transactionView" aria-hidden="true" aria-labelledby="transactionViewLabel" tabindex="-1">
      <div class="modal-dialog modal-xl">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="transactionViewLabel">Transaction #1234567890</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body" style="max-height: 75vh; overflow:hidden;">
            <div class="row">
              <div class="col-7">
                <div class="col" style="height: calc(75vh - 200px);overflow-y: auto;overflow-x: hidden;">
                  <table class="table align-middle">
                    <thead class="table table-secondary">
                      <tr>
                        <th scope="col">Selected</th>
                        <th scope="col">Item Name</th>
                        <th scope="col">Unit</th>
                        <th scope="col">Variant</th>
                        <th scope="col">Price</th>
                        <th scope="col">Quantity</th>
                        <th scope="col">Quantity Selected</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td scope="row">
                          <!--Select Items to be Returned or Refunded-->
                          <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                          </div>
                        </td>
                        <td>Boysen Paint (W)</td>
                        <td>1 Liter</td>
                        <td>White</td>
                        <td>$99.00</td>
                        <td>3</td>
                        <!--Select Item  Qty to be Returned or Refunded-->
                        <td>
                          <div class="input-group">
                            <button class="btn btn-sm btn-outline-secondary minus-qty" type="button" data-product-id="' . $id . '" data-product-qty="' . $qty . '">
                              <i class="fas fa-minus"></i>
                            </button>
                            <input type="text" class="form-control text-center qty-input" value="' . $qty . '" style="max-width: 50px;">
                            <button class="btn btn-sm btn-outline-secondary add-qty" type="button" data-product-id="' . $id . '" data-product-qty="' . $qty . '">
                              <i class="fas fa-plus"></i>
                            </button>
                          </div>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
              <!--Show the details  and total of the items to be  returned or refunded-->
              <div class="col-5">
                <div class="row text-center border-bottom mb-2">
                  <h1 class="text-danger" id="transaction-total">$100.00</h1>
                  <p class="text-secondary">Total Price</p>
                </div>
                <div class="col border-bottom mb-2">

                  <div class="mb-2">
                    <div class="d-flex justify-content-end">
                      <h5 class="text-secondary my-auto">Return/Refund Total:</h5>
                      <h1 class="text-success" id="return-refundTotal">₱0.00</h1>
                    </div>
                  </div>
                </div>
                <div class="col">
                  <form action="" class="transactionType-form">
                    <div class="d-flex justify-content-end">
                      <select class="form-select w-50" aria-label="Default select example" disabled>
                        <option value="1">walk-in</option>
                        <option value="2">deliver</option>
                        <option value="2">online order</option>
                      </select>
                    </div>
                    <!--Show the details  of customer this can be blank-->
                    <h3>Customer Details</h3>
                    <form class="form-control">
                      <label for="fName-input" class="form-label">First Name:</label>
                      <input type="text" class="form-control" placeholder="Jhon" id="fName-input" disabled />
                      <label for="lName-input" class="form-label">Last Name:</label>
                      <input type="text" class="form-control" placeholder="Doe" id="lName-input" disabled />
                      <label for="address-input" class="form-label">Address:</label>
                      <input type="text" class="form-control" placeholder="#123 Somewhere Street, Nowhere City" id="address-input" disabled />
                      <label for="contact-input" class="form-label">Contact:</label>
                      <input type="text" class="form-control" placeholder="1231-1231-1231" id="contact-input" disabled />
                    </form>
                  </form>
                </div>
              </div>
            </div>
          </div>
          <!--choose to confirm or you can cancel it by pressing the x on the top of the view modal -->
          <div class="modal-footer">
            <button type="button" class="btn btn-primary">Return</button>
            <button type="button" class="btn btn-secondary">Refund</button>
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