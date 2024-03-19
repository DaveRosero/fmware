<div class="container-fluid">
    <!--Restock Table Start-->
    <div class="card">
        <div class="card-body">
            <div class="row justify-content-between">
                <div class="col">
                    <h5 class="card-title fw-semibold mb-4">Price List</h5>
                </div>
                <div class="col text-end">
                    <div class="d-inline-block me-2">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newPrice">
                            Add to Price List
                        </button>
                    </div>
                    <div id="printButtonContainer" class="d-inline-block">
                        <!-- DataTables print button will be placed here -->
                    </div>
                </div>
            </div>
            <table id="price-list">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Product</th>
                        <th>Base Price</th>
                        <th>Unit Price</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $price->getPrices(); ?>
                </tbody>
            </table>
        </div>
    </div>
    <!--Restock Table End-->
</div>

