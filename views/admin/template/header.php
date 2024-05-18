    <!--  Header Start -->
    <header class="app-header bg-dark">
        <nav class="navbar navbar-expand-lg navbar-light">
          <ul class="navbar-nav">
            <li class="nav-item d-block d-xl-none">
              <a class="nav-link sidebartoggler nav-icon-hover" id="headerCollapse" href="javascript:void(0)">
              <i class="fa-solid fa-bars"></i>
              </a>
            </li>
          </ul>
          <div class="navbar-collapse px-0" id="navbarNav">
            <div class="row">
              <div class="col position-relative">
                <i class="fa-solid fa-bell text-secondary fs-6"></i>
                <span class="badge bg-danger rounded-pill position-absolute top-0 start-100 translate-middle">
                    5
                </span>
              </div>
            </div>
          </div>
          <div class="navbar-collapse justify-content-end px-0" id="navbarNav">
            <form action="/logout" method="POST">
              <button type="submit" class="btn btn-danger mx-3 mt-2 d-block">Logout</button>
            </form>
          </div>
        </nav>
      </header>
      <!--  Header End -->