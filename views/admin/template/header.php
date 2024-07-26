<header>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand fw-semibold d-flex align-items-center" href="/dashboard">
                <img src="/asset/images/store/logo.png" alt="Icon" class="me-2" style="width: 36px; height: 36px;">
                FMWare
            </a>
            <div class="navbar-collapse justify-content-end" id="navbarNav">
                <div class="nav-item position-relative">
                    <i class="fas fa-search position-absolute" style="top: 50%; left: 10px; transform: translateY(-50%);"></i>
                    <input type="text" class="form-control ps-5" placeholder="Search...">
                </div>
            </div>
            <div class="navbar-collapse justify-content-end px-0" id="navbarNav">
                <div class="nav-item dropdown position-relative me-3">
                    <a class="nav-link text-light position-relative" href="#" id="notificationDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-bell fs-5"></i>
                        <span id="notificationBadge" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"> 
                            <!-- Badge element -->
                        </span>
                    </a>
                    <ul id="notificationMenu" class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationDropdown">
                        <li><a class="dropdown-item" href="#">Notification 1</a></li>
                        <li><a class="dropdown-item" href="#">Notification 2</a></li>
                    </ul>
                </div>
                <div class="nav-item me-3">
                    <i id="expandButton" class="fas fa-expand fs-5 text-light"></i>
                </div>
                <div class="dropdown">
                    <a class="nav-icon position-relative text-decoration-none text-light dropdown-toggle text-white d-flex align-items-center" href="#" role="button" id="userDropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-circle-user fs-5 me-2"></i>Hi, Admin
                    </a>
                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                        <form action="/logout" method="post">
                            <button type="submit" class="dropdown-item">Logout</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </nav>
</header>
