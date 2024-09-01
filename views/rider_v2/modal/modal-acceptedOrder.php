<!-- Accepted Order Items Modal (Receipt Style) -->
<div class="modal fade" id="acceptedOrder-items-modal" tabindex="-1" aria-labelledby="acceptedOrder-items-modal-label" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="acceptedOrder-items-modal-label">Order</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <!-- Order Info Section -->
        <div class="mb-3">
          <h6><strong>Order Information</strong></h6>
          <p><strong>Date:</strong> <span id="acceptedOrder-date"></span></p>
          <p><strong>Customer Name:</strong> <span id="acceptedOrder-user-name"></span></p>
          <p><strong>Phone#:</strong> <span id="acceptedOrder-user-phone"></span></p>
          <p><strong>Address:</strong> <span id="acceptedOrder-address"></span></p>
          <p><strong>Description:</strong> <span id="acceptedOrder-address-desc"></span></p>
        </div>

        <!-- Items Section -->
        <div class="mb-3">
          <h6><strong>Order Items</strong></h6>
          <div id="acceptedOrder-items-container">
            <!-- Individual Items will be populated here -->
          </div>
        </div>

        <!-- Price Section -->
        <div class="d-flex justify-content-between border-top pt-2">
          <p><strong>Total Price:</strong></p>
          <p id="acceptedOrder-gross"></p>
        </div>
        <div class="d-flex justify-content-between">
          <p><strong>Discount:</strong></p>
          <p id="acceptedOrder-discount"></p>
        </div>
        <div class="d-flex justify-content-between">
          <p><strong>Delivery Fee:</strong></p>
          <p id="acceptedOrder-delivery-fee"></p>
        </div>
        <div class="d-flex justify-content-between border-top pt-2">
          <p><strong>Grand Total:</strong></p>
          <p id="acceptedOrder-grand-total"></p>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#qr-scanner-modal" id="webcamButton">Scan QR Code</button>
        <button type="button" class="btn btn-danger" id="cancelOrderButton">Cancel Order</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
