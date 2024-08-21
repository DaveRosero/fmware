<!-- OrderModal -->
<div class="modal fade" id="order-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-success" id="orderLabel"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="card shadow-lg mb-2">
                    <div class="card-header text-center mb-0 pb-0 bg-primary">
                        <p class="text-light fw-bold fs-5">Paid Status</p>
                    </div>
                    <div class="card-body">
                        <div class="row mb-2 align-items-center justify-content-center" id="paid-status">
                            <!-- <div class="col col-auto">
                                <button class="btn btn-lg btn-danger" disabled>
                                    <span><i class="fas fa-exclamation-circle"></i></span> 
                                    Unpaid
                                </button>
                            </div>
                            <div class="col col-auto">
                                <i class="fas fa-arrow-right fa-2x"></i>
                            </div>
                            <div class="col col-auto">
                                <button class="btn btn-lg btn-success">
                                    <i class="fas fa-check-circle"></i> 
                                    Paid
                                </button>
                            </div> -->
                        </div>
                    </div>
                </div>

                <div class="card shadow-lg mb-2">
                    <div class="card-header text-center mb-2 pb-0 bg-primary">
                        <p class="text-light fw-bold fs-5">Order Status</p>
                    </div>
                    <div class="card-body">
                        <div class="row mb-2 align-items-center justify-content-center" id="order-status">
                            <!-- <div class="col col-auto">
                                <button class="btn btn-lg btn-warning" disabled><i class="fas fa-hourglass-half"></i> Pending</button>
                            </div>
                            <div class="col col-auto">
                                <i class="fas fa-arrow-right fa-2x"></i>
                            </div>
                            <div class="col col-auto">
                                <button class="btn btn-lg btn-primary"><i class="fas fa-truck"></i> Delivering</button>
                            </div> -->
                        </div>
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <button class="btn btn-lg btn-primary w-100 mb-2" id="view-proof">Proof of Payment</button>
                    </div>
                    <div class="col-md-6">
                        <button class="btn btn-lg btn-danger w-100 mb-2" id="cancel-order">Cancel Order</button>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <input type="hidden" name="order_ref" id="order_ref">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <!-- <button type="submit" class="btn btn-primary">Submit</button> -->
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="paid-modal" tabindex="-1" aria-labelledby="paid-modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="paid-modalLabel">Confirm Action</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center fw-bold fs-6" id="paid-modal-warning">

            </div>
            <div class="modal-footer">
                <input type="hidden" name="paid" value="paid" id="paid">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="paid-modal-close">Close</button>
                <button type="submit" class="btn btn-success" id="confirm-paid">Confirm</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="delivering-modal" tabindex="-1" aria-labelledby="delivering-modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="delivering-modalLabel">Confirm Action</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center fw-bold fs-6" id="delivering-modal-warning">

            </div>
            <div class="modal-footer">
                <input type="hidden" name="status" value="delivering" id="delivering">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="delivering-modal-close">Close</button>
                <button type="submit" class="btn btn-success" id="confirm-delivering">Confirm</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="delivered-modal" tabindex="-1" aria-labelledby="delivered-modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="delivered-modalLabel">Confirm Action</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center fw-bold fs-6" id="delivered-modal-warning">

            </div>
            <div class="modal-footer">
                <input type="hidden" name="status" value="delivered" id="delivered">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="delivered-modal-close">Close</button>
                <button type="submit" class="btn btn-success" id="confirm-delivered">Confirm</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="receipt-modal" tabindex="-1" aria-label="receipt-modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="receipt-modalLabel">Order Receipt</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center fw-bold fs-6" id="receipt-content">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" id="print-receipt">Print</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="proof-modal" tabindex="-1" aria-label="proof-modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="proof-modalLabel">Proof of Payment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center fw-bold fs-6" id="proof-content">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="proof-modal-close">Close</button>
            </div>
        </div>
    </div>
</div>