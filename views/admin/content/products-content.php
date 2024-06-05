<div class="container-fluid">
    <!--Product Table Start-->
    <div class="card">
        <div class="card-body">
            <div class="row justify-content-between">
                <div class="col">
                    <h5 class="card-title fw-semibold mb-4">Product List</h5>
                </div>
                <div class="col text-end">
                    <div class="d-inline-block me-2">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newProduct">
                            New Product
                        </button>
                    </div>
                </div>
            </div>
            <table class="table table-borderless" id="product-table">
                <thead>
                    <tr>
                        <th>Status</th>
                        <th>Image</th>
                        <th>Product</th>
                        <th>Variant</th>
                        <th>Brand</th>
                        <th>Category</th>
                        <th>Unit</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $products->getProducts(); ?>
                </tbody>
            </table>
        </div>
    </div>
    <!--Product Table End-->
</div>

