<!-- New Modal -->
<div class="modal fade" id="newProduct" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">New Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="new-product" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="file" class="form-control" id="imageInput" name="image" accept="image/*" placeholder="">
                                    <label class="form-label py-2" for="imageInput">Product Image <span class="text-danger">*</span></label>
                                </div>
                            </div>
                            <div class="form-floating col-md-6">
                                <input type="text" class="form-control" name="name" placeholder="" required>
                                <label for="" class="form-label mx-2">Product Name <span class="text-danger">*</span></label>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="form-floating col-md-6">
                                <input type="text" class="form-control" name="code" placeholder="" required>
                                <label for="" class="form-label mx-2">Item Code <span class="text-danger">*</span></label>
                            </div>
                            <div class="form-floating col-md-6">
                                <input type="text" class="form-control" name="supplier_code" placeholder="" required>
                                <label for="" class="form-label mx-2">Supplier Code <span class="text-danger">*</span></label>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="form-floating col-md-6">
                                <input type="text" class="form-control" name="description" placeholder="">
                                <label for="" class="form-label mx-2">Description</label>
                            </div>
                            <div class="form-floating col-md-6">
                                <input type="date" class="form-control" id="expiration_date" name="expiration_date">
                                <label for="expiration_date" class="form-label mx-2">Expiration Date</label>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="form-floating col-md-6">
                                <select class="form-select" id="brand" name="brand">
                                    <option></option>
                                    <?php $products->getBrands(); ?>
                                </select>
                                <label for="" class="form-label">Brand <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="unit_value" placeholder="Enter unit value" required>
                                    </div>
                                    <div class="col-md-6">
                                        <select class="form-select" id="unit" name="unit" placeholder="">
                                            <option></option>
                                            <?php $products->getUnits(); ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="form-floating col-md-6">
                                <select class="form-select" id="category" name="category">
                                    <option></option>
                                    <?php $products->getCategory(); ?>
                                </select>
                                <label for="" class="form-label">Category <span class="text-danger">*</span></label>
                            </div>
                            <div class="form-floating col-md-6">
                                <select class="form-select" id="variant" name="variant">
                                    <option></option>
                                    <?php $products->getVariants(); ?>
                                </select>
                                <label for="" class="form-label">Variant <span class="text-danger">*</span></label>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="form-floating col-md-6">
                                        <input class="form-control" type="number" name="base_price" id="base_price" min="1" step="any" placeholder="" required>
                                        <label for="" class="form-label mx-2">Base Price <span class="text-danger">*</span></label>
                                    </div>
                                    <div class="form-floating col-md-6">
                                        <input class="form-control" type="number" name="selling_price" id="selling_price" min="1" step="any" placeholder="" required>
                                        <label for="" class="form-label mx-2">Selling Price <span class="text-danger">*</span></label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="form-floating col-md-4">
                                        <input class="form-control" type="number" name="initial_stock" id="initial_stock" min="0" placeholder="" required>
                                        <label for="" class="form-label mx-2">Initial Stock <span class="text-danger">*</span></label>
                                    </div>
                                    <div class="form-floating col-md-4">
                                        <input class="form-control" type="number" name="critical_level" id="critical_level" min="0" placeholder="" required>
                                        <label for="" class="form-label mx-2">Critical Level <span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-md-4 d-flex justify-content-center">
                                        <div class="form-check my-auto">
                                            <input class="form-check-input" type="checkbox" value="" id="non-stockable">
                                            <label class="form-check-label text-primary" for="non-stockable">
                                                Non-Stockable
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="form-floating col-md-6">
                                <input type="text" class="form-control" name="barcode" id="barcode" placeholder="" required>
                                <label for="" class="form-label mx-2">Barcode <span class="text-danger">*</span></label>
                                <div class="form-check mt-2">
                                    <input class="form-check-input" type="checkbox" value="" id="generate_barcode">
                                    <label class="form-check-label text-primary" for="flexCheckDefault">
                                        Generate Barcode
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="stockable" id="stockable">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>



<!-- Edit Modal -->
<div class="modal fade" id="editProduct" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="edit-label"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="edit-product" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="" class="form-label">New Product Name<span class="text-danger">*</span></label>
                        <p class="text-danger" id="edit_feedback"></p>
                        <input type="text" class="form-control" id="product_name" name="product_name" >
                        <input type="hidden" name="product_id" id="product_id">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Loading Modal -->
<div class="modal" id="loadingModal" tabindex="-1" aria-labelledby="loadingModalLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center">
                <div class="spinner-border" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2">Loading...</p>
            </div>
        </div>
    </div>
</div>
