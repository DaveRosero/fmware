<!-- POS Items Modal (Receipt Style) -->
<div class="modal fade" id="historyPOS-items-modal" tabindex="-1" aria-labelledby="historyPOS-items-modal-label" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="historyPOS-items-modal-label">POS Receipt</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <!-- POS Info Section -->
        <div class="mb-3">
          <h6><strong>POS Information</strong></h6>
          <p><strong>Date:</strong> <span id="historyPOS-date"></span></p>
          <p><strong>Status:</strong> <span id="historyPOS-status"></span></p>
          <p><strong>Paid:</strong> <span id="historyPOS-paid"></span></p>
          <p><strong>Customer Name:</strong> <span id="historyPOS-user-name"></span></p>
          <p><strong>Phone#:</strong> <span id="historyPOS-user-phone"></span></p>
          <p><strong>Address:</strong> <span id="historyPOS-address"></span></p>
        </div>

        <!-- Items Section -->
        <div class="mb-3">
          <h6><strong>POS Items</strong></h6>
          <div id="historyPOS-items-container">
            <!-- Individual Items will be populated here -->
          </div>
        </div>

        <!-- Price Section -->
        <div class="d-flex justify-content-between border-top pt-2">
          <p><strong>Subtotal:</strong></p>
          <p id="historyPOS-subtotal"></p>
        </div>
        <div class="d-flex justify-content-between">
          <p><strong>Discount:</strong></p>
          <p id="historyPOS-discount"></p>
        </div>
        <div class="d-flex justify-content-between">
          <p><strong>Delivery Fee:</strong></p>
          <p id="historyPOS-delivery-fee"></p>
        </div>
        <div class="d-flex justify-content-between border-top pt-2">
          <p><strong>Grand Total:</strong></p>
          <p id="historyPOS-grand-total"></p>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>