<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <h3 class="mb-0">Manage Deliveries</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <!-- Search and Sort Section -->
                    <div class="d-flex justify-content-between">
                        <input type="text" id="search-input" class="form-control me-2" placeholder="Search by Order Ref or POS Ref">

                        <div class="dropdown">
                            <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Sort By
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#" data-sort="All">All</a></li>
                                <li><a class="dropdown-item" href="#" data-sort="Order Ref">Order Ref</a></li>
                                <li><a class="dropdown-item" href="#" data-sort="POS Ref">POS Ref</a></li>
                                <li><a class="dropdown-item" href="#" data-sort="Date">Date</a></li>
                                <li><a class="dropdown-item" href="#" data-sort="Paid">Paid</a></li>
                                <li><a class="dropdown-item" href="#" data-sort="Unpaid">Unpaid</a></li>
                            </ul>
                        </div>
                    </div>
                    <!-- Accepted Orders Container -->
                    <div id="accepted-orders-container" class="mt-3">
                        <!-- Accepted orders will be dynamically appended here -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>