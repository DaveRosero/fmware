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
              <div class="col">
                <button class="btn btn-primary text-black fw-semibold mx-2">
                  <i class="fas fa-shipping-fast"></i>
                  Deliveries Today: 6
                </button>
              </div>
            </div>
            <div class="row">
              <div class="col">
                <button class="btn btn-success text-black fw-semibold mx-2">
                  <i class="fas fa-cash-register"></i>
                  Sales Today: 6
                </button>
              </div>
            </div>
            <div class="row">
              <div class="col">
                <button class="btn btn-warning text-black fw-semibold mx-2">
                  <i class="fas fa-spinner"></i>
                  Pending Orders: 6
                </button>
              </div>
            </div>
            <div class="row">
              <div class="col">
                <button class="btn btn-danger text-black fw-semibold mx-2">
                  <i class="fas fa-exclamation-triangle"></i>
                  Low on Stocks: 6
                </button>
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