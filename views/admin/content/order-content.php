<!--Order Table Start-->
<div class="card">
    <div class="card-body">
        <div class="row justify-content-between">
            <div class="col">
                <h5 class="card-title fw-semibold mb-4">Order List</h5>
            </div>
            <div class="col text-end">
                <button class="btn btn-primary" id="printButton"><i class="fa-solid fa-print"></i></button>
            </div>
        </div>
        <table class="table table-borderless" id="order-table">
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
                    <th class="text-center"><i class="fas fa-cog"></i></th>
                </tr>
            </thead>
            <tbody id="order-table-content">
                <?php echo $order->getOrders(); ?>
            </tbody>
        </table>
    </div>
</div>
<!--Brand Table End-->


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