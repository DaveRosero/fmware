<div class="container-fluid">
    <!--Brand Table Start-->
    <div class="card">
        <div class="card-body">
            <div class="row justify-content-between">
                <div class="col">
                    <h5 class="card-title fw-semibold mb-4">Brand List</h5>
                </div>
                <div class="col text-end">
                    <div class="d-inline-block me-2">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newBrand">
                            New Brand
                        </button>
                    </div>
                    <div id="printButtonContainer" class="d-inline-block">
                        <!-- DataTables print button will be placed here -->
                    </div>
                </div>
            </div>
            <table class="table table-borderless" id="brand-table">
                <thead>
                    <tr>
                        <th class="text-center">Status</th>
                        <th class="text-center">Brand</th>
                        <th class="text-center">Number of Products</th>
                        <th class="text-center">Date Added</th>
                        <th class="text-center"><i class="fas fa-cog"></i></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $brand->getBrands(); ?>
                </tbody>
            </table>
        </div>
    </div>
    <!--Brand Table End-->
</div>

