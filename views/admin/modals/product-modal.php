<!-- New Modal -->
<div class="modal fade" id="newProduct" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">New Product</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="new-product" method="POST" enctype="multipart/form-data">
            <div class="modal-body">
                <p class="text-danger" id="product_feedback"></p>
                <div class="container">
                    <div class="row mb-2">
                        <div class="col">
                            <label for="imageInput" class="form-label">Choose an image <span class="text-danger">*</span></label>
                            <input type="file" class="form-control" id="imageInput" name="image" accept="image/*">
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col">
                            <label for="" class="form-label">Product Name<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col">
                            <label for="" class="form-label">Item Code<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="code" required>
                        </div>
                        <div class="col">
                            <label for="" class="form-label">Supplier Code<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="supplier_code" required>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col">
                            <label for="" class="form-label">Description</label>
                            <input type="text" class="form-control" name="description">
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col">
                            <label for="" class="form-label">Category <span class="text-danger">*</span></label>
                            <select class="form-select" id="category" name="category" data-placeholder="Select Category">
                                <option></option>
                                <?php $products->getCategory(); ?>
                            </select>
                        </div>
                        <div class="col">
                            <label for="" class="form-label">Brand <span class="text-danger">*</span></label>
                            <select class="form-select" id="brand" name="brand" data-placeholder="Select Brand">
                                <option></option>
                                <?php $products->getBrands(); ?>
                            </select>
                        </div>
                        <div class="col">
                            <label for="" class="form-label">Unit <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="unit_value">
                            <select class="form-select" id="unit" name="unit" data-placeholder="Select Unit">
                                <option></option>
                                <?php $products->getUnits(); ?>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col">
                            <label for="expiration_date" class="form-label">Expiration Date</label>
                            <input type="date" class="form-control" id="expiration_date" name="expiration_date">
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col">
                            <label for="" class="form-label">Barcode</label>
                            <input type="text" class="form-control" name="barcode">
                        </div>
                    </div>
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
                        <input type="text" class="form-control" id="product_name" name="product_name" required>
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
