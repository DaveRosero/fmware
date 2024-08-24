<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <h3 class="mb-0">Manage Orders</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="app-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="d-flex">
                        <input type="text" class="form-control" placeholder="Search">
                        <div class="dropdown">
                            <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                               Sort By
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#">Order</a></li>
                                <li><a class="dropdown-item" href="#">Date</a></li>
                                <li><a class="dropdown-item" href="#">Paid</a></li>
                            </ul>
                        </div>
                    </div>
                    <div id="orders-container" class="mt-3">
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>