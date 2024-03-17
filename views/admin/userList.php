<?php
  if (session_status() == PHP_SESSION_NONE) {
      session_start();
  }

  require_once 'model/admin/admin.php';
  require_once 'model/admin/userListClass.php';

  $admin = new Admin();
  $userList = new UserList();

  $admin->isAdmin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Manage Users | FMWare</title>
    <link rel="icon" href="asset/images/store/logo.png" type="image/png">
    <!--Style-->
    <link rel="stylesheet" href="asset/css/dashboard.css">
    <!--Fonts-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;200;300;400;500;700;900&display=swap">
    <link rel="stylesheet" href="vendor/fontawesome/css/all.min.css">
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
        <?php include 'views/admin/content/userList-content.php'; ?>
      <!-- Content End -->  
      
      
    </div>
  </div>


    <!--jQuery-->
    <script src="vendor/jQuery/jquery-3.7.1.slim.min.js"></script>
    <!--Bootstrap JS-->
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!--Font Awesome-->
    <script src="vendor/fontawesome/js/all.min.js"></script>
    <!--Scripts-->
    <script src="asset/js/dashboard.js"></script>
    <script src="asset/js/mini-sidebar.js"></script>
    <script src="asset/js/sidebarmenu.js"></script>
</body>
</html>