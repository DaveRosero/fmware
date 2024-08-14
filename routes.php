<?php

require_once __DIR__ . '/router.php';

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
any('/cart-update-qty', 'controller/home/cart/update-qty.php');
any('/del-cart-item', 'controller/home/cart/del-cart-item.php');

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
get('/activity-logs', 'views/admin/logs.php');
get('/settings', 'views/admin/settings.php');
get('/create-reports', 'views/admin/create-reports.php');

// Reports Controller
any('/create-report-content', 'controller/admin/create-reports/create-report-content.php');

//*****************************************************************//

// Management and Reports
get('/manage-business', 'views/admin/manage-business.php');

// Add Expenses Controller
any('/add-expenses', 'controller/admin/manage-business/add-expenses.php');
any('/delete-expenses', 'controller/admin/manage-business/delete-expenses.php');

// Delivery Fee Controller
any('/get-df', 'controller/admin/manage-business/get-df.php');
any('/update-df', 'controller/admin/manage-business/update-df.php');
any('/update-df-status', 'controller/admin/manage-business/update-df-status.php');

// PIN Controller
any('/change-pin', 'controller/admin/manage-business/change-pin.php');
any('/reset-pin', 'controller/admin/manage-business/reset-pin.php');

// People
get('/users', 'views/admin/user.php');
get('/staff', 'views/admin/staff.php');

// Staff Controller
any('/add-staff', 'controller/admin/staff/add-staff.php');
any('/update-staff', 'controller/admin/staff/update-status.php');

// Manage User Controller
any('/update-user-status', 'controller/admin/user/update-user-status.php');

//*****************************************************************//

// Inventory Views
get('/manage-products', 'views/admin/products.php');
// get('/stocks', 'views/admin/stocks.php');
// get('/price-list', 'views/admin/price-list.php');
get('/purchase-orders', 'views/admin/purchase-order.php');
get('/create-po/$supplier/$po_ref', 'views/admin/create-po.php');
get('/receive-po/$supplier/$po_ref', 'views/admin/receive-po.php');
get('/view-po/$supplier/$po_ref', 'views/admin/view-po.php');

// Product Controller
any('/new-product', 'controller/admin/product/new-product.php');
any('/edit-product', 'controller/admin/product/edit-product.php');
any('/disable-product', 'controller/admin/product/disable-product.php');
any('/get-product', 'controller/admin/product/get-product.php');
any('/view-product', 'controller/admin/product/view-product.php');
any('/get-base-prices', 'controller/admin/product/base-prices.php');

// Stocks Controller
// any('/add-stock', 'controller/admin/stocks/add-stock.php');
// any('/restock', 'controller/admin/stocks/restock.php');

// Price List Controller
// any('/new-price', 'controller/admin/price/new-price.php');

// Purchase Order Controller
any('/redirect-po', 'controller/admin/purchase-order/redirect.php');
any('/add-po-item', 'controller/admin/purchase-order/add-po-item.php');
any('/get-po-item', 'controller/admin/purchase-order/get-po-item.php');
any('/del-po-item', 'controller/admin/purchase-order/del-po-item.php');
any('/qty-po-item', 'controller/admin/purchase-order/qty-po-item.php');
any('/price-po-item', 'controller/admin/purchase-order/price-po-item.php');
any('/unit-po-item', 'controller/admin/purchase-order/unit-po-item.php');
any('/po-remarks', 'controller/admin/purchase-order/po-remarks.php');
any('/save-po', 'controller/admin/purchase-order/save-po.php');
any('/receive-po-item', 'controller/admin/purchase-order/receive-po-item.php');
any('/update-receive-item', 'controller/admin/purchase-order/update-receive-item.php');
any('/update-po-shipping', 'controller/admin/purchase-order/update-po-shipping.php');
any('/update-po-others', 'controller/admin/purchase-order/update-po-others.php');
any('/complete-po', 'controller/admin/purchase-order/complete-po.php');
any('/delete-po', 'controller/admin/purchase-order/delete-po.php');
any('/actual-price-po', 'controller/admin/purchase-order/actual-price-po.php');
any('/update-srp-po', 'controller/admin/purchase-order/update-srp-po.php');

//*****************************************************************//

// Manage Orders Views
get('/manage-orders', 'views/admin/order.php');
get('/print-receipt/$order_ref', 'views/admin/receipt.php');

// Confirm Order Views
get('/scan-qr', 'views/admin/scan-qr.php');
get('/confirm-order/$code/$order_ref', 'views/admin/confirm-order.php');

// Manage Orders Controller
any('/get-orders', 'controller/admin/order/get-orders.php');
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

// Manage Sales View
get('/manage-sales', 'views/admin/sales.php');

// Manage Sales Controller
any('/view-sales', 'controller/admin/sales/view-sales.php');

//*****************************************************************//

// Manage Returns View
any('/manage-returns', 'views/admin/return.php');

// Manage Returns Controller

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
any('/pos-checkout', 'views/pos/model/checkout.php');
any('/pos-history', 'views/pos/model/history_fetchprod.php');
any('/pos-historyprod', 'views/pos/model/history_product.php');
any('/pos-transvoid', 'views/pos/model/historyvoid.php');
any('/pos-fetchTransactions', 'views/pos/model/transactions.php');
any('/pos-transactionDetails', 'views/pos/model/transactionDetails.php');
any('/pos-transactionItems', 'views/pos/model/transactionItems.php');
any('/pos-processRefund', 'views/pos/model/transactionRefund.php');
any('/pos-processReplace', 'views/pos/model/transactionReplace.php');
any('/pos-change', 'views/pos/qtychange.php');
any('/pos-search', 'views/pos/search.php');
any('/pos-fetchall', 'views/pos/fetchallprod.php');
any('/pos-pusearch', 'views/pos/model/pickup_searchprod.php');
any('/pos-puprod', 'views/pos/model/pickup_product.php');
any('/pos-puclaim', 'views/pos/model/pickupclaim.php');
any('/pos-puprepare', 'views/pos/model/pickup_prepared.php');

// RIDER 
//*****************************************************************//
// any('/rider-dashboard', 'views/rider/orders.php');
// any('/rider-orders', 'views/rider/orders.php');
// any('/rider-history', 'views/rider/history.php');
// any('/fetch-rider-orders', 'views/rider/models/fetch_orders.php');
// any('/fetch-order-items', 'views/rider/models/fetch_orderItems.php');
//*****************************************************************//

// Rider_v2
//*****************************************************************//
//screens
any('/rider-order', 'views/rider_v2/screen-riderOrder.php');
//modal
//model
any('/model-order', 'views/rider_v2/model/model-order.php');
any('/model-order-details', 'views/rider_v2/model/model-order-details.php');
any('/model-order-items', 'views/rider_v2/model/model-order-items.php');


//*****************************************************************//

// Test
get('/test/collapse', 'views/test/collapse.php');
get('/dump', 'views/test/vardump.php');
any('/test', 'views/test/test.php');
get('/db-array', 'views/test/db_array.php');
get('/send-mail', 'views/test/send_mail.php');

//*****************************************************************//

// 404
any('/404', 'views/404.php');

//*****************************************************************//