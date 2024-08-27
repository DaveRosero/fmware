<!-- Order Items Modal (Receipt Style) -->
<div class="modal fade" id="historyOrder-items-modal" tabindex="-1" aria-labelledby="historyOrder-items-modal-label" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="historyOrder-items-modal-label">Order Receipt</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <!-- Order Info Section -->
        <div class="mb-3">
          <h6><strong>Order Information</strong></h6>
          <p><strong>Date:</strong> <span id="historyOrder-date"></span></p>
          <p><strong>Status:</strong> <span id="historyOrder-status"></span></p>
          <p><strong>Paid:</strong> <span id="historyOrder-paid"></span></p>
          <p><strong>Customer Name:</strong> <span id="historyOrder-user-name"></span></p>
          <p><strong>Phone#:</strong> <span id="historyOrder-user-phone"></span></p>
          <p><strong>Address:</strong> <span id="historyOrder-address"></span></p>
          <p><strong>Description:</strong> <span id="historyOrder-address-desc"></span></p>
        </div>

        <!-- Items Section -->
        <div class="mb-3">
          <h6><strong>Order Items</strong></h6>
          <div id="historyOrder-items-container">
            <!-- Individual Items will be populated here -->
          </div>
        </div>

        <!-- Price Section -->
        <div class="d-flex justify-content-between border-top pt-2">
          <p><strong>Subtotal:</strong></p>
          <p id="historyOrder-subtotal"></p>
        </div>
        <div class="d-flex justify-content-between">
          <p><strong>VAT:</strong></p>
          <p id="historyOrder-vat"></p>
        </div>
        <div class="d-flex justify-content-between">
          <p><strong>Discount:</strong></p>
          <p id="historyOrder-discount"></p>
        </div>
        <div class="d-flex justify-content-between">
          <p><strong>Delivery Fee:</strong></p>
          <p id="historyOrder-delivery-fee"></p>
        </div>
        <div class="d-flex justify-content-between border-top pt-2">
          <p><strong>Grand Total:</strong></p>
          <p id="historyOrder-grand-total"></p>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
