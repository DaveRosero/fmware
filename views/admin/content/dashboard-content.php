<div class="container-fluid">

    <div class="row">

        <!-- Total Products Card -->
        <div class="col">
            <div class="card shadow">
                <div class="card-body">
                    <div class="row align-items-start">
                        <div class="col ">
                            <h5 class="card-title mb-9 fw-semibold">Total Products</h5>
                        </div>
                        <div class="col">
                            <h4 class="fw-semibold mb-3"><i class="fas fa-barcode px-4"></i><?php $admin->getTotalProducts(); ?></h4>
                        </div>
                        <p class="mb-0 text-end"><a href="/products">View details ></a></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Suppliers Card -->
        <div class="col">
            <div class="card shadow">
                <div class="card-body">
                    <div class="row align-items-start">
                        <div class="col ">
                            <h5 class="card-title mb-9 fw-semibold">Total Suppliers</h5>
                        </div>
                        <div class="col">
                            <h4 class="fw-semibold mb-3"><i class="fas fa-truck px-4"></i><?php $admin->getTotalSuppliers(); ?></h4>
                        </div>
                        <p class="mb-0 text-end"><a href="/suppliers">View details ></a></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Users Card -->
        <div class="col">
            <div class="card shadow">
                <div class="card-body">
                    <div class="row align-items-start">
                        <div class="col">
                            <h5 class="card-title mb-9 fw-semibold">Total Users</h5>
                        </div>
                        <div class="col">
                            <h4 class="fw-semibold mb-3"><i class="fas fa-users px-4"></i><?php $admin->getTotalUsers(); ?></h4>
                        </div>
                        <p class="mb-0 text-end"><a href="/users">View details ></a></p>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="row">

        <!-- Daily Sales - Order Card -->
        <div class="col">
            <div class="card shadow">
                <div class="card-header">
                    <h5 class="badge bg-secondary"><strong>ORDERS</strong></h5>
                    <h5 class="badge bg-success"><strong>Daily Sales</strong></h5>
                </div>
                <div class="card-body">
                    <div class="row align-items-start">
                        <div class="col ">
                        <h5 class="card-title mb-9 fw-semibold">Gross Profit</h5>
                        </div>
                        <div class="col">
                        <h4 class="fw-semibold mb-3"><?php $admin->getDailyOrderGross(); ?></h4>
                        </div>
                    </div>
                    <div class="row align-items-start">
                        <div class="col ">
                        <h5 class="card-title mb-9 fw-semibold">Net Profit</h5>
                        </div>
                        <div class="col">
                        <h4 class="fw-semibold mb-3 text-success    "><?php $admin->getDailyOrderNet(); ?></h4>
                        </div>
                        <p class="mb-0 text-end"><a href="/manage-orders">View details ></a></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Daily Sales - POS Card -->
        <div class="col">
            <div class="card shadow">
                <div class="card-header">
                    <h5 class="badge bg-secondary"><strong>POINT OF SALE</strong></h5>
                    <h5 class="badge bg-success"><strong>Daily Sales</strong></h5>
                </div>
                <div class="card-body">
                    <div class="row align-items-start">
                        <div class="col ">
                        <h5 class="card-title mb-9 fw-semibold">Gross Profit</h5>
                        </div>
                        <div class="col">
                        <h4 class="fw-semibold mb-3"><?php $admin->getDailyPOSGross(); ?></h4>
                        </div>
                    </div>
                    <div class="row align-items-start">
                        <div class="col ">
                        <h5 class="card-title mb-9 fw-semibold">Net Profit</h5>
                        </div>
                        <div class="col">
                        <h4 class="fw-semibold mb-3 text-success    "><?php $admin->getDailyPOSNet(); ?></h4>
                        </div>
                        <p class="mb-0 text-end"><a href="/manage-orders">View details ></a></p>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="row">

        <!-- Recent Transactions Card -->
        <div class="col">
            <div class="card shadow">
                <div class="card-body">
                    <div class="row justify-content-between">
                        <div class="col">
                            <h5 class="card-title fw-semibold mb-4">Recent Transaction</h5>
                        </div>
                    </div>
                    
                    <form>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Type</th>
                                    <th>Total</th>
                                    <th>Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                <td>POS</td>
                                <td>P100.00</td>
                                <td>09:30</td>
                            </tbody>
                        </table>
                    </form>
                </div>
            </div>
        </div>

        <!-- Logs Card -->
        <div class="col-8">
            <div class="card shadow">
                <div class="card-body">
                    <div class="row justify-content-between">
                        <div class="col">
                            <h5 class="card-title fw-semibold mb-4">Logs</h5>
                        </div>
                    </div>
                    
                    <form>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Action</th>
                                    <th>Description</th>
                                    <th>Author</th>
                                    <th>Date & Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                <td>Inventory Update</td>
                                <td>Added PRODUCT_1 to Products</td>
                                <td>admin</td>
                                <td>March 04, 2024</td>
                            </tbody>
                        </table>
                    </form>
                </div>
            </div>
        </div>

    </div>

</div>