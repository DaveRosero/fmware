<?php
include_once 'session.php';
include_once 'model/history.php';
include_once 'model/pickup.php';
require_once 'model/database/database.php';
require_once 'model/user/addressClass.php';
require_once 'model/user/user.php';


$posaddress = new Address();
$user = new User();

// Check if session email is set
if (isset($_SESSION['email'])) {
  $user_info = $user->getUser($_SESSION['email']);

  // Check if 'name' key exists in $user_info before using it
  $user_name = isset($user_info['fname']) ? $user_info['fname'] : 'Unknown User';
} else {
  // Handle case where session email is not set
  $user_name = 'Unknown User';
}

// Check if search parameter is set
$search = isset($_GET['search']) ? $_GET['search'] : '';

if ($_SESSION['group'] !== 'cashier') {
  header('Location: /404');
}

?>



<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>FMWARE | POS</title>
  <link rel="icon" href="/asset/images/store/logo.png" type="image/png">
  <?php
  require_once 'config/load_vendors.php';
  ?>
  <link rel="stylesheet" href="asset/css/pos/pos.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

</head>

<body style="overflow: hidden;">
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark border-bottom shadow-sm">
    <div class="container-fluid">
      <a class="navbar-brand d-flex align-items-center" href="#">
        <img class="rounded-circle me-2 logo-img" src="asset/images/store/logo.png" alt="logo-img"
          style="width: 40px; height: 40px;" />
        <span class="fw-bold text-light">FMWare|POS</span>
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link btn btn-outline-success text-light me-2 d-flex align-items-center"
              data-bs-target="#transaction-searchModal" data-bs-toggle="modal" id="transaction-searchBtn">
              Refund & Replace <i class="fa-solid fa-arrows-rotate ms-2"></i>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link btn btn-outline-success text-light me-2 d-flex align-items-center"
              data-bs-target="#pickup-searchModal" data-bs-toggle="modal">
              Pick-Up <i class="fa-solid fa-right-to-bracket ms-2"></i>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link btn btn-outline-success text-light d-flex align-items-center"
              data-bs-target="#history-searchModal" data-bs-toggle="modal">
              History <i class="fa-solid fa-clock ms-2"></i>
            </a>
          </li>
        </ul>
        <!-- Notification Bell and Account Name -->
    <div class="d-flex align-items-center">
      <!-- Notification Bell -->
        <div class="dropdown me-3">
            <button class="btn btn-outline-success position-relative" type="button" id="notificationDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-bell"></i>
                <span id="notification-count" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                    0
                </span>
            </button>
            <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end notif-dropdown " aria-labelledby="notificationDropdown">
                <li><h6 class="dropdown-header text-dark">Notifications</h6></li>
                <?php include_once 'notify-pickup.php' ?>
            </ul>
        </div>
        <div class="dropdown">
          <button class="btn btn-outline-success dropdown-toggle border-light text-light" type="button"
            data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-person-circle me-2"></i> Hi, <?php echo htmlspecialchars($user_name); ?>
          </button>
          <ul class="dropdown-menu dropdown-menu-end bg-dark border-0 shadow">
            <li>
              <form action="/logout" method="post" class="d-flex justify-content-center">
                <button type="submit" class="btn btn-outline-danger w-75">Logout</button>
              </form>
            </li>
          </ul>
        </div>
    </div>
  </nav>
  <div class="container-fluid main-content">
    <div class="row">
      <div class="col right-section border-end">
        <div class="col-body mt-2">
          <div class="table-container"
            style="max-height: 90vh; overflow-x:hidden; overflow-y:auto;">
            <form class="d-flex" role="search" id="barcode-form">
              <input class="form-control me-2 border-2 rounded-pill shadow-sm" type="text" name="barcode" id="barcode"
                placeholder="Search">
            </form>
            <br>
            <tbody>
              <div class="row row-cols-1 row-cols-md-4 row-cols-lg-4 g-2 item-list h-100">
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
            <table class="table table-hover">
              <thead class="table-secondary">
                <tr>
                  <th class="text-center">Item Name</th>
                  <th class="text-center">Variant</th>
                  <th class="text-center">Unit</th>
                  <th class="text-center">Quantity</th>
                  <th class="text-center">Price</th>
                  <th class="text-center">Actions</th>
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
              <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#checkoutModal"
                id="checkout-button" disabled>
                Checkout
              </button>
            </div>
          </div>
          <?php include_once 'modal/checkout-modal.php' ?>
        </div>
      </div>
    </div>
    <!--Pickup Search Modal-->
    <?php include_once 'modal/pickup-searchModal.php' ?>
    <!--Pickup View Modal-->
    <?php include_once 'modal/pickup-viewModal.php' ?>
    <!--Pickup Confirmation Modal-->
    <?php include_once 'modal/pickup-confirmationModal.php' ?>
    <!--Pickup History Search Modal-->
    <?php include_once 'modal/history-searchModal.php' ?>
    <!--Pickup History View Modal-->
    <?php include_once 'modal/history-viewModal.php' ?>
    <!--Pickup History View Modal-->
    <?php include_once 'modal/history-confirmationModal.php' ?>

    <!--REFUNDS RETURNS MODALS-->
    <?php include_once 'modal/transaction-searchModal.php' ?>
    <?php include_once 'modal/transaction-viewModal.php' ?>
  </div>

  <script src="asset/js/pos/pos.js"></script>
  <script src="asset/js/pos/checkout_modal.js"></script>
  <script src="asset/js/pos/history.js"></script>
  <script src="asset/js/pos/transactions.js"></script>
  <script src="asset/js/pos/pickup.js"></script>
</body>

</html>