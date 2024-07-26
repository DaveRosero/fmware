<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="text-center">
                    <h5 class="mb-0 fw-semibold">FM Odulio's Enterprise & Gen. Merchandise</h5>
                    <p class="mb-0">Mc Arthur Hi-way, Poblacion II, Marilao, Bulacan</p>
                    <p class="mb-0">fmoduliogenmdse@yahoo.com</p>
                    <p class="mb-0">0922-803-3898</p>
                    <h5 class="mt-4">Purchase Order</h5>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-md-6">
                    <h6 class="mb-0"><?php echo $supplier_info['name']; ?></h6>
                    <p class="mb-0"><?php echo $supplier_info['address']; ?></p>
                    <p class="mb-0"><?php echo $supplier_info['email']; ?></p>
                    <p class="mb-0"><?php echo $supplier_info['phone']; ?></p>
                </div>
                <div class="col-md-6">
                    <div class="text-end">
                        <h6 class="mb-0"><?php echo $po_info['date']; ?></h6>
                        <p class="fst-italic"><?php echo $po_info['po_ref']; ?></p>
                    </div>
                </div>
            </div>

            <div class="row mt-2 mb-2">
                <hr>
            </div>

            <form id="po_item" method="POST">
                <div class="row d-flex justify-content-end">
                    <div class="col-md-4 d-flex justify-content-end mb-2">
                        <select class="form-select" id="product" name="product" required>
                            <option></option>
                            <?php $po->getSupplierProducts($supplier_info['id']); ?>
                        </select>
                        <input type="hidden" name="po_ref" id="po_ref" value="<?php echo $po_info['po_ref']; ?>">
                        <button class="btn btn-success ms-2" type="submit">Add</button>
                        <a class="btn btn-primary ms-2" href="/manage-products">New</a>
                    </div>
                </div>
            </form>

            <div class="row">
                <table class="table table-borderless">
                    <thead>
                        <th class="text-center">#</th>
                        <th class="text-center">Product</th>
                        <th class="text-center">Quantity</th>
                        <th class="text-center">Unit</th>
                        <th class="text-center">Price</th>
                        <th class="text-center">Total</th>
                    </thead>
                    <tbody id="po_item_content">
                        <!-- PO Content Here -->
                    </tbody>
                </table>
            </div>

            <div class="row mt-4">
                <div class="col-md-6">
                    <label class="form-label fw-semibold" for="">REMARKS:</label>
                    <textarea id="remarks" name="remarks" class="form-control" data-po-ref="<?php echo $po_info['po_ref']; ?>"><?php echo $po_info['remarks']; ?></textarea>
                </div>
                <div class="col-md-6 text-end mt-4">
                    <h5><span id="grand_total">TOTAL: ₱0.00</span></h5>
                </div>
            </div>
        </div>
        <div class="card-footer bg-white text-end mb-3" style="border-top: none; box-shadow: none; margin-top: 0;">
            <button class="btn btn-primary" id="save" data-po-ref="<?php echo $po_info['po_ref']; ?>">Save</button>
            <button class="btn btn-danger" id="delete" data-po-ref="<?php echo $po_info['po_ref']; ?>">Delete</button>
            <a href="/purchase-orders" class="btn btn-secondary">Cancel</a>
        </div>
    </div>
</div>

