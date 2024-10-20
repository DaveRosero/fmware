<!-- Order Items Modal (Receipt Style) -->
<div class="modal fade" id="order-items-modal" tabindex="-1" aria-labelledby="order-items-modal-label" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="order-items-modal-label">Receipt</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <!-- Order Info Section -->
        <div class="mb-3">
          <h6><strong>Order Information</strong></h6>
          <p><strong>Date:</strong> <span id="order-date"></span></p>
          <p><strong>Customer Name:</strong> <span id="order-user-name"></span></p>
          <p><strong>Phone#:</strong> <span id="order-user-phone"></span></p>
          <p><strong>Address:</strong> <span id="order-address"></span></p>
          <p><strong>Description:</strong> <span id="order-address-desc"></span></p>
        </div>

        <!-- Items Section -->
        <div class="mb-3">
          <h6><strong>Order Items</strong></h6>
          <div id="order-items-container">
            <!-- Individual Items will be populated here -->
          </div>
        </div>

        <!-- Price Section -->
        <div class="d-flex justify-content-between border-top pt-2">
          <p><strong>Subtotal:</strong></p>
          <p id="order-gross"></p>
        </div>
        <div class="d-flex justify-content-between">
          <p><strong>Discount:</strong></p>
          <p id="order-discount"></p>
        </div>
        <div class="d-flex justify-content-between">
          <p><strong>Delivery Fee:</strong></p>
          <p id="order-delivery-fee"></p>
        </div>
        <div class="d-flex justify-content-between border-top pt-2">
          <p><strong>Grand Total:</strong></p>
          <p id="order-grand-total"></p>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="accept-order-btn">Accept Order</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>