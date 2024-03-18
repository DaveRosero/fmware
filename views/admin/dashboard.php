<?php
    include_once 'session.php';
    require_once 'model/admin/admin.php';
    require_once 'model/user/logoutClass.php';

    $admin = new Admin();
    $logout = new Logout();
    $admin->isAdmin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Dashboard | FMWare</title>
    <link rel="icon" href="asset/images/store/logo.png" type="image/png">
    <?php 
      include_once 'vendor/Bootstrap/css/bundle.php'; 
    ?>
    <link rel="stylesheet" href="asset/css/admin/dashboard.css">
    <link rel="stylesheet" href="asset/css/admin/style.css">
</head>
<body>
  <!--  Body Wrapper -->
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
    data-sidebar-position="fixed" data-header-position="fixed">
    
    <?php include 'views/admin/template/sidebar.php'; ?>

    <!--  Main wrapper -->
    <div class="body-wrapper">
      <?php include 'views/admin/template/header.php'; ?>
    

      <!-- Content Start -->
        <?php include 'views/admin/content/dashboard-content.php'; ?>
      <!-- Content End -->  
      
      
    </div>
  </div>


  <?php
    include_once 'vendor/jQuery/bundle.php';
    include_once 'vendor/FontAwesome/kit.php';
    include_once 'vendor/Bootstrap/js/bundle.php'; 
  ?>
  <script src="asset/js/admin/dashboard.js"></script>
  <script src="asset/js/admin/mini-sidebar.js"></script>
  <script src="asset/js/admin/sidebarmenu.js"></script>
</body>
</html>