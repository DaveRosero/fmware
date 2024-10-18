<!-- Header -->
<nav class="navbar navbar-expand-lg navbar-light shadow sticky-top bg-white">
    <div class="container d-flex justify-content-between align-items-center">

        <a class="navbar-brand logo h1 align-self-center" href="/" style="color: #fcc404;">
            FMWare
        </a>

        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#templatemo_main_nav" aria-controls="templatemo_main_nav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse flex-fill d-lg-flex justify-content-lg-between align-items-center" id="templatemo_main_nav">
            <ul class="navbar-nav mx-lg-auto">
                <li class="nav-item">
                    <a class="nav-link mx-3" href="/">Home</a>
                </li>
                <li class="nav-item mx-3">
                    <a class="nav-link" href="/about">About</a>
                </li>
                <li class="nav-item mx-3">
                    <a class="nav-link" href="/shop/all">Shop</a>
                </li>
                <li class="nav-item mx-3">
                    <a class="nav-link" href="/contact">Contact</a>
                </li>
            </ul>

            <div class="navbar d-flex align-items-center">
                <?php if ($user->isLoggedIn()): ?>
                    <!-- Cart Icon with Cart Count -->
                    <a class="nav-icon position-relative text-decoration-none" id="cart" href="/cart">
                        <i class="fa fa-fw fa-cart-arrow-down text-dark mr-1"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-light text-dark" id="cart-count">
                            <?php
                            $cart_count = $cart->cartCount($_SESSION['user_id']);
                            echo $cart_count;
                            ?>
                        </span>
                    </a>

                    <!-- User Dropdown for Account and Logout -->
                    <div class="dropdown ms-3">
                        <a class="nav-icon position-relative text-decoration-none dropdown-toggle" href="#" role="button" id="userDropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-fw fa-user text-dark mr-3"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <a class="dropdown-item" href="/account/settings">My Account</a>
                            <div class="dropdown-divider"></div>
                            <form action="/logout" method="post">
                                <button type="submit" class="dropdown-item">Logout</button>
                            </form>
                        </div>
                    </div>

                <?php else: ?>
                    <!-- Login and Register Buttons for Unauthenticated Users -->
                    <a class="nav-icon text-decoration-none d-lg-inline-block" href="/login">
                        <i class="fas fa-sign-in-alt text-dark mr-2"></i> Login
                    </a>
                    <a class="nav-icon text-decoration-none d-lg-inline-block ms-3" href="/register">
                        <i class="fas fa-user-plus text-dark mr-2"></i> Register
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>
<!-- Close Header -->