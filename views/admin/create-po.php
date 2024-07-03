<?php
  include_once 'session.php';
  require_once 'model/admin/poClass.php';

  $admin = new Admin();
  $po = new PO();
  $admin->isAdmin();
  
  $supplier_info = $po->getSupplierInfo(urldecode($supplier));
  $po_info = $po->getPOInfo($po_ref);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Create P.O. | FMWare</title>
    <link rel="icon" href="/asset/images/store/logo.png" type="image/png">
    <?php 
      include_once 'config/load_vendors.php';
    ?>
    <link rel="stylesheet" href="/asset/css/admin/dashboard.css">
    <link rel="stylesheet" href="/asset/css/admin/style.css">
</head>
<body>
  <!--  Body Wrapper -->
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
    data-sidebar-position="fixed" data-header-position="fixed">
    
    <?php include 'views/admin/template/sidebar.php'; ?>

    <!--  Main wrapper -->
    <div class="body-wrapper">
      <?php include 'views/admin/template/header.php';?>
    
      <!-- Content Start -->
         <?php include 'views/admin/content/create-po-content.php';?>
      <!-- Content End -->  
      
      
    </div>

    
  </div>

  <!-- Modal Start -->
    <?php
      //include_once 'views/admin/modals/purchase-order-modal.php';
    ?>
  <!-- Modal End-->

  <script src="/asset/js/admin/mini-sidebar.js"></script>
  <script src="/asset/js/admin/sidebarmenu.js"></script>
  <script src="/asset/js/admin/create-po.js"></script>
</body>
</html>