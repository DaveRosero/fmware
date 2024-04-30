<?php
    include_once 'session.php';
    require_once 'model/user/user.php';
    require_once 'model/home/cartClass.php';
    require_once 'model/home/shopClass.php';

    $user = new User();
    $cart = new Cart();
    $shop = new Shop();

    if ($shop->notFilter($filter)) {
        header('Location: /404');
    }

    $brands = $shop->getBrandArray();
    $categories = $shop->getCategoryArray();
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>FMWare</title>
        <link rel="icon" href="/asset/images/store/logo.png" type="image/png">
        <?php 
            include_once 'vendor/Bootstrap/css/bundle.php';
        ?>
        <link rel="stylesheet" href="/asset/css/index.css">
    </head>
    <body>
        <?php include_once 'views/home/template/header.php'; ?>

        <?php include_once 'views/home/content/shop-content.php'; ?>

        <?php //include_once 'views/home/modals/my-purchases-modal.php'; ?>
            
        <?php
            include_once 'vendor/jQuery/bundle.php';
            include_once 'vendor/FontAwesome/kit.php';
            include_once 'vendor/Bootstrap/js/bundle.php'; 
        ?>
        <script src="/asset/js/home/home.js"></script>
        <script src="/asset/js/home/shop.js"></script>
    </body>
</html>