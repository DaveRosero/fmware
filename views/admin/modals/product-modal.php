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
                                    <label class="form-label py-2" for="imageInput">Product Image</label>
                                </div>
                            </div>
                            <div class="form-floating col-md-6">
                                <input type="text" class="form-control" name="name" placeholder="" required>
                                <label for="" class="form-label mx-2">Product Name <span class="text-danger">*</span></label>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="form-floating col-md-6">
                                <input type="text" class="form-control" name="code" placeholder="">
                                <label for="" class="form-label mx-2">Item Code</label>
                            </div>
                            <div class="col-md-6">
                                <select class="form-select" name="supplier" id="supplier" required>
                                    <option></option>
                                    <?php $products->supplierOptions(); ?>
                                </select>
                                <a href="/manage-suppliers" class="text-decoration-underline mx-2">New Supplier</a>
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
                                <select class="form-select" id="brand" name="brand" required>
                                    <option></option>
                                    <?php $products->getBrands(); ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="unit_value" placeholder="Enter unit value" required>
                                    </div>
                                    <div class="col-md-6">
                                        <select class="form-select" id="unit" name="unit" placeholder="" required>
                                            <option></option>
                                            <?php $products->getUnits(); ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="form-floating col-md-6">
                                <select class="form-select" id="category" name="category" required>
                                    <option></option>
                                    <?php $products->getCategory(); ?>
                                </select>
                            </div>
                            <div class="form-floating col-md-6">
                                <select class="form-select" id="variant" name="variant" required>
                                    <option></option>
                                    <?php $products->getVariants(); ?>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="row">
                                    <!-- <div class="form-floating col-md-6">
                                        <input class="form-control" type="number" name="base_price" id="base_price" min="1" step="any" placeholder="" required>
                                        <label for="" class="form-label mx-2">Base Price <span class="text-danger">*</span></label>
                                    </div> -->
                                    <div class="form-floating col-md">
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
                                            <label class="form-check-label fw-semibold" for="non-stockable">
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
                                    <label class="form-check-label fw-semibold" for="flexCheckDefault">
                                        Generate Barcode
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="pickup_checkbox">
                                                    <input type="hidden" name="pickup" id="pickup" value="0">
                                                    <label class="form-check-label fw-semibold" for="checkbox1">
                                                        For Pickup
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="delivery_checkbox">
                                                    <input type="hidden" name="delivery" id="delivery" value="0">
                                                    <label class="form-check-label fw-semibold" for="checkbox2">
                                                        For Delivery
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="stockable" id="stockable" value="1">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<form id="edit-product" method="POST" enctype="multipart/form-data">
    <!-- Edit Modal -->
    <div class="modal fade" id="editProduct" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="edit-label">Edit Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="file" class="form-control" id="imageInput" name="edit_image" accept="image/*" placeholder="">
                                    <label class="form-label py-2" for="imageInput">New Product Image</label>
                                </div>
                            </div>
                            <div class="form-floating col-md-6">
                                <input type="text" class="form-control" name="edit_name" id="edit_name" placeholder="" required>
                                <label for="" class="form-label mx-2">Product Name <span class="text-danger">*</span></label>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="form-floating col-md-6">
                                <input type="text" class="form-control" name="edit_code" id="edit_code" placeholder="">
                                <label for="" class="form-label mx-2">Item Code</label>
                            </div>
                            <div class="col-md-6">
                                <select class="form-select" name="edit_supplier" id="edit_supplier" required>
                                    <option></option>
                                    <?php $products->supplierOptions(); ?>
                                </select>
                                <a href="/manage-suppliers" class="text-decoration-underline mx-2">New Supplier</a>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="form-floating col-md-6">
                                <input type="text" class="form-control" name="edit_description" id="edit_description" placeholder="">
                                <label for="" class="form-label mx-2">Description</label>
                            </div>
                            <div class="form-floating col-md-6">
                                <input type="date" class="form-control" id="edit_expiration_date" name="edit_expiration_date">
                                <label for="expiration_date" class="form-label mx-2">Expiration Date</label>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="form-floating col-md-6">
                                <select class="form-select" id="edit_brand" name="edit_brand" required>
                                    <option></option>
                                    <?php $products->getBrands(); ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="edit_unit_value" id="edit_unit_value" placeholder="Enter unit value" required>
                                    </div>
                                    <div class="col-md-6">
                                        <select class="form-select" id="edit_unit" name="edit_unit" placeholder="" required>
                                            <option></option>
                                            <?php $products->getUnits(); ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="form-floating col-md-6">
                                <select class="form-select" id="edit_category" name="edit_category" required>
                                    <option></option>
                                    <?php $products->getCategory(); ?>
                                </select>
                            </div>
                            <div class="form-floating col-md-6">
                                <select class="form-select" id="edit_variant" name="edit_variant" required>
                                    <option></option>
                                    <?php $products->getVariants(); ?>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="row">
                                    <!-- <div class="form-floating col-md-6">
                                        <input class="form-control" type="number" name="edit_base_price" id="edit_base_price" min="1" step="any" placeholder="" required>
                                        <label for="" class="form-label mx-2">Base Price <span class="text-danger">*</span></label>
                                    </div> -->
                                    <div class="form-floating col-md">
                                        <input class="form-control" type="number" name="edit_selling_price" id="edit_selling_price" min="1" step="any" placeholder="" required>
                                        <label for="" class="form-label mx-2">Selling Price <span class="text-danger">*</span></label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="form-floating col-md-4">
                                        <input class="form-control" type="number" name="edit_stock" id="edit_stock" min="0" placeholder="" required>
                                        <label for="" class="form-label mx-2">Stock <span class="text-danger">*</span></label>
                                    </div>
                                    <div class="form-floating col-md-4">
                                        <input class="form-control" type="number" name="edit_critical_level" id="edit_critical_level" min="0" placeholder="" required>
                                        <label for="" class="form-label mx-2">Critical Level <span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-md-4 d-flex justify-content-center">
                                        <div class="form-check my-auto">
                                            <input class="form-check-input" type="checkbox" value="" id="edit_non_stockable_checkbox">
                                            <input type="hidden" name="edit_stockable" id="edit_stockable">
                                            <label class="form-check-label fw-semibold" for="non-stockable">
                                                Non-Stockable
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="form-floating col-md-6">
                                <input type="text" class="form-control" name="edit_barcode" id="edit_barcode" placeholder="" required>
                                <label class="form-label mx-2" for="flexCheckDefault">
                                    Barcode <span class="text-danger">*</span>
                                </label>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="edit_pickup_checkbox">
                                                    <input type="hidden" name="edit_pickup" id="edit_pickup">
                                                    <label class="form-check-label fw-semibold" for="checkbox1">
                                                        For Pickup
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="edit_delivery_checkbox">
                                                    <input type="hidden" name="edit_delivery" id="edit_delivery">
                                                    <label class="form-check-label fw-semibold" for="checkbox2">
                                                        For Delivery
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="edit_id" id="edit_id">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </div>
    </div>
