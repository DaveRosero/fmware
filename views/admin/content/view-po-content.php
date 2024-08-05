<!--begin::App Main-->
<main class="app-main"> <!--begin::App Content Header-->
    <div class="app-content-header"> <!--begin::Container-->
        <div class="container-fluid"> <!--begin::Row-->
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">View Purchase Order</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item">Inventory</li>
                        <li class="breadcrumb-item">Purchase Orders</li>
                        <li class="breadcrumb-item active" aria-current="page">
                            View
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
                                <div class="col text-end">
                                    <button class="btn btn-info text-white" id="imageButton" data-po-ref="<?php echo $po_info['po_ref']; ?>"><i class="bi bi-image"></i></button>
                                    <button class="btn btn-primary" id="printButton"><i class="bi bi-printer-fill"></i></button>
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
                                    <table class="table table-borderless">
                                        <thead>
                                        <?php if ($po_info['status'] !==2 ): ?>
                                            <th class="text-center">#</th>
                                            <th class="text-center">Product</th>
                                            <th class="text-center">Quantity</th>
                                            <th class="text-center">Unit</th>
                                            <th class="text-center">Price</th>
                                            <th class="text-center">Total</th>
                                        <?php else: ?>
                                            <th class="text-center">#</th>
                                            <th class="text-center">Product</th>
                                            <th class="text-center">Quantity</th>
                                            <th class="text-center">Unit</th>
                                            <th class="text-center">Price</th>
                                            <th class="text-center">Total</th>
                                            <th class="text-center">Actual Price</th>
                                            <th class="text-center">Received</th>
                                            <th class="text-center">Amount</th>
                                        <?php endif; ?>
                                        </thead>
                                        <tbody id="po_item_content">
                                            <!-- PO Content Here -->
                                            <?php
                                                if ($po_info['status'] !== 2) {
                                                    $po->viewPO($po_info['po_ref']); 
                                                } else {
                                                    $po->viewCompletePO($po_info['po_ref']); 
                                                }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="row mt-4">
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold" for="">REMARKS:</label>
                                        <p class="fs-3 px-4"><?php echo $po_info['remarks']; ?></p>
                                    </div>
                                    <?php if ($po_info['status'] !== 2): ?>
                                        <div class="col-md-6 text-end mt-4">
                                            <h5><span id="grand_total">TOTAL: <?php $po->viewPOTotal($po_info['po_ref']); ?></span></h5>
                                        </div>
                                    <?php else: ?>
                                        <div class="col-md-6 text-end">
                                            <h5>SHIPPING: ₱<?php echo number_format($po_info['shipping'], 2); ?></h5>
                                            <h5>OTHERS: ₱<?php echo number_format($po_info['others'], 2); ?></h5>
                                            <h5>GRAND TOTAL: ₱<?php echo number_format($po_info['shipping'] + $po_info['others'] + str_replace(',', '', $po_info['received_total']), 2); ?></h5>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent text-end mb-3" style="border-top: none; box-shadow: none; margin-top: 0;">
                            <button class="btn btn-danger" id="delete" data-po-ref="<?php echo $po_info['po_ref']; ?>"><i class="bi bi-trash3-fill"></i></button>
                            <a href="/purchase-orders" class="btn btn-secondary"><i class="bi bi-arrow-left"></i></a>
                        </div>
                    </div>
                </div>
            </div> <!--end::Row-->
        </div>
    </div> <!--end::App Content-->
</main> <!--end::App Main--> 