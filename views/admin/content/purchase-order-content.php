<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <div class="row justify-content-between">
                <div class="col">
                    <h5 class="card-title fw-semibold mb-4">Purchase Orders</h5>
                </div>
                <div class="col">
                    <div class="text-end">
                        <button class="btn btn-primary" id="create_po" data-bs-toggle="modal" data-bs-target="#createPO">Create P.O.</button>
                    </div>
                </div>
            </div>
            <table class="table table-borderless" id="purchase-order-table">
                <thead>
                    <tr>
                        <th>P.O. #</th>
                        <th>Supplier</th>
                        <th>Total Amount</th>
                        <th>Date Created</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $po->showPO(); ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

