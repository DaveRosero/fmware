<!-- New Modal -->
<div class="modal fade" id="addStock" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add Stock</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="add-stock" method="POST">
            <div class="modal-body">
                <div class="row mb-2">
                    <div class="col">
                        <label for="" class="form-label">Product<span class="text-danger">*</span></label><br>
                        <select class="form-select" id="products" name="product_id" data-placeholder="Select a Product">
                            <option></option>
                            <?php $stocks->getProducts(); ?>
                        </select>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col">
                        <label for="" class="form-label">Initial Stock</label>
                        <input type="number" class="form-control" name="initial_stock" min="1">
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col">
                        <label for="" class="form-label">Critical Level</label>
                        <input type="number" class="form-control" name="critical_level" min="1">
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


<!-- Restock Modal -->
<div class="modal fade" id="restock" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="edit-label"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="restock-product" method="POST">
                <div class="modal-body">
                    <div class="row mb-2">
                        <div class="col">
                            <label for="" class="form-label">Product</label>
                            <input type="text" class="form-control" id="product-name" name="product_id" readonly>
                            <input type="hidden" name="product_id" id="product-id">
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col">
                            <label for="" class="form-label">Supplier Order No.<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="supplier_order_no" name="supplier_order_no" required>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col">
                            <label for="" class="form-label">Date of Delivery<span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="date" name="date" required>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col">
                            <label for="" class="form-label">Quantity<span class="text-danger">*</span></label>
                            <input type="number" min="1" class="form-control" id="qty" name="qty" required>
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
