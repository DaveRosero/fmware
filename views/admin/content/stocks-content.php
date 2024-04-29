<div class="container-fluid">
    <!--Stocks Table Start-->
    <div class="card">
        <div class="card-body">
            <div class="row justify-content-between">
                <div class="col">
                    <h5 class="card-title fw-semibold mb-4">Inventory Stocks</h5>
                </div>
                <div class="col text-end">
                    <div class="d-inline-block me-2">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addStock">
                            Add to Stock
                        </button>
                    </div>
                    <div id="printButtonContainer" class="d-inline-block">
                        <!-- DataTables print button will be placed here -->
                    </div>
                </div>
            </div>
            <table id="stocks-table">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Product</th>
                        <th>Variant</th>
                        <th>Unit</th>
                        <th>Brand</th>
                        <th>Category</th>
                        <th>Quantity in Stock</th>
                        <th>Critical Level</th>
                        <th>Last Restock</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $stocks->getStocks(); ?>
                </tbody>
            </table>
        </div>
    </div>
    <!--Stocks Table End-->
</div>

