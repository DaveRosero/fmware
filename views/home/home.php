<?php
    include_once 'session.php';
    require_once 'model/user/user.php';

    $user = new User();
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
        <!-- Header -->
        <nav class="navbar navbar-expand-lg navbar-light shadow">
            <div class="container d-flex justify-content-between align-items-center">

                <a class="navbar-brand logo h1 align-self-center" href="/fmware" style="color: #fcc404;">
                    FMWare
                </a>

                <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#templatemo_main_nav" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="align-self-center collapse navbar-collapse flex-fill  d-lg-flex justify-content-lg-between" id="templatemo_main_nav">
                    <div class="flex-fill">
                        <ul class="nav navbar-nav d-flex justify-content-between mx-lg-auto">
                            <li class="nav-item">
                                <a class="nav-link" href="/fmware">Home</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/fmware/about">About</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/fmware/shop">Shop</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/fmware/contact">Contact</a>
                            </li>
                        </ul>
                    </div>
                    <div class="navbar align-self-center d-flex">
                        <div class="d-lg-none flex-sm-fill mt-3 mb-4 col-7 col-sm-auto pr-3">
                            <div class="input-group">
                                <input type="text" class="form-control" id="inputMobileSearch" placeholder="Search ...">
                                <div class="input-group-text">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                </div>
                            </div>
                        </div>
                        <a class="nav-icon d-none d-lg-inline" href="#" data-bs-toggle="modal" data-bs-target="#templatemo_search">
                            <i class="fa fa-fw fa-search text-dark mr-2"></i>
                        </a>
                        
                        <?php if ($user->isLoggedIn()): ?>
                            <a class="nav-icon position-relative text-decoration-none" href="#">
                                <i class="fa fa-fw fa-cart-arrow-down text-dark mr-1"></i>
                                <span class="position-absolute top-0 left-100 translate-middle badge rounded-pill bg-light text-dark">7</span>
                            </a>
                            <div class="dropdown">
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                    <a class="dropdown-item" href="#">Profile</a>
                                    <a class="dropdown-item" href="#">Settings</a>
                                    <div class="dropdown-divider"></div>
                                    <form action="/fmware/logout" method="post">
                                        <button type="submit" class="dropdown-item">Logout</button>
                                    </form>
                                    
                                </div>
                                <a class="nav-icon position-relative text-decoration-none dropdown-toggle" href="#" role="button" id="userDropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fa fa-fw fa-user text-dark mr-3"></i>
                                </a>
                            </div>

                        <?php elseif (!$user->isLoggedIn()): ?>
                            <a class="nav-icon d-none d-lg-inline text-decoration-none" href="/fmware/login">
                                <i class="fas fa-sign-in-alt text-dark mr-2"></i> Login
                            </a>
                            <a class="nav-icon d-none d-lg-inline text-decoration-none" href="/fmware/register">
                                <i class="fas fa-user-plus text-dark mr-2"></i> Register
                            </a>
                        <?php endif; ?>
                    </div>
                </div>

            </div>
        </nav>
        <!-- Close Header -->

        <!-- Modal -->
        <div class="modal fade bg-white" id="templatemo_search" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="w-100 pt-1 mb-5 text-right">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" method="get" class="modal-content modal-body border-0 p-0">
                    <div class="input-group mb-2">
                        <input type="text" class="form-control" id="inputModalSearch" name="q" placeholder="Search ...">
                        <button type="submit" class="input-group-text bg-success text-light">
                            <i class="fa fa-fw fa-search text-white"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>



        <!-- Start Banner Hero -->
        <div id="template-mo-zay-hero-carousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <div class="container">
                        <img class="img-fluid" src="asset/images/store/caroBanner1.png" alt="">
                    </div>
                </div>
                <div class="carousel-item">
                    <div class="container">
                        <img class="img-fluid" src="asset/images/store/caroBanner2.png" alt="">
                    </div>
                </div>
                <div class="carousel-item">
                    <div class="container">
                        <img class="img-fluid" src="asset/images/store/caroBanner3.png" alt="">
                    </div>
                </div>
            </div>
            <a class="carousel-control-prev text-decoration-none w-auto ps-3" href="#template-mo-zay-hero-carousel" role="button" data-bs-slide="prev">
                <i class="fas fa-chevron-left" style="color: #fcc404; font-size: 24px;"></i>
            </a>
            <a class="carousel-control-next text-decoration-none w-auto pe-3" href="#template-mo-zay-hero-carousel" role="button" data-bs-slide="next">
                <i class="fas fa-chevron-right" style="color: #fcc404; font-size: 24px;"></i>
            </a>
        </div>
        <!-- End Banner Hero -->


        <!-- Start Categories of The Month -->
        <section class="container py-5">
            <div class="row text-center pt-3">
                <div class="col-lg-6 m-auto">
                    <h1 class="h1">Categories of The Month</h1>
                    <p>
                        #
                    </p>
                </div>
            </div>
        </section>
        <!-- End Categories of The Month -->


        <!-- Start Featured Product -->
        <section class="bg-light">
            <div class="container py-5">
                <div class="row text-center py-3">
                    <div class="col-lg-6 m-auto">
                        <h1 class="h1">Featured Product</h1>
                        <p>
                            #
                        </p>
                    </div>
                </div>
            </div>
        </section>
        <!-- End Featured Product -->


        <!--Footer Start-->
        <div class="container mx-auto mt-4">
            <div class="row">
                <!--Contact Us-->
                <div class="col-4">
                    <h5>Contact Us</h5>
                    <h5>Address:</h5>
                    <p>Mac Arthur Highway, Poblacion II, Marilao, Bulacan</p>
                    <h5>Contact:</h5>
                    <p>(+63) 922-803-3898</p>
                    <h5>Socials:</h5>
                    <a class="btn text-start" href="#" role="button"><img class="me-2" src="#" alt=""
                            style="width: 30px" />facebook.com/fmodulio</a>
                </div>
                <!--Contact Us-->
                <!--Customer Care-->
                <div class="col-2">
                    <h5>Customer Care</h5>
                </div>
                <!--Customer Care-->
                <!--News Letter-->
                <div class="col-6">
                    <div class="row d-flex align-items-center">
                        <div class="col">
                            <h5>Sign Up For Newsletters</h5>
                            <p>Get Emails updates about our latest shop and special offers</p>
                        </div>
                        <div class="col">
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" placeholder="Your Email Address"
                                    aria-label="Recipient's email" aria-describedby="button-addon2" />
                                <button class="btn btn-outline-success" type="button" id="button-addon2">
                                    Submit
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <!--News Letter-->
            </div>
            <div class="row text-center">
                <p>© FMWARE 2024</p>
            </div>
        </div>
        <!--Footer End-->
        
    <?php
        include_once 'vendor/jQuery/bundle.php';
        include_once 'vendor/FontAwesome/kit.php';
        include_once 'vendor/Bootstrap/js/bundle.php'; 
    ?>
    </body>
</html>