<form id="order-form">
    <!-- OrderModal -->
    <div class="modal fade" id="order-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title text-success" id="orderLabel"></h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-2">
                    <label class="text-center"><strong>Paid Status</strong></label>
                    <div class="col d-flex justify-content-center">
                        <select class="form-control text-center" name="paid" id="paid" required></select>
                    </div>
                </div>
                <div class="row mb-2">
                <label class="text-center"><strong>Change Order Status</strong></label>
                    <div class="col d-flex justify-content-center">
                        <select class="form-control text-center" name="status" id="status" required></select>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col d-flex justify-content-center">
                        <img class="img-fluid" src="#" alt="proof of payment">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <input type="hidden" name="order_ref" id="order_ref">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </div>
    </div>
</form>