<?php

require_once __DIR__.'/router.php';

//*****************************************************************//

    // Home Views
    get('/fmware', 'views/home/home.php');
    get('/fmware/about', 'views/home/about.php');
    get('/fmware/shop', 'views/home/shop.php');
    get('/fmware/view-product', 'views/home/view-product.php');
    get('/fmware/cart', 'views/home/cart.php');

    // Cart Controller
    any('/fmware/add-cart', 'controller/home/cart/add-cart.php');

//*****************************************************************//

    // Auth
    get('/fmware/login', 'views/auth/login.php');
    get('/fmware/register', 'views/auth/register.php');

    // Auth Controller
    any('/fmware/user-login', 'controller/auth/login-controller.php');
    any('/fmware/user-register', 'controller/auth/register-controller.php');
    any('/fmware/logout', 'controller/auth/logout-controller.php');

//*****************************************************************//

    // Admin Dashboard
    get('/fmware/dashboard', 'views/admin/dashboard.php');

//*****************************************************************//

    // People
    get('/fmware/groups', 'views/admin/groups.php');
    get('/fmware/users', 'views/admin/userList.php');
    get('/fmware/staff', 'views/admin/staffList.php');

//*****************************************************************//

    // Inventory Views
    get('/fmware/products', 'views/admin/products.php');
    get('/fmware/stocks', 'views/admin/stocks.php');
    get('/fmware/restocks', 'views/admin/restock.php');
    get('/fmware/price-list', 'views/admin/price-list.php');

    // Product Controller
    any('/fmware/new-product', 'controller/admin/product/new-product.php');
    any('/fmware/edit-product', 'controller/admin/product/edit-product.php');
    any('/fmware/disable-product', 'controller/admin/product/disable-product.php');

    // Stocks Controller
    any('/fmware/add-stock', 'controller/admin/stocks/add-stock.php');
    any('/fmware/restock', 'controller/admin/stocks/restock.php');

    // Price List Controller
    any('/fmware/new-price', 'controller/admin/price/new-price.php');

//*****************************************************************//

    // Maintenance Views
    get('/fmware/category', 'views/admin/category.php');
    get('/fmware/brands', 'views/admin/brands.php');
    get('/fmware/unit', 'views/admin/unit.php');

    // Category Controller
    any('/fmware/new-category', 'controller/admin/category/new-category.php');
    any('/fmware/disable-category', 'controller/admin/category/disable-category.php');
    any('/fmware/edit-category', 'controller/admin/category/edit-category.php');

    // Brand Controller
    any('/fmware/new-brand', 'controller/admin/brand/new-brand.php');
    any('/fmware/edit-brand', 'controller/admin/brand/edit-brand.php');
    any('/fmware/disable-brand', 'controller/admin/brand/disable-brand.php');

    // Unit Controller
    any('/fmware/new-unit', 'controller/admin/unit/new-unit.php');
    any('/fmware/edit-unit', 'controller/admin/unit/edit-unit.php');
    any('/fmware/disable-unit', 'controller/admin/unit/disable-unit.php');

//*****************************************************************//

    // Test
    get('/fmware/test/collapse', 'views/test/collapse.php');
    get('/fmware/dump', 'views/test/vardump.php');
    get('/fmware/test', 'views/test/test.php');

//*****************************************************************//

    // 404
    any('/404','views/404.php');

//*****************************************************************//