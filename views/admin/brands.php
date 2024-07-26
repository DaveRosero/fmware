<?php
  include_once 'session.php';
  require_once 'model/admin/admin.php';
  require_once 'model/admin/brandsClass.php';

  $admin = new Admin();
  $brand = new Brands();

  $admin->isAdmin();
?>
<!DOCTYPE html>
<html lang="en">
  <head>
      <meta charset="utf-8" />
      <meta name="viewport" content="width=device-width, initial-scale=1" />
      <title>Manage Brands | FMWare</title>
      <link rel="icon" href="/asset/images/store/logo.png" type="image/png">
      <?php require_once 'config/load_vendors.php'; ?>
      <link rel="stylesheet" href="/asset/css/admin/style.css">
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
            <?php include_once 'views/admin/content/brands-content.php'; ?>
          </div>
          <!-- Content -->
      </div>
    </div>

    <!-- Modal Start -->
    <?php include_once 'views/admin/modals/brand-modal.php'; ?>
    <!-- Modal End-->

    <script src="/asset/js/admin/brand.js"></script>
    <script src="/asset/js/template/sidebar.js"></script>
  </body>
</html>