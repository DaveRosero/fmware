<?php
    include_once 'session.php';
    require_once 'model/user/user.php';
    require_once 'model/home/home.php';
    require_once 'model/home/cartClass.php';
    require_once 'model/user/transactionClass.php';

    $home = new Home();
    $user = new User();
    $cart = new Cart();
    $transaction = new Transaction();

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
            require_once 'config/load_vendors.php'; 
        ?>
        <link rel="stylesheet" href="/asset/css/index.css">
    </head>
    <body>
        <?php include_once 'views/home/template/header.php'; ?>

        <?php include_once 'views/home/content/account-content.php'; ?>

        <?php // include_once 'views/home/modals/cart-modal.php'; ?>

        <script src="/asset/js/home/home.js"></script>
        <script src="/asset/js/home/my-purchases.js"></script>
    </body>
</html>