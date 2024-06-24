<?php

require_once __DIR__.'/router.php';

//*****************************************************************//

    // Home Views
    get('/', 'views/home/home.php');
    get('/about', 'views/home/about.php');
    get('/shop/$filter', 'views/home/shop.php');
    get('/view-product/product/$product_id', 'views/home/view-product.php');
    get('/cart', 'views/home/cart.php');
    get('/account/settings', 'views/home/account.php');
    get('/my-purchases/$status', 'views/home/my-purchases.php');

    // Cart Controller
    any('/add-cart', 'controller/home/cart/add-cart.php');
    any('/add-qty', 'controller/home/cart/add-qty.php');
    any('/sub-qty', 'controller/home/cart/sub-qty.php');
    any('/check-product', 'controller/home/cart/check-product.php');
    any('/uncheck-product', 'controller/home/cart/uncheck-product.php');
    any('/delivery-fee', 'controller/home/cart/delivery-fee.php');
    any('/cart-total', 'controller/home/cart/cart-total.php');
    any('/checkout', 'controller/home/cart/checkout.php');

    // User Controller
    any('/has-address', 'controller/home/user/has-address.php');
    any('/new-address', 'controller/home/user/new-address.php');
    any('/get-municipality', 'controller/home/user/get-municipality.php');

    // My Purchases Controller
    any('/get-qr', 'controller/home/my-purchases/get-qr.php');
    any('/order-details', 'controller/home/my-purchases/get-order-details.php');
    any('/upload-proof', 'controller/home/my-purchases/upload-proof.php');

    // Shop Controller
    any('/search-product', 'controller/home/shop/search-product.php');

//*****************************************************************//

    // Auth
    get('/login', 'views/auth/login.php');
    get('/register', 'views/auth/register.php');
    get('/verify-account/$code/$email', 'views/auth/verify.php');
    get('/verify-success', 'views/auth/verify-success.php');
    get('/reset-password/$code/$email', 'views/auth/reset-password.php');

    // Auth Controller
    any('/user-login', 'controller/auth/login-controller.php');
    any('/user-register', 'controller/auth/register-controller.php');
    any('/logout', 'controller/auth/logout-controller.php');
    any('/forgot-password', 'controller/auth/forgot-password.php');
    any('/new-password', 'controller/auth/new-password.php');

//*****************************************************************//

    // Admin Dashboard
    get('/dashboard', 'views/admin/dashboard.php');

//*****************************************************************//

    // Management and Reports
    get('/manage-business', 'views/admin/manage-business.php');

    // Add Expenses Controller
    any('/add-expenses', 'controller/admin/manage-business/add-expenses.php');

    // Delivery Fee Controller
    any('/get-df', 'controller/admin/manage-business/get-df.php');
    any('/update-df', 'controller/admin/manage-business/update-df.php');
    any('/update-df-status', 'controller/admin/manage-business/update-df-status.php');

    // People
    get('/users', 'views/admin/user.php');
    get('/staff', 'views/admin/staff.php');

    // Staff Controller
    any('/add-staff', 'controller/admin/staff/add-staff.php');
    any('/update-staff', 'controller/admin/staff/update-status.php');

//*****************************************************************//

    // Inventory Views
    get('/manage-products', 'views/admin/products.php');
    // get('/stocks', 'views/admin/stocks.php');
    // get('/restocks', 'views/admin/restock.php');
    // get('/price-list', 'views/admin/price-list.php');

    // Product Controller
    any('/new-product', 'controller/admin/product/new-product.php');
    any('/edit-product', 'controller/admin/product/edit-product.php');
    any('/disable-product', 'controller/admin/product/disable-product.php');
    any('/get-product', 'controller/admin/product/get-product.php');

    // Stocks Controller
    any('/add-stock', 'controller/admin/stocks/add-stock.php');
    any('/restock', 'controller/admin/stocks/restock.php');

    // Price List Controller
    any('/new-price', 'controller/admin/price/new-price.php');

//*****************************************************************//

    // Manage Orders Views
    get('/manage-orders', 'views/admin/order.php');
    get('/print-receipt/$order_ref', 'views/admin/receipt.php');

    // Confirm Order Views
    get('/scan-qr', 'views/admin/scan-qr.php');
    get('/confirm-order/$code/$order_ref', 'views/admin/confirm-order.php');

    // Manage Orders Controller
    any('/get-orders','controller/admin/order/get-orders.php');
    any('/paid-status', 'controller/admin/order/paid-status.php');
    any('/update-paid', 'controller/admin/order/update-paid.php');
    any('/order-status', 'controller/admin/order/order-status.php');
    any('/update-status', 'controller/admin/order/update-status.php');

    // Confirm Order Controller
    any('/confirm-order-status', 'controller/admin/order/confirm-order.php');

    // Proof of Payment Controller
    any('/get-proof', 'controller/admin/order/get-proof.php');

//*****************************************************************//

    // Manage Supplier Views
    get('/manage-suppliers', 'views/admin/suppliers.php');

    // Manage Supplier Controller
    any('/add-supplier', 'controller/admin/supplier/add-supplier.php');
    any('/get-supplier', 'controller/admin/supplier/get-supplier.php');
    any('/edit-supplier', 'controller/admin/supplier/edit-supplier.php');
    any('/update-supplier', 'controller/admin/supplier/update-supplier.php');

//*****************************************************************//

    // Maintenance Views
    get('/category', 'views/admin/category.php');
    get('/brands', 'views/admin/brands.php');
    get('/unit', 'views/admin/unit.php');

    // Category Controller
    any('/new-category', 'controller/admin/category/new-category.php');
    any('/disable-category', 'controller/admin/category/disable-category.php');
    any('/edit-category', 'controller/admin/category/edit-category.php');

    // Brand Controller
    any('/new-brand', 'controller/admin/brand/new-brand.php');
    any('/edit-brand', 'controller/admin/brand/edit-brand.php');
    any('/disable-brand', 'controller/admin/brand/disable-brand.php');

    // Unit Controller
    any('/new-unit', 'controller/admin/unit/new-unit.php');
    any('/edit-unit', 'controller/admin/unit/edit-unit.php');
    any('/disable-unit', 'controller/admin/unit/disable-unit.php');

//*****************************************************************//

    // POS Controller
    any('/pos', 'views/pos/pos.php');
    any('/pos-cart', 'views/pos/price_list_cart.php');
    any('/pos-addqty', 'views/pos/add-qty.php');
    any('/pos-minusqty', 'views/pos/minus-qty.php');
    any('/pos-ctbody', 'views/pos/cart-body.php');
    any('/pos-reset', 'views/pos/reset-card.php');
    any('/pos-removecart', 'views/pos/remove_cart.php');
    any('/pos-ctdiscount', 'views/pos/cart-discount.php');
    any('/pos-barcode', 'views/pos/barcode.php');
    any('/pos-addpos', 'views/pos/addPos.php');
    any('/pos-getpos', 'views/pos/getPos.php');
    any('/pos-updateqty', 'views/pos/update-qty.php');


//*****************************************************************//

    // Test
    get('/test/collapse', 'views/test/collapse.php');
    get('/dump', 'views/test/vardump.php');
    any('/test', 'views/test/test.php');
    get('/db-array', 'views/test/db_array.php');
    get('/send-mail', 'views/test/send_mail.php');

//*****************************************************************//

    // 404
    any('/404','views/404.php');

//*****************************************************************//