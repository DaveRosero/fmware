<!-- POS Payment Modal -->
<div class="modal fade" id="pos-payment-modal" tabindex="-1" aria-labelledby="pos-payment-modal-label" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="pos-payment-modal-label">POS Payment</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="card">
          <div class="card-body">
            <!-- Placeholder for POS Ref -->
            <h1 id="pos-ref">POS Ref: N/A</h1>

            <!-- POS details -->
            <div class="d-flex flex-column text-end">
              <h3 id="pos-price">Order Price:</h3>
              <h3 id="pos-delivery-fee">Delivery Fee:</h3>
              <h3 id="pos-discount">Discount:</h3>
              <h3 id="pos-total">Total Price:</h3>
            </div>

            <!-- Payment Method -->
            <div class="d-flex flex-column mt-3">
              <h1>Payment Method</h1>
              <div class="d-flex">
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="paymentMethod" id="Cash" value="3" checked>
                  <label class="form-check-label" for="Cash">Cash on Delivery</label>
                </div>
                <div class="form-check ms-3">
                  <input class="form-check-input" type="radio" name="paymentMethod" id="Gcash" value="2">
                  <label class="form-check-label" for="Gcash">Gcash</label>
                </div>
              </div>
            </div>

            <!-- Input for Cash Received Info -->
            <div class="mt-3">
              <input class="form-control" type="number" placeholder="Cash Received" id="cash-received" aria-label="Cash Received">
              <div class="d-flex flex-column text-end mt-2">
                <h3 id="change">Change: ₱0.00</h3>
              </div>
            </div>

          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="delivered-btn" disabled>Delivered</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>