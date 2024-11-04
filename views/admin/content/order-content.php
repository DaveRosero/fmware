<!--begin::App Main-->
<main class="app-main"> <!--begin::App Content Header-->
    <div class="app-content-header"> <!--begin::Container-->
        <div class="container-fluid"> <!--begin::Row-->
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Manage Orders</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item">Transactions</li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Online Orders
                        </li>
                    </ol>
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
                            <div class="table-responsive">
                                <table class="table table-borderless w-100" id="order-table">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Order Ref</th>
                                            <th class="text-center">Customer</th>
                                            <th class="text-center">Date</th>
                                            <th class="text-center">Transaction</th>
                                            <th class="text-center">Payment</th>
                                            <th class="text-center">Total</th>
                                            <th class="text-center">Paid</th>
                                            <th class="text-center">Status</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="order-table-content">
                                        <?php echo $order->getOrders(); ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div> <!--end::Row-->
        </div>
    </div> <!--end::App Content-->
</main> <!--end::App Main-->

<div class="d-none" id="printContent">
    <table class="table table-borderless">
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
            </tr>
        </thead>
        <tbody id="order-table-content">
            <?php echo $order->getPrintOrders(); ?>
        </tbody>
    </table>
</div>