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
                <span class="badge bg-success text-black fw-semibold mx-2 py-6">
                  <i class="fas fa-cash-register"></i>
                  Sales Today: 6
                </span>
              </div>
            </div>
            <div class="row">
              <div class="col">
                <span class="badge bg-success text-black fw-semibold mx-2 py-6">
                  <i class="fas fa-shipping-fast"></i>
                  Deliveries Today: 6
                </span>
              </div>
            </div>
            <div class="row">
              <div class="col">
                <a href="/purchase-order">
                  <span class="badge bg-primary text-black fw-semibold mx-2 py-6">
                    <i class="fas fa-undo"></i>
                    Returns Today: 6
                  </span>
                </a>
              </div>
            </div>
            <div class="row">
              <div class="col">
                <a href="/manage-orders">
                  <span class="badge bg-warning text-black fw-semibold mx-2 py-6">
                    <i class="fas fa-spinner"></i>
                    Pending Orders: 6
                  </span>
                </a>
              </div>
            </div>
            <div class="row">
              <div class="col">
                <a href="/purchase-order">
                  <span class="badge bg-warning text-black fw-semibold mx-2 py-6">
                    <i class="fas fa-file"></i>
                    Pending PO: <?php $admin->getPendingPO(); ?>
                  </span>
                </a>
              </div>
            </div>
            <div class="row">
              <div class="col">
                <a href="/purchase-order">
                  <span class="badge bg-danger text-black fw-semibold mx-2 py-6">
                    <i class="fas fa-exclamation-triangle"></i>
                    Low on Stocks: 6
                  </span>
                </a>
              </div>
            </div>
          </div>
          <div class="navbar-collapse justify-content-end px-0" id="navbarNav">
            <div class="dropdown">
              <a class="nav-icon position-relative text-decoration-none fw-semibold dropdown-toggle text-white" href="#" role="button" id="userDropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  Hi, Admin
              </a>
              <div class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                  <form action="/logout" method="post">
                      <button type="submit" class="dropdown-item">Logout</button>
                  </form>
              </div>
          </div>
        </nav>
      </header>
      <!--  Header End -->