<?php

require_once __DIR__.'/router.php';

// Landing Page
any('/fmware', 'views/home/home.php');
any('/fmware/about', 'views/home/about.php');
any('/fmware/shop', 'views/home/shop.php');

// Sub Landing Page
any('/fmware/view-product', 'views/home/view-product.php');

// Auth
any('/fmware/login', 'views/auth/login.php');
any('/fmware/register', 'views/auth/register.php');
// Admin Dashboard
any('/fmware/dashboard', 'views/admin/dashboard.php');

// People
any('/fmware/groups', 'views/admin/groups.php');
any('/fmware/users', 'views/admin/userList.php');
any('/fmware/staff', 'views/admin/staffList.php');

// Inventory
any('/fmware/products', 'views/admin/products.php');

// Maintenance
any('/fmware/attributes', 'views/admin/attributes.php');
any('/fmware/category', 'views/admin/category.php');
any('/fmware/brands', 'views/admin/brands.php');
any('/fmware/unit', 'views/admin/unit.php');

// Test
any('/fmware/test/collapse', 'views/test/collapse.php');
any('/fmware/dump', 'views/test/vardump.php');
any('/fmware/test', 'views/test/test.php');

// 404
any('/404','views/404.php');