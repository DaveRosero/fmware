<?php
  include_once 'session.php';
  require_once 'model/admin/poClass.php';

  $admin = new Admin();
  $po = new PO();
  $admin->isAdmin();
  
  $supplier_info = $po->getSupplierInfo(urldecode($supplier));
  $po_info = $po->getPOInfo($po_ref);

  if ($po_info['status'] == 0) {
    header('Location: /404');
  }
?>
<!DOCTYPE html>
<html lang="en">
  <head>
      <meta charset="utf-8" />
      <meta name="viewport" content="width=device-width, initial-scale=1" />
      <title>View P.O. | FMWare</title>
      <link rel="icon" href="/asset/images/store/logo.png" type="image/png">
      <?php require_once 'config/load_vendors.php'; ?>
      <link rel="stylesheet" href="/asset/css/admin/style.css">
      <style>
          @media print {
              th, td {
                  font-size: .6rem; /* Equivalent to Bootstrap's fs-1 */
              }
          }
          .wrapper {
              padding: 20px; /* Adjust the padding to create margins */
              background: white; /* Background color to show the margin area */
          }
      </style>
  </head>
  <body class="bg-light">
    <?php include_once 'views/admin/template/header.php'; ?>

    <div class="container-fluid">
      <div class="row">
          <!-- Sidebar -->
          <div class="col-lg-1 col-md-1 bg-dark sticky-top">
            <?php include_once 'views/admin/template/sidebar.php'; ?>
          </div>
          <!-- Sidebar -->

          <!-- Content -->
          <div class="col-lg-11 col-md-11 p-3 min-vh-100"> 
            <?php include_once 'views/admin/content/view-po-content.php'; ?>
          </div>
          <!-- Content -->
      </div>
    </div>

    <!-- Modal Start -->
    <!-- Modal End-->

    <script src="/asset/js/admin/view-po.js"></script>
    <script src="/asset/js/template/sidebar.js"></script>
  </body>
</html>