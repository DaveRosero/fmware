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
                    <button class="btn btn-secondary print"><i class="fa-solid fa-print"></i></button>
                </div>
            </div>
            <table class="table table-borderless" id="suppliers-table">
                <thead>
                    <th></th>
                    <th>Supplier</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Address</th>
                </thead>
                <tbody>
                    <?php $supplier->showSupplier(); ?>
                </tbody>
            </table>
        </div>
    </div>
    <!--Stocks Table End-->
</div>