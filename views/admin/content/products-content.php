
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
                        <th class="text-center">Status</th>
                        <th class="text-center">Image</th>
                        <th class="text-center">Product</th>
                        <th class="text-center">Brand</th>
                        <th class="text-center">Category</th>
                        <th class="text-center">Base Price</th>
                        <th class="text-center">Selling Price</th>
                        <th class="text-center">Stock</th>
                        <th class="text-center"><i class="fas fa-cog"></i></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $products->getProducts(); ?>
                </tbody>
            </table>
        </div>
    </div>
    <!--Product Table End-->

