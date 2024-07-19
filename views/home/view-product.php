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
    $product_info = $product->getProductInfo($product_id);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>View Product | FMWare</title>
        <link rel="icon" href="/asset/images/store/logo.png" type="image/png">
        <?php
            require_once 'config/load_vendors.php';
        ?>
        <link rel="stylesheet" href="/asset/css/index.css">
    </head>
    <body>
        <?php include_once 'views/home/template/header.php'; ?>

        <?php include_once 'views/home/content/view-product-content.php'; ?>

        <?php include_once 'views/home/modals/view-product-modal.php'; ?>

        <?php include_once 'views/home/template/footer.php'; ?>
        
        <script src="/vendor/NotifyJS/js/notify.js"></script>
        <script src="/asset/js/home/view-product.js"></script>
    </body>
</html>