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
                        <th class="text-center">P.O. #</th>
                        <th class="text-center">Supplier</th>
                        <th class="text-center">Date Created</th>
                        <th class="text-center">Date Received</th>
                        <th class="text-center">Status</th>
                        <th class="text-center" style="width: 100px;"><i class="fas fa-cog"></i></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $po->showPO(); ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

