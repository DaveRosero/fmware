<?php
include_once 'session.php';
require_once 'model/user/user.php';
require_once 'model/home/home.php';
require_once 'model/home/productClass.php';
require_once 'model/home/cartClass.php';
require_once 'model/user/addressClass.php';
require_once 'model/user/transactionClass.php';

$home = new Home();
$user = new User();
$product = new Product();
$cart = new Cart();
$address = new Address();
$transaction = new Transaction();

if (isset($_SESSION['group']) && isset($_SESSION['user_id']) && $_SESSION['group'] !== 'user') {
    $home->redirectUser($_SESSION['group']);
}
$user_info = $user->getUser($_SESSION['email'] ?? null);
$user_address = $address->getAddress($user_info['id']);

if (isset($_SESSION['buynow'])) {
    $buynow = $_SESSION['buynow'];
    $product = $_SESSION['product'];
    unset($_SESSION['buynow']); // Clear the session variable after use
    unset($_SESSION['product']); // Clear the session variable after use
    // print_r($product);
} else {
    $buynow = 0;
}
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
    <style>
        .form-check-input {
            border-color: #444;
            /* Darker border */
        }

        .form-check-input:checked {
            border-color: #000;
            /* Darker border when checked */
        }

        .form-check-input:focus {
            box-shadow: 0 0 0 0.2rem rgba(0, 0, 0, 0.25);
            /* Darker focus shadow */
        }

        .custom-icon-size {
            font-size: 6rem;
            /* Adjust size as needed */
        }
    </style>
</head>

<body>
    <?php include_once 'views/home/template/header.php'; ?>

    <?php include_once 'views/home/content/cart-content.php'; ?>

    <?php include_once 'views/home/modals/cart-modal.php'; ?>

    <script src="/vendor/NotifyJS/js/notify.js"></script>
    <script src="/asset/js/home/cart.js"></script>
</body>

</html>