<?php
  include_once 'session.php';
  require_once 'model/admin/admin.php';
  require_once 'model/admin/userListClass.php';
  require_once 'model/admin/groupClass.php';

  $admin = new Admin();
  $user_list = new UserList();
  $group = new Group();

  $admin->isAdmin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Manage Users | FMWare</title>
    <link rel="icon" href="/asset/images/store/logo.png" type="image/png">
    <?php 
      include_once 'vendor/Bootstrap/css/bundle.php';
      include_once 'vendor/DataTables/css/bundle.php'; 
    ?>
    <link rel="stylesheet" href="/asset/css/admin/dashboard.css">
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
        <?php include 'views/admin/content/staffList-content.php'; ?>
      <!-- Content End -->  
      
      
    </div>
  </div>

  <?php
    include_once 'vendor/jQuery/bundle.php';
    include_once 'vendor/FontAwesome/kit.php';    
    include_once 'vendor/DataTables/js/bundle.php';
    include_once 'vendor/Bootstrap/js/bundle.php'; 
  ?>
  <script src="/asset/js/admin/dashboard.js"></script>
  <script src="/asset/js/admin/mini-sidebar.js"></script>
  <script src="/asset/js/admin/sidebarmenu.js"></script>
</body>
</html>