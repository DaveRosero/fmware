<?php
  include_once 'session.php';
  require_once 'model/admin/admin.php';
  require_once 'model/admin/orderClass.php';

  $admin = new Admin();
  $order = new Order();

//   $admin->isAdmin();
  if ($order->checkOrder($order_ref)) {
      header('Location: /fmware/404');
      exit();
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Manage Orders | FMWare</title>
    <link rel="icon" href="/fmware/asset/images/store/logo.png" type="image/png">
    <?php 
      include_once 'vendor/Bootstrap/css/bundle.php';
    ?>
    <link rel="stylesheet" href="/fmware/asset/css/admin/dashboard.css">
</head>
<body>
  <!--  Body Wrapper -->
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
    data-sidebar-position="fixed" data-header-position="fixed">
      <?php // include 'views/admin/template/sidebar.php'; ?>


    <!--  Main wrapper -->
    <div class="body-wrapper">
      <?php // include 'views/admin/template/header.php'; ?>
    

      <!-- Content Start -->
        <?php include 'views/admin/content/staff-order-content.php'; ?>
      <!-- Content End -->  
      
      
    </div>
  </div>

  <!-- Modal Start -->
    <?php
    //   include_once 'views/admin/modals/order-modal.php';
    ?>
  <!-- Modal End-->

  <?php
    include_once 'vendor/jQuery/bundle.php';
    include_once 'vendor/FontAwesome/kit.php';
    include_once 'vendor/Bootstrap/js/bundle.php'; 
  ?>
  <script src="/fmware/asset/js/admin/dashboard.js"></script>
  <script src="/fmware/asset/js/admin/mini-sidebar.js"></script>
  <script src="/fmware/asset/js/admin/sidebarmenu.js"></script>
  <script src="/fmware/asset/js/admin/confirm-order.js"></script>
</body>
</html>