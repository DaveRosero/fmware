<?php
    include_once 'session.php';
    require_once 'model/user/user.php';
    require_once 'model/home/productClass.php';
    require_once 'model/home/cartClass.php';

    $user = new User();
    $product = new Product();
    $cart = new Cart();

    $product_info = $product->getProductInfo($product_id);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>View Product | FMWare</title>
        <link rel="icon" href="/fmware/asset/images/store/logo.png" type="image/png">
        <?php 
            include_once 'vendor/Bootstrap/css/bundle.php'; 
        ?>
        <link rel="stylesheet" href="/fmware/asset/css/index.css">
    </head>
    <body>
        <?php include_once 'views/home/template/header.php'; ?>

        <!-- Open Content -->
        <section class="bg-light">
            <div class="container pb-5">
                <div class="row">
                    <div class="col-lg-5 mt-5">
                        <div class="card mb-3">
                            <img class="card-img img-fluid" src="/fmware/asset/images/products/<?php echo $product_info['image']; ?>" alt="Product Image" id="product-detail">
                        </div>
                    </div>
                    <!-- col end -->
                    <div class="col-lg-7 mt-5">
                        <div class="card">
                            <div class="card-body">
                                <h1 class="h2"><?php echo $product_info['name']; ?></h1>
                                <p class="h3 py-2"><?php echo $product->getPrice($product_id); ?></p>
                                <ul class="list-inline">
                                    <li class="list-inline-item">
                                        <h6>Brand:</h6>
                                    </li>
                                    <li class="list-inline-item">
                                        <p class="text-muted"><strong>Easy Wear</strong></p>
                                    </li>
                                </ul>

                                <h6>Description:</h6>
                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod temp incididunt ut labore et dolore magna aliqua. Quis ipsum suspendisse. Donec condimentum elementum convallis. Nunc sed orci a diam ultrices aliquet interdum quis nulla.</p>

                                <h6>Specification:</h6>
                                <ul class="list-unstyled pb-3">
                                    <li>Lorem ipsum dolor sit</li>
                                    <li>Amet, consectetur</li>
                                </ul>

                                <form action="" method="GET">
                                    <input type="hidden" name="product-title" value="Activewear">
                                    <div class="row">
                                        <div class="col-auto">
                                            <ul class="list-inline pb-3">
                                                <li class="list-inline-item text-right">
                                                    Quantity
                                                    <input type="hidden" name="product-quanity" id="product-quanity" value="1">
                                                </li>
                                                <li class="list-inline-item"><span class="btn btn-success" id="btn-minus">-</span></li>
                                                <li class="list-inline-item"><span class="badge bg-secondary" id="var-value">1</span></li>
                                                <li class="list-inline-item"><span class="btn btn-success" id="btn-plus">+</span></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="row pb-3">
                                        <div class="col d-grid">
                                            <button type="submit" class="btn btn-success btn-lg" name="submit" value="buy">Buy</button>
                                        </div>
                                        <div class="col d-grid">
                                            <button type="submit" class="btn btn-success btn-lg" name="submit" value="addtocard">Add To Cart</button>
                                        </div>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Close Content -->


        <?php include_once 'views/home/template/footer.php'; ?>
        
        <?php
            include_once 'vendor/jQuery/bundle.php';
            include_once 'vendor/FontAwesome/kit.php';
            include_once 'vendor/Bootstrap/js/bundle.php'; 
        ?>
        <script src="vendor/NotifyJS/js/notify.js"></script>
        <script src="asset/js/home/cart.js"></script>
    </body>
</html>