<!--begin::Header-->
<nav class="app-header navbar navbar-expand bg-body"> <!--begin::Container-->
    <div class="container-fluid"> <!--begin::Start Navbar Links-->
        <ul class="navbar-nav">
            <li class="nav-item"> <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button"> <i class="bi bi-list"></i> </a> </li>
        </ul> <!--end::Start Navbar Links--> <!--begin::End Navbar Links-->
        <ul class="navbar-nav ms-auto"> <!--begin::Notifications Dropdown Menu-->
            <li class="nav-item dropdown">
                <button
                    class="btn btn-link nav-link py-2 px-0 px-lg-2 dropdown-toggle d-flex align-items-center"
                    id="bd-theme"
                    type="button"
                    aria-expanded="false"
                    data-bs-toggle="dropdown"
                    data-bs-display="static">
                    <span class="theme-icon-active">
                        <i class="my-1"></i>
                    </span>
                    <span class="d-lg-none ms-2" id="bd-theme-text">Toggle theme</span>
                </button>
                <ul
                    class="dropdown-menu dropdown-menu-end"
                    aria-labelledby="bd-theme-text"
                    style="--bs-dropdown-min-width: 8rem;">
                    <li>
                        <button
                            type="button"
                            class="dropdown-item d-flex align-items-center active"
                            data-bs-theme-value="light"
                            aria-pressed="false">
                            <i class="bi bi-sun-fill me-2"></i>
                            Light
                            <i class="bi bi-check-lg ms-auto d-none"></i>
                        </button>
                    </li>
                    <li>
                        <button
                            type="button"
                            class="dropdown-item d-flex align-items-center"
                            data-bs-theme-value="dark"
                            aria-pressed="false">
                            <i class="bi bi-moon-fill me-2"></i>
                            Dark
                            <i class="bi bi-check-lg ms-auto d-none"></i>
                        </button>
                    </li>
                    <li>
                        <button
                            type="button"
                            class="dropdown-item d-flex align-items-center"
                            data-bs-theme-value="auto"
                            aria-pressed="true">
                            <i class="bi bi-circle-fill-half-stroke me-2"></i>
                            Auto
                            <i class="bi bi-check-lg ms-auto d-none"></i>
                        </button>
                    </li>
                </ul>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link" data-bs-toggle="dropdown" href="#">
                    <i class="bi bi-bell-fill"></i>
                    <span class="navbar-badge badge text-bg-danger"></span>
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item">
                        4 new messages
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item">
                        8 friend requests
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item">
                        3 new reports
                    </a>
                </div>
            </li> <!--end::Notifications Dropdown Menu--> <!--begin::Fullscreen Toggle-->
            <li class="nav-item"> <a class="nav-link" href="#" data-lte-toggle="fullscreen"> <i data-lte-icon="maximize" class="bi bi-arrows-fullscreen"></i> <i data-lte-icon="minimize" class="bi bi-fullscreen-exit" style="display: none;"></i> </a> </li> <!--end::Fullscreen Toggle--> <!--begin::User Menu Dropdown-->
            <form action="/logout" method="post">
                <button type="submit" class="btn btn-outline-danger">Logout</button>
            </form> <!--end::User Menu Dropdown-->
        </ul> <!--end::End Navbar Links-->
    </div> <!--end::Container-->
</nav> <!--end::Header-->