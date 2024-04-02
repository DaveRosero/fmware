<div class="container-fluid">
    <!--Order Table Start-->
    <div class="card">
        <div class="card-body">
            <div class="row justify-content-between">
                <div class="col">
                    <h5 class="card-title fw-semibold mb-4">Order List</h5>
                </div>
                <div class="col text-end">
                    <div id="printButtonContainer" class="d-inline-block">
                        <!-- DataTables print button will be placed here -->
                    </div>
                </div>
            </div>
            <table id="order-table">
                <thead>
                    <tr>
                        <th>Order Ref</th>
                        <th>Customer</th>
                        <th>Order Date</th>
                        <th>Transaction Type</th>
                        <th>Payment Method</th>
                        <th>Gross Amount</th>
                        <th>Net Profit</th>
                        <th>Paid</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $order->getOrders(); ?>
                </tbody>
            </table>
        </div>
    </div>
    <!--Brand Table End-->
</div>

