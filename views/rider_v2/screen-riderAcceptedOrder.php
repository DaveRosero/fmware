<?php
	include_once 'session.php';
	require_once 'model/admin/admin.php';
    require_once 'model/user/logoutClass.php';

    $admin = new Admin();
    $admin->isDelivery();
    $logout = new Logout();
    
?>

<!DOCTYPE html>
<html lang="en">
<!--begin::Head-->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Accepted Orders | FMWare</title><!--begin::Primary Meta Tags-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="title" content="Orders | FMWare">
    <?php include_once 'views/rider_v2/include/style.php'; ?><!-- begin::Required Plugin(AdminLTE)-->
    <link rel="stylesheet" href="/asset/css/admin/adminlte.css"><!--end::Required Plugin(AdminLTE)-->
    <link rel="stylesheet" href="/asset/css/rider_v2/style.css">
</head>
<!--end::Head-->

<!--begin::Body-->
<body class="layout-fixed-complete sidebar-expand-lg bg-body-tertiary">
    <!--begin::App Wrapper-->
    <div class="app-wrapper">
        <?php require_once 'views/rider_v2/template/header.php'; ?>
        <?php require_once 'views/rider_v2/template/sidebar.php'; ?>
        <?php require_once 'views/rider_v2/content/content-acceptedOrder.php'; ?>
        <?php require_once 'views/rider_v2/modal/modal-acceptedOrder.php'; ?>
        <?php require_once 'views/rider_v2/modal/modal-qrCode.php'; ?>
        <?php require_once 'views/rider_v2/template/footer.php'; ?>
    </div>
    <!--end::App Wrapper-->
    <!--begin::Script-->
    <?php include_once 'views/rider_v2/include/script.php'; ?>
    <!--begin::Required Plugin(AdminLTE)-->
    <script src="/asset/js/admin/adminlte.js"></script>
    <!--end::Required Plugin(AdminLTE)--><!--begin::OverlayScrollbars Configure-->
    <script>
        const SELECTOR_SIDEBAR_WRAPPER = ".sidebar-wrapper";
        const Default = {
            scrollbarTheme: "os-theme-light",
            scrollbarAutoHide: "leave",
            scrollbarClickScroll: true,
        };
        document.addEventListener("DOMContentLoaded", function() {
            const sidebarWrapper = document.querySelector(SELECTOR_SIDEBAR_WRAPPER);
            if (
                sidebarWrapper &&
                typeof OverlayScrollbarsGlobal?.OverlayScrollbars !== "undefined"
            ) {
                OverlayScrollbarsGlobal.OverlayScrollbars(sidebarWrapper, {
                    scrollbars: {
                        theme: Default.scrollbarTheme,
                        autoHide: Default.scrollbarAutoHide,
                        clickScroll: Default.scrollbarClickScroll,
                    },
                });
            }
        });
    </script>
    <!--end::OverlayScrollbars Configure-->
    <script src="/asset/js/template/sidebar.js"></script>
    <script src="/asset/js/template/theme.js"></script>
    <script src="/asset/js/rider_v2/rider.js"></script>
    <!--end::Script-->
</body>
<!--end::Body-->

</html>