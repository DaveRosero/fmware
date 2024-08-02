<!--begin::App Main-->
<main class="app-main"> <!--begin::App Content Header-->
    <div class="app-content-header"> <!--begin::Container-->
        <div class="container-fluid"> <!--begin::Row-->
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Receive Purchase Order</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item">Inventory</li>
                        <li class="breadcrumb-item">Purchase Orders</li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Receive
                        </li>
                    </ol>
                </div>
            </div> <!--end::Row-->
        </div> <!--end::Container-->
    </div> <!--end::App Content Header--> <!--begin::App Content-->
    <div class="app-content"> <!--begin::Container-->
        <div class="container-fluid"> <!--begin::Row-->
            <div class="row">
                <div class="col-12 col-sm-12 col-md-12">
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
                                        <input type="hidden" name="po_ref" id="po_ref" value="<?php echo $po_info['po_ref']; ?>">
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-2 mb-2">
                                <hr>
                            </div>

                            <div class="row">
                                <div class="table-responsive">
                                    <table class="table table-borderless w-100">
                                        <thead>
                                            <th class="text-center">#</th>
                                            <th class="text-center">Product</th>
                                            <th class="text-center">Quantity</th>
                                            <th class="text-center">Unit</th>
                                            <th class="text-center">Price</th>
                                            <th class="text-center">Total</th>
                                            <th class="text-center">Actual Price</th>
                                            <th class="text-center">Received</th>
                                            <th class="text-center">Amount</th>
                                        </thead>
                                        <tbody id="po_item_content">
                                            <!-- PO Content Here -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold" for="">REMARKS:</label>
                                    <p class="ms-2 px-4 fs-4"><?php echo $po_info['remarks']; ?></p>
                                </div>
                                <div class="col-md-6 text-end">
                                    <div class="d-flex justify-content-end align-items-center mb-2">
                                        <h5 class="mb-0 me-2">SHIPPING:</h5>
                                        <div class="input-group w-25">
                                            <span class="input-group-text">₱</span>
                                            <input class="form-control" type="number" name="shipping" id="shipping" step="any" min="0" value="<?php echo $po_info['shipping']; ?>">
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-end align-items-center mb-2">
                                        <h5 class="mb-0 me-2">OTHERS:</h5>
                                        <div class="input-group w-25">
                                            <span class="input-group-text">₱</span>
                                            <input class="form-control" type="number" name="others" id="others" step="any" min="0" value="<?php echo $po_info['others']; ?>">
                                        </div>
                                    </div>
                                    <h5><span id="grand_total">GRAND TOTAL: ₱0.00</span></h5>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-white text-end mb-3" style="border-top: none; box-shadow: none; margin-top: 0;">
                            <button class="btn btn-primary" id="save" data-po-ref="<?php echo $po_info['po_ref']; ?>"><i class="bi bi-floppy-fill"></i></button>
                            <a href="/purchase-orders" class="btn btn-secondary"><i class="bi bi-arrow-left"></i></a>
                        </div>
                    </div>
                </div>
            </div> <!--end::Row-->
        </div>
    </div> <!--end::App Content-->
</main> <!--end::App Main--> 