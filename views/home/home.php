<?php
    include_once 'session.php';
    require_once 'model/user/user.php';
    require_once 'model/home/home.php';
    require_once 'model/home/productClass.php';
    require_once 'model/home/cartClass.php';
    
    $home = new Home();
    $user = new User();
    $product = new Product();
    $cart = new Cart();

    if (isset($_SESSION['group']) && isset($_SESSION['user_id']) && $_SESSION['group'] !== 'user') {
        $home->redirectUser($_SESSION['group']);
    }
    $user_info = $user->getUser($_SESSION['email'] ?? null);
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
        
        <?php include_once 'views/home/template/banner.php'; ?>

        <?php include_once 'views/home/content/home-content.php'; ?>

        <?php include_once 'views/home/template/footer.php'; ?>
            
        <?php
            include_once 'vendor/jQuery/bundle.php';
            include_once 'vendor/FontAwesome/kit.php';
            include_once 'vendor/Bootstrap/js/bundle.php'; 
        ?>
        <script src="/vendor/NotifyJS/js/notify.js"></script>
        <script src="/asset/js/home/home.js"></script>
        <script src="/asset/js/home/cart.js"></script>
    </body>
</html>