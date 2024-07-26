<div class="container-fluid">
    <!--Stocks Table Start-->
    <div class="card">
        <div class="card-body">
            <div class="row justify-content-between">
                <div class="col">
                    <h5 class="card-title fw-semibold mb-4">Suppliers</h5>
                </div>
                <div class="col text-end">
                    <div class="d-inline-block me-2">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSupplier">
                            Add Supplier
                        </button>
                    </div>
                    <!-- <button class="btn btn-secondary print"><i class="fa-solid fa-print"></i></button> -->
                </div>
            </div>
            <table class="table table-borderless" id="suppliers-table">
                <thead>
                    <th class="text-center">Status</th>
                    <th class="text-center">Supplier</th>
                    <th class="text-center">Email</th>
                    <th class="text-center">Contact Person</th>
                    <th class="text-center">Phone</th>
                    <th class="text-center">Address</th>
                    <th class="text-center">Date</th>
                    <th class="text-center"><i class="fas fa-cog"></i></th>
                </thead>
                <tbody>
                    <?php $supplier->showSupplier(); ?>
                </tbody>
            </table>
        </div>
    </div>
    <!--Stocks Table End-->
</div>