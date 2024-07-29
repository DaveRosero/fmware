<!--Sales Table Start-->
<div class="card">
    <div class="card-body">
        <div class="row justify-content-between">
            <div class="col">
                <h5 class="card-title fw-semibold mb-4">Sales List</h5>
            </div>
            <div class="col text-end">
                <button class="btn btn-primary" id="printButton"><i class="fa-solid fa-print"></i></button>
            </div>
        </div>
        <table class="table table-borderless" id="sales-table">
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

            </tbody>
        </table>
    </div>
</div>
<!--Sales Table End-->