</form>

<div class="modal fade" id="viewProduct" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="view-label">View Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <img src="#" alt="Product Image" srcset="" id="view_image">
                        </div>
                        <div class="col-md-6">
                            <dl class="row">
                                <dt class="col-sm-4 fw-semibold text-primary text-end">NAME:</dt>
                                <dd class="col-sm-8 fw-semibold" id="view_name"></dd>

                                <dt class="col-sm-4 fw-semibold text-primary text-end">CODE:</dt>
                                <dd class="col-sm-8 fw-semibold" id="view_code"></dd>

                                <dt class="col-sm-4 fw-semibold text-primary text-end">SUPPLIER:</dt>
                                <dd class="col-sm-8 fw-semibold" id="view_supplier"></dd>

                                <dt class="col-sm-4 fw-semibold text-primary text-end">DESCRIPTION:</dt>
                                <dd class="col-sm-8 fw-semibold" id="view_description"></dd>

                                <dt class="col-sm-4 fw-semibold text-primary text-end">EXPIRATION DATE:</dt>
                                <dd class="col-sm-8 fw-semibold" id="view_expiration_date"></dd>

                                <dt class="col-sm-4 fw-semibold text-primary text-end">CATEGORY:</dt>
                                <dd class="col-sm-8 fw-semibold" id="view_category"></dd>

                                <dt class="col-sm-4 fw-semibold text-primary text-end">BRAND:</dt>
                                <dd class="col-sm-8 fw-semibold" id="view_brand"></dd>

                                <dt class="col-sm-4 fw-semibold text-primary text-end">UNIT:</dt>
                                <dd class="col-sm-8 fw-semibold" id="view_unit"></dd>

                                <dt class="col-sm-4 fw-semibold text-primary text-end">VARIANT:</dt>
                                <dd class="col-sm-8 fw-semibold" id="view_variant"></dd>

                                <!-- <dt class="col-sm-4 fw-semibold text-primary text-end">BASE PRICE:</dt>
                                <dd class="col-sm-8 fw-semibold" id="view_base_price"></dd> -->

                                <dt class="col-sm-4 fw-semibold text-primary text-end">SELLING PRICE:</dt>
                                <dd class="col-sm-8 fw-semibold" id="view_selling_price"></dd>

                                <dt class="col-sm-4 fw-semibold text-primary text-end">STOCKS:</dt>
                                <dd class="col-sm-8 fw-semibold" id="view_stock"></dd>

                                <dt class="col-sm-4 fw-semibold text-primary text-end">CRITICAL LEVEL:</dt>
                                <dd class="col-sm-8 fw-semibold" id="view_critical_level"></dd>

                                <dt class="col-sm-4 fw-semibold text-primary text-end">BARCODE:</dt>
                                <dd class="col-sm-8 fw-semibold" id="view_barcode"></dd>

                                <dt class="col-sm-4 fw-semibold text-primary text-end">FOR PICKUP:</dt>
                                <dd class="col-sm-8 fw-semibold" id="view_pickup"></dd>

                                <dt class="col-sm-4 fw-semibold text-primary text-end">FOR DELIVERY:</dt>
                                <dd class="col-sm-8 fw-semibold" id="view_delivery"></dd>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button class="btn btn-primary edit"
                        id = "view-edit-button" 
                        data-bs-toggle="modal" 
                        data-bs-target="#editProduct"
                    >Edit</button>
                </div>
            </div>
        </div>
</div>

<div class="modal fade" id="prevBP" tabindex="-1" aria-labelledby="loadingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Previous Base Price/s</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-borderless w-100" id="bp_table">
                        <thead>
                            <th class="text-center">P.O. #</th>
                            <th class="text-center">Price</th>
                            <th class="text-center">Date</th>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-center">FMPO_E53929A97676D0094A3C</td>
                                <td class="text-center">P100,000</td>
                                <td class="text-center">August 2, 2024</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
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
