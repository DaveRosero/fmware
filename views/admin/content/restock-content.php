<div class="container-fluid">
    <!--Restock Table Start-->
    <div class="card">
        <div class="card-body">
            <div class="row justify-content-between">
                <div class="col">
                    <h5 class="card-title fw-semibold mb-4">Restock Records</h5>
                </div>
                <div class="col text-end">
                    <div id="printButtonContainer" class="d-inline-block">
                        <!-- DataTables print button will be placed here -->
                    </div>
                </div>
            </div>
            <table id="restock-table">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Supplier Order No.</th>
                        <th>Author</th>
                        <th>Date Delivered</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $stocks->getRestock(); ?>
                </tbody>
            </table>
        </div>
    </div>
    <!--Restock Table End-->
</div>

