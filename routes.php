<?php

require_once __DIR__.'/router.php';

//*****************************************************************//

    // Landing Page
    get('/fmware', 'views/home/home.php');
    get('/fmware/about', 'views/home/about.php');
    get('/fmware/shop', 'views/home/shop.php');

//*****************************************************************//

    // Sub Landing Page
    get('/fmware/view-product', 'views/home/view-product.php');

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

    // Inventory
    get('/fmware/products', 'views/admin/products.php');

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

//*****************************************************************//

    // Test
    get('/fmware/test/collapse', 'views/test/collapse.php');
    get('/fmware/dump', 'views/test/vardump.php');
    get('/fmware/test', 'views/test/test.php');

//*****************************************************************//

    // 404
    any('/404','views/404.php');

//*****************************************************************//