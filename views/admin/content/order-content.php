<div class="container-fluid">
    <!--Order Table Start-->
    <div class="card">
        <div class="card-body">
            <div class="row justify-content-between">
                <div class="col">
                    <h5 class="card-title fw-semibold mb-4">Order List</h5>
                </div>
                <div class="col text-end">
                    <button class="btn btn-secondary print"><i class="fa-solid fa-print"></i></button>
                </div>
            </div>
            <table id="order-table">
                <thead>
                    <tr>
                        <th>Order Ref</th>
                        <th>Customer</th>
                        <th>Date</th>
                        <th>Transaction</th>
                        <th>Payment</th>
                        <th>Total</th>
                        <th>Profit</th>
                        <th>Paid</th>
                        <th>Status</th>
                        <th><i class="fas fa-cog"></i></th>
                    </tr>
                </thead>
                <tbody id="order-table-content">
                    <?php echo $order->getOrders(); ?>
                </tbody>
            </table>
        </div>
    </div>
    <!--Brand Table End-->
</div>

