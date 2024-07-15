<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col text-end">
                    <button class="btn btn-primary" id="printButton"><i class="fa-solid fa-print"></i></button>
                </div>
            </div>
            <div id="printContent">
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
                    <div class="col-md-6 text-md-end">
                        <div class="text-end">
                            <h6 class="mb-0"><?php echo $po_info['date']; ?></h6>
                            <p class="fst-italic"><?php echo $po_info['po_ref']; ?></p>
                        </div>
                    </div>
                </div>

                <div class="row mt-2 mb-2">
                    <hr>
                </div>

                <div class="row">
                    <table class="table table-bordered">
                        <thead class="table-secondary">
                            <th>#</th>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Unit</th>
                            <th>Price</th>
                            <th>Total</th>
                        </thead>
                        <tbody id="po_item_content">
                            <!-- PO Content Here -->
                             <?php $po->viewPO($po_info['po_ref']); ?>
                        </tbody>
                    </table>
                </div>

                <div class="row mt-4">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold" for="">REMARKS:</label>
                        <p class="fs-3 px-4"><?php echo $po_info['remarks']; ?></p>
                    </div>
                    <div class="col-md-6 text-end mt-4">
                        <h5><span id="grand_total">TOTAL: <?php $po->viewPOTotal($po_info['po_ref']); ?></span></h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

