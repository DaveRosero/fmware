<?php
    include_once 'session.php';
    require_once 'model/user/user.php';
    require_once 'model/home/productClass.php';

    $user = new User();
    $product = new Product();
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>FMWare</title>
        <link rel="icon" href="asset/images/store/logo.png" type="image/png">
        <?php 
            include_once 'vendor/Bootstrap/css/bundle.php'; 
        ?>
        <link rel="stylesheet" href="asset/css/index.css">
    </head>
    <body>
        <?php include_once 'views/home/template/header.php'; ?>
        
        <?php //include_once 'views/home/template/banner.php'; ?>

        <?php //include_once 'views/home/content/home-content.php'; ?>

        <?php //include_once 'views/home/template/footer.php'; ?>
            
        <?php
            include_once 'vendor/jQuery/bundle.php';
            include_once 'vendor/FontAwesome/kit.php';
            include_once 'vendor/Bootstrap/js/bundle.php'; 
        ?>
        <script src="asset/js/home/cart.js"></script>
    </body>
</html>