<!-- New Modal -->
<div class="modal fade" id="newPrice" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add to Price List</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="new-price" method="POST">
            <div class="modal-body">
                <div class="row">
                    <div class="col">
                        <label for="" class="form-label">Product<span class="text-danger">*</span></label>
                        <select name="product_id" id="products" data-placeholder="Select a Product">
                            <option></option>
                            <?php $price->getProducts(); ?>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <label for="" class="form-label">Base Price<span class="text-danger">*</span></label>
                        <input type="number" min="0" class="form-control" name="base_price" required>
                    </div>
                    <div class="col">
                        <label for="" class="form-label">Unit Price<span class="text-danger">*</span></label>
                        <input type="number" min="0" class="form-control" name="unit_price" required>
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
<div class="modal fade" id="editBrand" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="edit-label"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="edit-brand" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="" class="form-label">New Brand Name<span class="text-danger">*</span></label>
                        <p class="text-danger" id="edit_feedback"></p>
                        <input type="text" class="form-control" id="brand_name" name="brand_name" required>
                        <input type="hidden" name="brand_id" id="brand_id">
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
