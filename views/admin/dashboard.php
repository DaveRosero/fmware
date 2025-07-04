<?php
include_once 'session.php';
require_once 'model/admin/admin.php';
require_once 'model/user/logoutClass.php';

$admin = new Admin();
$logout = new Logout();
$admin->isAdmin();
?>
<!DOCTYPE html>
<html lang="en"> <!--begin::Head-->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Dashboard | FMWare</title><!--begin::Primary Meta Tags-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="title" content="Dashboard | FMWare">
    <?php include_once 'views/admin/include/style.php'; ?><!-- begin::Required Plugin(AdminLTE)-->
    <link rel="stylesheet" href="/asset/css/admin/adminlte.css"><!--end::Required Plugin(AdminLTE)-->
    <style>
        .card {
            display: flex;
            flex-direction: column;
        }

        .card-body {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        #sales-chart,
        #profits-chart {
            width: 100%;
            height: 100%;
            /* Make sure it fills the height of the card-body */
        }
    </style>

</head> <!--end::Head--> <!--begin::Body-->

<body class="layout-fixed-complete sidebar-expand-lg bg-body-tertiary"> <!--begin::App Wrapper-->
    <div class="app-wrapper">
        <?php require_once 'views/admin/template/header.php'; ?>
        <?php require_once 'views/admin/template/sidebar.php'; ?>
        <?php require_once 'views/admin/content/dashboard-content.php'; ?>
        <?php require_once 'views/admin/template/footer.php'; ?>
    </div> <!--end::App Wrapper--> <!--begin::Script-->
    <?php include_once 'views/admin/include/script.php'; ?><!--begin::Required Plugin(AdminLTE)-->
    <script src="/asset/js/admin/adminlte.js"></script> <!--end::Required Plugin(AdminLTE)--><!--begin::OverlayScrollbars Configure-->
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
    </script> <!--end::OverlayScrollbars Configure-->
    <script src="/asset/js/template/sidebar.js"></script>
    <script src="/asset/js/template/theme.js"></script>
    <script src="/asset/js/admin/dashboard.js"></script> <!--end::Script-->
</body><!--end::Body-->

</html>