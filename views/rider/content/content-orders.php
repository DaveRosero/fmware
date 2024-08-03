<!--begin::App Main-->
<main class="app-main"> <!--begin::App Content Header-->
    <div class="app-content-header"> <!--begin::Container-->
        <div class="container-fluid"> <!--begin::Row-->
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Manage Deliveries</h3>
                </div>
            </div> <!--end::Row-->
        </div> <!--end::Container-->
    </div> <!--end::App Content Header--> <!--begin::App Content-->
    <div class="app-content"> <!--begin::Container-->
        <div class="container-fluid"> <!--begin::Row-->
            <div class="row">
                <div class="col-12 col-sm-12 col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th class="text-center">Order Ref</th>
                                        <th class="text-center">Customer</th>
                                        <th class="text-center">Date</th>
                                        <th class="text-center">Transaction</th>
                                        <th class="text-center">Payment</th>
                                        <th class="text-center">Total</th>
                                        <th class="text-center">Profit</th>
                                        <th class="text-center">Paid</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center"><i class="bi bi-gear-fill"></i></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th>FMO_AE86B12314</th>
                                        <td>Larry</td>
                                        <td>July 18, 2024</td>
                                        <td>Online Order</td>
                                        <td>COD</td>
                                        <td>$9999.00</td>
                                        <td>$5000.00</td>
                                        <td><span class="badge text-bg-danger">UNPAID</span></td>
                                        <td><span class="badge text-bg-primary">DELIVERING</span></td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="bi bi-three-dots"></i>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li><a class="dropdown-item" href="#">View</a></li>
                                                    <li><a class="dropdown-item" href="#">Cancel Delivery</a></li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div> <!--end::Row-->
        </div>
    </div> <!--end::App Content-->
</main> <!--end::App Main-->