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
    <title>Terms & Conditions | FMWare</title>
    <link rel="icon" href="/asset/images/store/logo.png" type="image/png">
    <?php
    require_once 'config/load_vendors.php';
    ?>
    <link rel="stylesheet" href="/asset/css/index.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.min.css" integrity="sha256-Qsx5lrStHZyR9REqhUF8iQt73X06c8LGIUPzpOhwRrI=" crossorigin="anonymous">
</head>

<body>
    <?php include_once 'views/home/template/header.php'; ?>

    <?php include_once 'views/home/content/terms-content.php'; ?>

    <?php include_once 'views/home/template/footer.php'; ?>

    <script src="/asset/js/home/home.js"></script>
    <script src="/asset/js/home/cart.js"></script>
</body>

</html>